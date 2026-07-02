---
name: crud-padrao
description: Padrão obrigatório para todo CRUD do Sistema de Gestão de Bibliotecas — estrutura de controller, rotas, Form Requests, paginação e mensagens. Use sempre que criar ou editar um recurso CRUD (Categoria, Livro, Empréstimo ou qualquer novo recurso).
---

# Padrão de CRUD — Sistema de Gestão de Bibliotecas

Todo recurso CRUD do projeto (Categorias, Livros, Empréstimos, e qualquer futuro recurso) deve seguir exatamente esta estrutura, para que o código fique previsível e fácil de revisar.

## Rotas

- Sempre `Route::resource('recurso', RecursoController::class)` dentro do grupo `middleware(['auth'])` (e `role:admin` quando a área for restrita a administradores).
- Nomes de rota em português/plural, ex.: `livros.index`, `livros.create`, `livros.store`, `livros.edit`, `livros.update`, `livros.destroy`. Nunca criar rotas ad-hoc fora do padrão resourceful sem necessidade real.

## Controller

- Um `Controller` resourceful por entidade (`app/Http/Controllers/LivroController.php` etc.), métodos `index`, `create`, `store`, `edit`, `update`, `destroy` (e `show` só quando a tela de detalhe agregar valor real).
- Controller nunca valida "na mão": sempre delega para um Form Request dedicado (`StoreLivroRequest`, `UpdateLivroRequest`).
- Controller nunca deve ter lógica de negócio complexa embutida — regras como "decrementar quantidade_disponivel" ficam num método do Model (ex.: `Livro::reservarExemplar()`) para ficar testável isoladamente.
- Sempre retornar `redirect()->route(...)->with('success', 'Mensagem clara em português')` após store/update/destroy.

## Form Requests

- Um Form Request por ação de escrita. `authorize()` sempre retorna a checagem de policy/role pertinente (nunca `return true` sem pensar).
- Regras de validação explícitas com mensagens em português quando o nome do campo não for autoexplicativo.
- Reaproveitar regras comuns entre Store/Update via um trait ou método protegido quando fizer sentido, evitando duplicação.

## Views

- Estrutura de pastas por recurso: `resources/views/{recurso}/index.blade.php`, `create.blade.php`, `edit.blade.php` (e `show.blade.php` se existir), sempre usando os componentes da skill `identidade-visual`.
- `create.blade.php` e `edit.blade.php` devem compartilhar o mesmo parcial de formulário (`_form.blade.php`) para não duplicar campos.
- Toda listagem usa `paginate(10)` (nunca `get()` sem paginação) e preserva query strings de filtro/busca com `->withQueryString()`.

## Validação e mensagens

- Erros de validação exibidos por campo, próximos ao input correspondente.
- Toda operação de sucesso gera mensagem flash (`session('success')`) exibida no layout.
- Exclusões sempre pedem confirmação no frontend antes de submeter o formulário DELETE.

## Boas práticas Laravel

- Mass assignment controlado via `$fillable` explícito no Model — nunca `$guarded = []`.
- Relacionamentos Eloquent (`belongsTo`, `hasMany`) usados para joins, nunca queries SQL manuais para o que o Eloquent já resolve.
- Factories + Seeders para dados de teste, nunca INSERT manual.
