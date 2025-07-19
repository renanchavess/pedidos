<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produtos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Produto_model');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index() {
        $data['produtos'] = $this->Produto_model->get_all();
        $data['content'] = 'produtos_view';
        $data['title'] = 'Lista de Produtos';
        $this->load->view('template', $data);
    }

    public function criar() {
        if ($this->input->post()) {
            $dados_produto = array(
                'nome' => $this->input->post('nome'),
                'preco' => $this->input->post('preco')
            );

            if ($dados_produto['preco'] < 0) {
                $this->session->set_flashdata('error', 'O preço não pode ser negativo.');
                redirect('produtos/criar');
            }

            $variacoes = $this->input->post('variacoes');

            if (empty($variacoes) || !is_array($variacoes)) {
                $this->session->set_flashdata('error', 'É necessário adicionar pelo menos uma variação.');
                redirect('produtos/criar');
            }

            foreach ($variacoes as $variacao) {
                if ($variacao['quantidade'] < 0) {
                    $this->session->set_flashdata('error', 'O estoque não pode ser negativo.');
                    redirect('produtos/criar');
                }
            }

            $produto_id = $this->Produto_model->insert($dados_produto);

            foreach ($variacoes as $variacao) {
                if (!empty($variacao['nome']) && isset($variacao['quantidade'])) {
                    $dados_variacao = array(
                        'produto_id' => $produto_id,
                        'variacao' => $variacao['nome'],
                        'quantidade' => $variacao['quantidade']
                    );
                    $this->Produto_model->insert_estoque($dados_variacao);
                }
            }

            redirect('produtos');
        } else {
            $data['content'] = 'produtos_form';
            $data['title'] = 'Adicionar Produto';
            $this->load->view('template', $data);
        }
    }

    public function editar($id) {
        if ($this->input->post()) {
            $dados_produto = array(
                'nome' => $this->input->post('nome'),
                'preco' => $this->input->post('preco')
            );

            if ($dados_produto['preco'] < 0) {
                $this->session->set_flashdata('error', 'O preço não pode ser negativo.');
                redirect('produtos/editar/' . $id);
            }

            $variacoes = $this->input->post('variacoes');

            foreach ($variacoes as $variacao) {
                if ($variacao['quantidade'] < 0) {
                    $this->session->set_flashdata('error', 'O estoque não pode ser negativo.');
                    redirect('produtos/editar/' . $id);
                }
            }

            $this->Produto_model->update($id, $dados_produto);

            foreach ($variacoes as $variacao) {
                if (isset($variacao['id']) && $this->Produto_model->variacao_existe($variacao['id'])) {
                    $dados_variacao = array(
                        'variacao' => $variacao['nome'],
                        'quantidade' => $variacao['quantidade']
                    );
                    $this->Produto_model->update_estoque($variacao['id'], $dados_variacao);
                } else {
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
            $data['variacoes'] = $this->Produto_model->get_estoque($id);
            $data['content'] = 'produtos_form';
            $data['title'] = 'Editar Produto';
            $this->load->view('template', $data);
        }
    }
}