<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Pedido_model');
        $this->load->model('Produto_model');
        $this->load->library('email_service');
    }

    public function checkout() {
        $carrinho = $this->session->userdata('carrinho') ?: [];
        $frete = $this->calcular_frete($carrinho);

        if ($this->input->post()) {
            $subtotal = array_reduce($carrinho, function($total, $item) {
                return $total + ($item['preco'] * $item['quantidade']);
            }, 0);

            $total = $subtotal + $frete;

            $cep = $this->input->post('cep');
            $url = "https://viacep.com.br/ws/$cep/json/";
            $response = file_get_contents($url);
            $cep_data = json_decode($response, true);

            if (isset($cep_data['erro'])) {
                $this->session->set_flashdata('error', 'CEP inválido. Por favor, tente novamente.');
                redirect('carrinho');
            }

            $dados_pedido = [
                'cliente_nome' => $this->input->post('cliente_nome'),
                'cliente_email' => $this->input->post('cliente_email'),
                'cep' => $cep,
                'endereco' => $cep_data['logradouro'] . ', ' . $cep_data['bairro'] . ', ' . $cep_data['localidade'] . ' - ' . $cep_data['uf'],
                'subtotal' => $subtotal,
                'frete' => $frete,
                'total' => $total
            ];

            $pedido_id = $this->Pedido_model->insert($dados_pedido);

            foreach ($carrinho as $item) {
                $dados_item = [
                    'pedido_id' => $pedido_id,
                    'produto_id' => $item['produto_id'],
                    'variacao_id' => $item['variacao_id'],
                    'quantidade' => $item['quantidade'],
                    'preco' => $item['preco']
                ];
                $this->Pedido_model->insert_item($dados_item);

                $this->Produto_model->update_estoque($item['variacao_id'], [
                    'quantidade' => $item['quantidade']
                ]);
            }

            if (!$this->email_service->enviar_confirmacao_pedido(
                $this->input->post('cliente_email'),
                $this->input->post('cliente_nome'),
                $subtotal,
                $frete,
                $total,
                $dados_pedido['endereco'],
                $cep
            )) {
                log_message('error', 'Falha ao enviar e-mail de confirmação para ' . $this->input->post('cliente_email'));
            }

            $this->session->unset_userdata('carrinho');
            redirect('pedidos/sucesso');
        } else {
            $data['carrinho'] = $carrinho;
            $data['frete'] = $frete;
            $data['content'] = 'checkout_view';
            $data['title'] = 'Finalizar Pedido';
            $this->load->view('template', $data);
        }
    }

    public function sucesso() {
        $data['content'] = 'sucesso_view';
        $data['title'] = 'Pedido Finalizado';
        $this->load->view('template', $data);
    }

    private function calcular_frete($carrinho) {
        $subtotal = array_reduce($carrinho, function($total, $item) {
            return $total + ($item['preco'] * $item['quantidade']);
        }, 0);

        if ($subtotal >= 52 && $subtotal <= 166.59) {
            return 15.00;
        } elseif ($subtotal > 200) {
            return 0.00;
        } else {
            return 20.00;
        }
    }
}