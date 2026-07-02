---
name: seguranca
description: Diretrizes de segurança do Sistema de Gestão de Bibliotecas — autorização por perfil, proteção de mass assignment, CSRF e senhas. Use ao criar rotas, controllers, models ou qualquer ponto que trate dados de usuário ou controle de acesso.
---

# Segurança — Sistema de Gestão de Bibliotecas

## Autorização por perfil

- Existem dois perfis: `admin` (bibliotecário) e `leitor`. Toda rota de escrita (create/store/edit/update/destroy) de Categorias, Livros e Empréstimos é restrita a `admin` via middleware de rota (`role:admin`) — nunca confiar apenas em esconder o botão na UI.
- Leitores autenticados só podem visualizar o catálogo e os próprios empréstimos (`emprestimos.index` filtrado por `user_id` do usuário logado) — nunca listar empréstimos de outros leitores para um usuário não-admin.
- Sempre verificar autorização no backend (middleware ou `$this->authorize(...)`), nunca só no Blade (`@if(auth()->user()->role === 'admin')` é só para esconder UI, não substitui a checagem no controller/rota).

## Mass assignment e dados sensíveis

- Todo Model define `$fillable` explícito — nunca `$guarded = []`.
- Campo `password` sempre com cast `hashed` (padrão do Breeze) — nunca salvar senha em texto puro nem logar o valor de `password`.
- Nunca expor o campo `password`/`remember_token` em qualquer view ou resposta.

## CSRF e formulários

- Todo formulário Blade usa `@csrf` (padrão do Laravel) — não desabilitar a proteção CSRF em nenhuma rota web.
- Ações destrutivas (delete) usam o verbo HTTP correto via `@method('DELETE')`, nunca um link `GET` disfarçado de exclusão.

## Validação de entrada

- Toda entrada do usuário passa por Form Request com regras explícitas (ver skill `crud-padrao`) — nunca confiar em dado vindo do cliente sem validar (ex.: `categoria_id` deve validar `exists:categorias,id`).
- Queries que recebem filtro do usuário (busca por título, filtro de status) sempre via Eloquent parametrizado (`where(...)`), nunca concatenação de SQL cru.

## Sessão e autenticação

- Login/registro/logout usam exclusivamente o fluxo do Breeze já instalado — não reimplementar autenticação manualmente.
- Rotas autenticadas sempre dentro do middleware `auth` (e `verified` se e-mail verificado for exigido).
