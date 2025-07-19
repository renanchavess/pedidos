# Projeto CodeIgniter - Sistema de Pedidos

Este projeto é um sistema de pedidos desenvolvido com CodeIgniter 3. Ele inclui funcionalidades como gerenciamento de produtos, variações, carrinho de compras e finalização de pedidos.

---

## **Instruções de Configuração**

### **1. Executar o SQL de Inicialização**
Para configurar o banco de dados, execute o script SQL localizado em `application/migrations/20250717120000_init.sql`. 

#### **Passos:**
1. Acesse seu banco de dados MySQL.
2. Execute o seguinte comando para importar o script:
   ```bash
   mysql -u root -p pedidos < application/migrations/20250717120000_init.sql
   ```
3. Certifique-se de que as tabelas foram criadas corretamente:
   - `produtos`
   - `estoque`
   - `pedidos`
   - `cupons`

---

### **2. Rodar o Docker**
Este projeto utiliza Docker para facilitar a execução do ambiente.

#### **Passos:**
1. Certifique-se de que o Docker está instalado em sua máquina.
2. No terminal, navegue até o diretório do projeto.
3. Execute o seguinte comando para iniciar o container:
   ```bash
   docker-compose up -d
   ```
4. Acesse o sistema no navegador:
   - URL: `http://localhost:8080`

---

### **3. Tecnologias Utilizadas**
- **Backend**: CodeIgniter 3 com PHP 7.4
- **Banco de Dados**: MySQL
- **Frontend**: Bootstrap e Javascript
- **Containerização**: Docker

---

## **4. API - Alterar Status do Pedido**

### **Endpoint**
- **URL**: `http://localhost:8080/pedidos/webhook`
- **Método**: `POST`

### **Descrição**
Este endpoint permite alterar o status de um pedido ou removê-lo, dependendo do status enviado.

### **Parâmetros**
- **`id`**: ID do pedido (obrigatório).
- **`status`**: Novo status do pedido (obrigatório). Os valores permitidos são:
  - `pago`: Atualiza o status do pedido para "pago".
  - `cancelado`: Remove o pedido.

### **Exemplo de Requisição**

#### **Com `curl`**
```bash
curl -X POST http://localhost:8080/pedidos/webhook \
     -H "Content-Type: application/json" \
     -d '{
           "id": 1,
           "status": "pago"
         }'
```

---

### **5. Contato**
- **Autor**: Renan Rodrigues Chaves
- **E-mail**: renanchavess@gmail.com