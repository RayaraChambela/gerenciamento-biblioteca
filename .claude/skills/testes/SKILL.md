---
name: testes
description: Convenções de testes automatizados (PHPUnit) do Sistema de Gestão de Bibliotecas — Feature tests por CRUD e Unit tests de regra de negócio. Use ao escrever ou revisar qualquer teste do projeto.
---

# Testes Automatizados — Sistema de Gestão de Bibliotecas

O projeto usa **PHPUnit** com as classes base do Laravel (é o que o `composer create-project laravel/laravel` já traz por padrão — não instalar Pest à parte). Objetivo: cobrir os 3 CRUDs e a regra de negócio de empréstimo, mirando o bônus de testes do trabalho.

## Organização

- `tests/Feature/` — um arquivo por recurso (`CategoriaTest.php`, `LivroTest.php`, `EmprestimoTest.php`), testando o fluxo HTTP completo (rota → controller → banco → resposta), estendendo `Tests\TestCase`.
- `tests/Unit/` — regras de negócio isoladas do Model, ex. `LivroTest.php` testando `reservarExemplar()`/`liberarExemplar()` sem passar pelo HTTP.
- Nunca duplicar teste de validação básica em Feature e Unit — validação de campo fica em Feature (via Form Request real), regra de negócio pura fica em Unit.

## O que cobrir em cada Feature test de CRUD

1. Usuário não autenticado é redirecionado para login ao acessar qualquer rota do recurso.
2. Usuário `leitor` recebe 403 ao tentar acessar rotas de escrita (create/store/edit/update/destroy).
3. Usuário `admin` consegue listar, criar, editar e excluir um registro com sucesso (assert de redirect + assert no banco com `assertDatabaseHas`/`assertDatabaseMissing`).
4. Validação: submeter dados inválidos retorna erros de validação esperados (`assertSessionHasErrors`).

## O que cobrir no teste de regra de negócio (Empréstimo)

- Registrar empréstimo decrementa `quantidade_disponivel` do livro em 1.
- Não é possível reservar um exemplar quando `quantidade_disponivel` é 0 (deve lançar exceção).
- Marcar devolução incrementa `quantidade_disponivel` de volta e preenche `data_devolucao`.

## Convenções PHPUnit

- Métodos de teste com nome descritivo em `snake_case` prefixado por `test_` (ex.: `test_admin_consegue_criar_categoria`) ou usando o atributo `#[Test]`.
- Sempre usar a trait `RefreshDatabase` para isolar o banco entre testes.
- Usar Factories (`Livro::factory()`, `User::factory()->admin()` / `->leitor()` conforme o Model) para montar cenários — nunca inserir registros manualmente com array cru.
- Rodar com `php artisan test` antes de considerar qualquer CRUD "pronto".
