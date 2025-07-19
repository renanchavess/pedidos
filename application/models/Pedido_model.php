<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedido_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert($dados) {
        $this->db->insert('pedidos', $dados);
        return $this->db->insert_id();
    }

    public function insert_item($dados) {
        $this->db->insert('pedidos_itens', $dados);
    }

    public function get_all() {
        $this->db->select('id, cliente_nome, cliente_email, subtotal, frete, total, status, criado_em');
        return $this->db->get('pedidos')->result_array();
    }

    public function get_itens($pedido_id) {
        $this->db->select('produtos.nome AS produto, estoque.variacao, pedidos_itens.quantidade, pedidos_itens.preco');
        $this->db->join('estoque', 'estoque.id = pedidos_itens.variacao_id', 'left');
        $this->db->join('produtos', 'produtos.id = pedidos_itens.produto_id', 'left');
        $this->db->where('pedidos_itens.pedido_id', $pedido_id);
        return $this->db->get('pedidos_itens')->result_array();
    }

    public function get($id) {
        return $this->db->get_where('pedidos', ['id' => $id])->row_array();
    }

    public function update_status($id, $status) {
        $this->db->where('id', $id);
        $this->db->update('pedidos', ['status' => $status]);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('pedidos');
    }
}