<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_service {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library('email');
    }

    public function enviar_confirmacao_pedido($cliente_email, $cliente_nome, $subtotal, $frete, $total, $endereco, $cep) {
        $this->CI->email->from('loja@exemplo.com', 'Minha Loja');
        $this->CI->email->to($cliente_email);
        $this->CI->email->subject('Confirmação de Pedido');
        $this->CI->email->message("
            Olá {$cliente_nome},

            Obrigado por realizar seu pedido!

            Detalhes do pedido:
            Subtotal: R$ " . number_format($subtotal, 2, ',', '.') . "
            Frete: R$ " . number_format($frete, 2, ',', '.') . "
            Total: R$ " . number_format($total, 2, ',', '.') . "

            Endereço de entrega:
            {$endereco}
            CEP: {$cep}

            Em breve você receberá mais informações sobre o envio.

            Atenciosamente,
            Minha Loja
        ");

        if (!$this->CI->email->send()) {
            log_message('error', 'Erro ao enviar e-mail: ' . $this->CI->email->print_debugger());
            return false;
        }

        return true;
    }
}