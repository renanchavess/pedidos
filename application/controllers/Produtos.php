<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produtos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Produto_model'); // Carrega o model de produtos
        $this->load->helper('url'); // Carrega o helper de URL
    }

    public function index() {
        // Lista todos os produtos
        $data['produtos'] = $this->Produto_model->get_all();
        $data['content'] = 'produtos_view'; // Nome da view principal
        $data['title'] = 'Lista de Produtos'; // Título da página
        $this->load->view('template', $data);
    }

    public function criar() {
        if ($this->input->post()) {
            $dados_produto = array(
                'nome' => $this->input->post('nome'),
                'preco' => $this->input->post('preco')
            );

            $produto_id = $this->Produto_model->insert($dados_produto);

            $variacoes = $this->input->post('variacoes');
            foreach ($variacoes as $variacao) {
                $dados_variacao = array(
                    'produto_id' => $produto_id,
                    'variacao' => $variacao['nome'],
                    'quantidade' => $variacao['quantidade']
                );
                $this->Produto_model->insert_estoque($dados_variacao);
            }

            redirect('produtos');
        } else {
            $this->load->view('produtos_form');
        }
    }

    public function editar($id) {
        if ($this->input->post()) {
            $dados_produto = array(
                'nome' => $this->input->post('nome'),
                'preco' => $this->input->post('preco')
            );

            $this->Produto_model->update($id, $dados_produto);

            // Atualiza ou adiciona variações
            $variacoes = $this->input->post('variacoes');
            foreach ($variacoes as $variacao_id => $variacao) {
                if ($this->Produto_model->variacao_existe($variacao_id)) {
                    // Atualiza variação existente
                    $dados_variacao = array(
                        'variacao' => $variacao['nome'],
                        'quantidade' => $variacao['quantidade']
                    );
                    $this->Produto_model->update_estoque($variacao_id, $dados_variacao);
                } else {
                    // Adiciona nova variação
                    $dados_variacao = array(
                        'produto_id' => $id,
                        'variacao' => $variacao['nome'],
                        'quantidade' => $variacao['quantidade']
                    );
                    $this->Produto_model->insert_estoque($dados_variacao);
                }
            }

            redirect('produtos');
        } else {
            $data['produto'] = $this->Produto_model->get($id);
            $data['variacoes'] = $this->Produto_model->get_estoque($id); // Busca as variações relacionadas ao produto
            $data['content'] = 'produtos_form'; // Nome da view principal
            $data['title'] = 'Editar Produto'; // Título da página
            $this->load->view('template', $data);
        }
    }
}