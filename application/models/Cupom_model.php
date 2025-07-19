<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cupom_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all() {
        return $this->db->get('cupons')->result_array();
    }

    public function get($id) {
        return $this->db->get_where('cupons', array('id' => $id))->row_array();
    }

    public function insert($dados) {
        $this->db->insert('cupons', $dados);
        return $this->db->insert_id();
    }

    public function update($id, $dados) {
        $this->db->where('id', $id);
        $this->db->update('cupons', $dados);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('cupons');
    }

    public function get_by_codigo($codigo) {
        return $this->db->get_where('cupons', array('codigo' => $codigo))->row_array();
    }

    public function get_validos() {
        $this->db->where('validade >=', date('Y-m-d'));
        return $this->db->get('cupons')->result_array();
    }
}