<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cupons extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Cupom_model');
        $this->load->helper('url');
    }

    public function index() {
        $data['cupons'] = $this->Cupom_model->get_all();
        $data['content'] = 'cupons_view';
        $data['title'] = 'Lista de Cupons';
        $this->load->view('template', $data);
    }

    public function criar() {
        if ($this->input->post()) {
            $dados_cupom = array(
                'codigo' => $this->input->post('codigo'),
                'desconto' => $this->input->post('desconto'),
                'validade' => $this->input->post('validade'),
                'valor_minimo' => $this->input->post('valor_minimo')
            );

            $this->Cupom_model->insert($dados_cupom);
            redirect('cupons');
        } else {
            $data['content'] = 'cupons_form';
            $data['title'] = 'Criar Cupom';
            $this->load->view('template', $data);
        }
    }

    public function editar($id) {
        if ($this->input->post()) {
            $dados_cupom = array(
                'codigo' => $this->input->post('codigo'),
                'desconto' => $this->input->post('desconto'),
                'validade' => $this->input->post('validade'),
                'valor_minimo' => $this->input->post('valor_minimo')
            );

            $this->Cupom_model->update($id, $dados_cupom);
            redirect('cupons');
        } else {
            $data['cupom'] = $this->Cupom_model->get($id);
            $data['content'] = 'cupons_form';
            $data['title'] = 'Editar Cupom';
            $this->load->view('template', $data);
        }
    }
}