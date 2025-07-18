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

### **3. Funcionalidades Concluídas**
#### **Produtos**
- Listagem de produtos agrupados por nome, com soma de quantidades e variações concatenadas.
- Tela de edição de produto dividida em duas colunas:
  - **Esquerda**: Formulário para editar o produto.
  - **Direita**: Formulário para adicionar ao carrinho.

#### **Carrinho**
- Adicionar produtos ao carrinho com variações específicas.
- Máscara de validação para o campo CEP.
- Validação de e-mail e nome no formulário de checkout.

#### **Pedidos**
- Finalização de pedidos com cálculo de subtotal, frete e total.
- Envio de e-mail de confirmação ao cliente com os detalhes do pedido.
- Tratamento de falhas no envio de e-mail sem interrupção do fluxo.

#### **Banco de Dados**
- Script SQL para inicialização do banco de dados (`20250717120000_init.sql`).

---

### **4. Tecnologias Utilizadas**
- **Backend**: CodeIgniter 3 com PHP 7.4
- **Banco de Dados**: MySQL
- **Frontend**: Bootstrap e Javascript
- **Containerização**: Docker

---

### **5. Contato**
- **Autor**: Renan Rodrigues Chaves
- **E-mail**: renanchavess@gmail.com