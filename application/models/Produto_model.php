<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produto_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all() {
        $this->db->select('produtos.id, produtos.nome, produtos.preco, GROUP_CONCAT(estoque.variacao SEPARATOR ", ") AS variacoes, SUM(estoque.quantidade) AS quantidade');
        $this->db->join('estoque', 'estoque.produto_id = produtos.id', 'left');
        $this->db->group_by('produtos.id');
        return $this->db->get('produtos')->result_array();
    }

    public function get($id) {
        return $this->db->get_where('produtos', array('id' => $id))->row_array();
    }

    public function get_estoque($produto_id) {
        $this->db->select('id, variacao, quantidade');
        $this->db->where('produto_id', $produto_id);
        return $this->db->get('estoque')->result_array();
    }

    public function insert($dados) {
        $this->db->insert('produtos', $dados);
        return $this->db->insert_id();
    }

    public function insert_estoque($dados) {
        $this->db->insert('estoque', $dados);
    }

    public function update($id, $dados) {
        $this->db->where('id', $id);
        $this->db->update('produtos', $dados);
    }

    public function update_estoque($id, $dados) {
        $this->db->where('id', $id);
        $this->db->update('estoque', $dados);
    }

    public function variacao_existe($variacao_id) {
        $this->db->where('id', $variacao_id);
        $query = $this->db->get('estoque');
        return $query->num_rows() > 0;
    }

    public function delete_estoque($id) {
        $this->db->where('id', $id);
        $this->db->delete('estoque');
    }
}