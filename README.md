# Sistema de Gestão de Bibliotecas

Aplicação web em Laravel para controle de acervo de uma biblioteca: cadastro de categorias e livros, e o ciclo completo de empréstimo/devolução de exemplares aos leitores cadastrados, com dashboard de métricas e dois perfis de acesso (bibliotecário/admin e leitor).

Trabalho da disciplina **Desenvolvimento de Aplicações com Laravel + Laravel Boost + Vibe Coding** (IFSP, Prof. Dr. Reginaldo do Prado). Veja também [`PLANO_IMPLEMENTACAO.md`](PLANO_IMPLEMENTACAO.md) e [`RELATORIO.md`](RELATORIO.md).

## Funcionalidades

- Autenticação (login/registro) via Laravel Breeze.
- CRUD de Categorias.
- CRUD de Livros, com busca por título/autor e filtro por categoria.
- CRUD de Empréstimos: registrar empréstimo e marcar devolução, com baixa/reposição automática da quantidade de exemplares disponíveis.
- Dashboard com métricas (total de livros, exemplares disponíveis, empréstimos ativos/atrasados para o admin; empréstimos e catálogo para o leitor).
- Controle de acesso por perfil: apenas `admin` gerencia categorias, livros e empréstimos; `leitor` visualiza o catálogo e seus próprios empréstimos.

## Tecnologias utilizadas

- [Laravel 13](https://laravel.com) (PHP 8.5)
- [Laravel Boost](https://github.com/laravel/boost) — MCP server e guidelines de IA para o desenvolvimento
- [Laravel Breeze](https://laravel.com/docs/starter-kits#breeze) (stack Blade) para autenticação
- SQLite como banco de dados
- Tailwind CSS (padrão do Breeze)
- PHPUnit para os testes automatizados

## Instalação

### Pré-requisitos

- PHP 8.5+ com as extensões padrão do Laravel
- Composer
- Node.js + npm

### Passo a passo

```bash
# Clonar o repositório
git clone <url-do-repositorio>
cd <pasta-do-projeto>

# Instalar dependências PHP
composer install

# Configurar o .env
cp .env.example .env
php artisan key:generate
```

O `.env` já vem configurado para usar SQLite (`DB_CONNECTION=sqlite`). Crie o arquivo do banco antes de migrar:

```bash
# Windows (PowerShell)
New-Item -ItemType File -Path database/database.sqlite

# Linux/Mac
touch database/database.sqlite
```

Se preferir usar MySQL/Postgres, ajuste as variáveis `DB_*` no `.env` para o seu servidor antes do próximo passo.

### Banco de dados, migrations e seeders

```bash
php artisan migrate:fresh --seed
```

Esse comando cria toda a estrutura de tabelas e popula o banco com categorias, livros de exemplo e os usuários de teste abaixo.

### Assets e execução

```bash
npm install
npm run build   # ou `npm run dev` durante o desenvolvimento

php artisan serve
```

Acesse `http://127.0.0.1:8000` e faça login com um dos usuários de teste.

### Laravel Boost (MCP + guidelines)

O Boost já está instalado e versionado no repositório (`boost.json`, `.mcp.json`, `CLAUDE.md`, skills em `.ai/skills`). Se precisar reinstalar/reconfigurar em outra máquina:

```bash
composer require laravel/boost --dev
php artisan boost:install
```

### Rodando os testes

```bash
php artisan test
```

## Usuários de teste

Todos criados automaticamente pelo `UserSeeder` (senha igual para todos: `password`).

| E-mail | Senha | Perfil de acesso |
|---|---|---|
| admin@biblioteca.com | password | Bibliotecário(a) (admin) — acesso total |
| leitor@biblioteca.com | password | Leitor(a) — catálogo e empréstimos próprios |
| maria@biblioteca.com | password | Leitor(a) — catálogo e empréstimos próprios |

Além desses, o seeder cria mais 5 leitores aleatórios (via factory) apenas para popular o histórico de empréstimos de demonstração.
