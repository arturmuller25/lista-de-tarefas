# Lista de Tarefas (Laravel)

Aplicação web de lista de tarefas (to-do list) desenvolvida em Laravel 12 como trabalho da disciplina de Programação Web.

O usuário pode inserir, editar e remover tarefas, marcá-las como concluídas e anexar uma imagem por tarefa. As tarefas concluídas são exibidas no final da lista, com a ordenação feita via Eloquent/SQL, e o CSS diferencia visualmente o que está pendente do que já foi concluído.

## Funcionalidades

- CRUD de tarefas: inserir, editar e remover.
- Conclusão: marcar e desmarcar tarefas como concluídas.
- Imagem por tarefa: adicionar e remover uma imagem em cada tarefa (upload no disco `public`).
- Ordenação: tarefas concluídas aparecem no final da lista, usando `orderBy('concluida')` no Eloquent.
- CSS: destaque visual distinto para tarefas concluídas (riscadas, fundo verde) e pendentes.
- Lixeira (soft delete): ao excluir, a tarefa vai para a lixeira, podendo ser restaurada ou excluída definitivamente.
- Validação de formulários com mensagens em português e mensagens de feedback (flash messages).

## Requisitos

- PHP 8.2 ou superior, com as extensões `pdo_sqlite` e `fileinfo`.
- Composer.

O projeto utiliza SQLite como banco de dados, portanto não é necessário instalar MySQL.

## Como rodar

```bash
# 1. Clonar o repositório
git clone https://github.com/arturmuller25/lista-de-tarefas.git
cd lista-de-tarefas

# 2. Instalar as dependências
composer install

# 3. Configurar o ambiente
cp .env.example .env
php artisan key:generate

# 4. Criar o banco SQLite e rodar as migrations
#    (no Windows, caso o comando "touch" não exista, crie o
#    arquivo database/database.sqlite manualmente)
touch database/database.sqlite
php artisan migrate

# 5. Criar o link para servir as imagens enviadas
php artisan storage:link

# 6. Subir o servidor de desenvolvimento
php artisan serve
```

Em seguida, basta acessar http://localhost:8000 no navegador.

## Testes

A aplicação possui testes automatizados que cobrem o fluxo completo: criação, edição, conclusão, ordenação, upload e remoção de imagem e lixeira.

```bash
php artisan test
```

## Estrutura principal

| Arquivo | Descrição |
|---|---|
| `database/migrations/2026_06_19_120000_create_tarefas_table.php` | Migration da tabela `tarefas` |
| `app/Models/Tarefa.php` | Model `Tarefa` (Eloquent com SoftDeletes) |
| `app/Http/Controllers/TarefaController.php` | Controller com todas as ações |
| `routes/web.php` | Rotas web nomeadas |
| `resources/views/_base.blade.php` | Layout base |
| `resources/views/tarefas/` | Views (`index`, `editar`, `lixeira`) |
| `public/css/style.css` | Estilos |

## Rotas

| Método | URI | Ação |
|---|---|---|
| GET | `/` | Lista de tarefas |
| POST | `/tarefas` | Criar tarefa |
| GET | `/tarefas/{tarefa}/editar` | Formulário de edição |
| PUT | `/tarefas/{tarefa}` | Atualizar tarefa |
| PATCH | `/tarefas/{tarefa}/concluir` | Marcar ou desmarcar como concluída |
| DELETE | `/tarefas/{tarefa}/imagem` | Remover a imagem da tarefa |
| DELETE | `/tarefas/{tarefa}` | Enviar para a lixeira |
| GET | `/tarefas/lixeira` | Listar a lixeira |
| PATCH | `/tarefas/{tarefa}/restaurar` | Restaurar da lixeira |
| DELETE | `/tarefas/{tarefa}/destruir` | Excluir definitivamente |
