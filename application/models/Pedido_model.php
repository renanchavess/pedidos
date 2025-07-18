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
}