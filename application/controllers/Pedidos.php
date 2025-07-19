<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Pedido_model');
        $this->load->model('Produto_model');
        $this->load->model('Cupom_model');
        $this->load->library('email_service');
    }

    public function index() {
        $data['pedidos'] = $this->Pedido_model->get_all();
        $data['content'] = 'pedidos_view';
        $data['title'] = 'Lista de Pedidos';
        $this->load->view('template', $data);
    }

    public function checkout() {
        $cliente_nome = $this->input->post('cliente_nome');
        $cliente_email = $this->input->post('cliente_email');
        $cep = $this->input->post('cep');
        $cupom_codigo = $this->input->post('cupom');

        $this->session->set_flashdata('cliente_nome', $cliente_nome);
        $this->session->set_flashdata('cliente_email', $cliente_email);
        $this->session->set_flashdata('cep', $cep);
        $this->session->set_flashdata('cupom', $cupom_codigo);

        $carrinho = $this->session->userdata('carrinho') ?: [];
        $frete = $this->calcular_frete($carrinho);

        $this->validar_estoque($carrinho);
        $cupom = $this->validar_cupom($cupom_codigo, $carrinho);
        $cep_data = $this->validar_cep($cep);

        $subtotal = array_reduce($carrinho, function($total, $item) {
            return $total + ($item['preco'] * $item['quantidade']);
        }, 0);

        $total = $subtotal + $frete;

        if ($cupom) {
            $desconto = ($subtotal * $cupom['desconto']) / 100;
            $total -= $desconto;
        }

        $dados_pedido = [
            'cliente_nome' => $cliente_nome,
            'cliente_email' => $cliente_email,
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

        $this->session->unset_userdata('carrinho');
        redirect('pedidos/sucesso');
    }

    private function validar_estoque($carrinho) {
        foreach ($carrinho as $item) {
            $estoque = $this->Produto_model->get_estoque($item['produto_id']);

            $variacao = array_filter($estoque, function($v) use ($item) {
                return $v['id'] == $item['variacao_id'];
            });

            $variacao = reset($variacao);

            if (!$variacao || $variacao['quantidade'] < $item['quantidade']) {
                $this->session->set_flashdata('error', "Estoque insuficiente para a variação '{$item['variacao']}'. Quantidade disponível: " . ($variacao['quantidade'] ?? 0) . ".");
                redirect('carrinho');
            }
        }
    }

    private function validar_cupom($cupom_codigo, $carrinho) {
        if (!$cupom_codigo) {
            return null;
        }

        $cupom = $this->Cupom_model->get_by_codigo($cupom_codigo);
        $subtotal = array_reduce($carrinho, function($total, $item) {
            return $total + ($item['preco'] * $item['quantidade']);
        }, 0);

        if (!$cupom || strtotime($cupom['validade']) < time() || $subtotal < $cupom['valor_minimo']) {
            $this->session->set_flashdata('error', 'Cupom inválido ou o subtotal não atende ao valor mínimo.');
            redirect('carrinho');
        }

        return $cupom;
    }

    private function validar_cep($cep) {
        $url = "https://viacep.com.br/ws/$cep/json/";
        $response = file_get_contents($url);
        $cep_data = json_decode($response, true);

        if (isset($cep_data['erro'])) {
            $this->session->set_flashdata('error', 'CEP inválido. Por favor, tente novamente.');
            redirect('carrinho');
        }

        return $cep_data;
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

    private function enviar_email_confirmacao($cliente_email, $cliente_nome, $subtotal, $frete, $total, $endereco, $cep) {
       $this->email_service->enviar_confirmacao_pedido(
            $cliente_email,
            $cliente_nome,
            $subtotal,
            $frete,
            $total,
            $endereco,
            $cep
        );
    }

    public function sucesso() {
        $data['content'] = 'sucesso_view';
        $data['title'] = 'Pedido Finalizado';
        $this->load->view('template', $data);
    }

    public function detalhes($id) {
        $pedido = $this->Pedido_model->get($id);
        $itens = $this->Pedido_model->get_itens($id);

        if (!$pedido) {
            show_404();
        }

        $data['pedido'] = $pedido;
        $data['itens'] = $itens;
        $data['content'] = 'pedido_detalhes_view';
        $data['title'] = 'Detalhes do Pedido';
        $this->load->view('template', $data);
    }

    public function webhook() {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            show_error('Método não permitido', 405);
        }

        $data = json_decode($this->input->raw_input_stream, true);

        if (!isset($data['id']) || !isset($data['status'])) {
            show_error('Dados inválidos', 400);
        }

        $pedido_id = $data['id'];
        $status = $data['status'];

        $status_permitidos = ['pago', 'cancelado'];
        if (!in_array($status, $status_permitidos)) {
            show_error('Status inválido. Permitidos: finalizado, cancelado.', 400);
        }

        $pedido = $this->Pedido_model->get($pedido_id);
        if (!$pedido) {
            show_error('Pedido não encontrado', 404);
        }

        if ($status === 'cancelado') {
            $this->Pedido_model->delete($pedido_id);
            $response = ['message' => 'Pedido removido com sucesso'];
        } else {
            $this->Pedido_model->update_status($pedido_id, $status);
            $response = ['message' => 'Status do pedido atualizado com sucesso'];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}