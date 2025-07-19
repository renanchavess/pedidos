<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Carrinho extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper(['url', 'form']);
        $this->load->model('Produto_model');
        $this->load->model('Cupom_model');
    }

    public function index() {
        $carrinho = $this->session->userdata('carrinho') ?: [];
        $frete = $this->calcular_frete($carrinho);

        $this->load->model('Cupom_model');
        $cupons_validos = $this->Cupom_model->get_validos();

        $data['cliente_nome'] = $this->session->flashdata('cliente_nome') ?: '';
        $data['cliente_email'] = $this->session->flashdata('cliente_email') ?: '';
        $data['cep'] = $this->session->flashdata('cep') ?: '';
        $data['cupom_selecionado'] = $this->session->flashdata('cupom') ?: '';

        $data['carrinho'] = $carrinho;
        $data['frete'] = $frete;
        $data['cupons'] = $cupons_validos;
        $data['content'] = 'carrinho_view';
        $data['title'] = 'Carrinho';
        $this->load->view('template', $data);
    }

    public function adicionar($produto_id) {
        $produto = $this->Produto_model->get($produto_id);

        if (!$produto) {
            redirect('produtos');
        }

        $variacao_id = $this->input->post('variacao_id') ?: null;
        $quantidade = $this->input->post('quantidade') ?: 1;

        $estoque = $this->Produto_model->get_estoque($produto_id);
        $variacao = array_filter($estoque, function($v) use ($variacao_id) {
            return $v['id'] == $variacao_id;
        });

        $variacao = reset($variacao);

        if (!$variacao || $variacao['quantidade'] < $quantidade) {
            redirect('produtos');
        }

        $carrinho = $this->session->userdata('carrinho') ?: [];
        $carrinho[] = [
            'produto_id' => $produto_id,
            'variacao_id' => $variacao_id,
            'nome' => $produto['nome'],
            'variacao' => $variacao['variacao'],
            'quantidade' => $quantidade,
            'preco' => $produto['preco']
        ];
        $this->session->set_userdata('carrinho', $carrinho);

        redirect('carrinho');
    }

    public function remover($index) {
        $carrinho = $this->session->userdata('carrinho') ?: [];
        unset($carrinho[$index]);
        $this->session->set_userdata('carrinho', $carrinho);

        redirect('carrinho');
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

    public function checkout() {
        $cupom_codigo = $this->input->post('cupom');
        $cliente_nome = $this->input->post('cliente_nome');
        $cliente_email = $this->input->post('cliente_email');
        $cep = $this->input->post('cep');

        $this->load->model('Cupom_model');
        $cupom = $this->Cupom_model->get_by_codigo($cupom_codigo);

        if (!$cupom || strtotime($cupom['validade']) < time()) {
            $this->session->set_flashdata('cliente_nome', $cliente_nome);
            $this->session->set_flashdata('cliente_email', $cliente_email);
            $this->session->set_flashdata('cep', $cep);
            $this->session->set_flashdata('cupom', $cupom_codigo);

            $this->session->set_flashdata('error', 'Cupom inválido ou expirado.');
            redirect('carrinho');
        }
    }
}