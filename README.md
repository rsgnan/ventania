# Ventania ERP

Sistema ERP para gestão de vendas físicas e controle de estoque, desenvolvido com Laravel, Vue 3 e PostgreSQL.

## Tecnologias

- PHP 8.3+ / Laravel 12
- Vue 3 + Inertia.js
- Tailwind CSS + shadcn-vue
- PostgreSQL

## Funcionalidades

- **Usuários:** Controle de acesso entre Administrador e Usuário Comum. Contas não são excluídas para manter o histórico de vendas, apenas ativadas ou desativadas.
- **Produtos e Kits:** Cadastro de produtos físicos (`PRO`) e kits (`KIT`) formados por mais de um produto. O estoque dos kits é calculado automaticamente com base no saldo dos itens que fazem parte dele.
- **Estoque:** O saldo só muda através de movimentações de entrada ou saída (em lote), mantendo o histórico de quanto tinha antes e quanto ficou depois.
- **Vendas:** Toda venda abre como `Pendente` (podendo ser editada) e só dá baixa no estoque quando muda para `Concluída`. Depois de concluída ou cancelada, a venda fica bloqueada para alterações.
- **Logs e Dashboard:** Resumo de faturamento, produtos mais vendidos, alertas de estoque baixo e registro das últimas ações feitas no sistema.

## Como rodar o projeto

1. Instale as dependências:
   composer install
   npm install

2. Configure o arquivo `.env` com os dados do seu PostgreSQL:
   cp .env.example .env
   php artisan key:generate

3. Rode as migrations com os dados iniciais e crie o link de imagens:
   php artisan migrate --seed
   php artisan storage:link

4. Inicie o servidor:
   composer run dev

## Login de teste

- **Admin:** `admin@ventania.com` / `password`
- **Vendedor:** `vendedor@ventania.com` / `password`

Para rodar os testes: `php artisan test`
