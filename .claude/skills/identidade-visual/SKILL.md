---
name: identidade-visual
description: Padrão visual obrigatório do Sistema de Gestão de Bibliotecas — paleta de cores, tipografia, componentes reutilizáveis, responsividade e UX. Use sempre que criar ou editar qualquer view Blade, layout ou componente visual do projeto.
---

# Identidade Visual — Sistema de Gestão de Bibliotecas

Todas as telas do projeto devem seguir este padrão. Nunca introduza uma cor, espaçamento ou componente fora do que está descrito aqui — se precisar de algo novo, generalize um componente Blade reutilizável em vez de estilizar inline.

## Paleta de cores (Tailwind)

- **Primária (azul-noite)**: `slate-800` / `slate-900` — cabeçalhos, sidebar, botões primários.
- **Destaque (dourado-livro)**: `amber-500` / `amber-600` — ações de destaque, badges de status "emprestado", links ativos.
- **Sucesso**: `emerald-600` (badge "devolvido", mensagens de sucesso).
- **Alerta/Atraso**: `red-600` (badge "atrasado", mensagens de erro).
- **Neutros**: `slate-50` (fundo), `slate-200` (bordas), `slate-600`/`slate-700` (texto secundário).

Nunca usar cores fora dessa paleta (nada de `blue-500`, `purple-*`, etc.) para manter consistência entre todas as telas.

## Tipografia

- Fonte padrão do Breeze: **Figtree** (`font-sans`), já configurada em `resources/css/app.css`/`tailwind.config.js`. Não trocar.
- Títulos de página: `text-2xl font-bold text-slate-900`.
- Subtítulos/seções: `text-lg font-semibold text-slate-800`.
- Texto de corpo: `text-sm text-slate-600`.

## Componentes padronizados

Sempre reutilize (ou crie em `resources/views/components/`) estes componentes em vez de recriar markup:

- **Botão primário**: `bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-md text-sm font-medium`.
- **Botão secundário/cancelar**: `bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-md text-sm font-medium`.
- **Botão destrutivo (excluir)**: `bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium`.
- **Card**: `bg-white shadow-sm rounded-lg border border-slate-200 p-6`.
- **Badge de status**: pill `text-xs font-medium px-2.5 py-0.5 rounded-full` — `emprestado` = amber, `devolvido` = emerald, `atrasado` = red.
- **Tabela paginada**: cabeçalho `bg-slate-50 text-slate-600 text-xs uppercase`, linhas `divide-y divide-slate-200 hover:bg-slate-50`, sempre com os links de paginação do Laravel (`{{ $items->links() }}`) no rodapé.
- **Mensagem flash**: banner no topo da página, verde (`bg-emerald-50 text-emerald-800`) para sucesso e vermelho (`bg-red-50 text-red-800`) para erro.

## Layout e navegação

- Reaproveite o layout autenticado do Breeze (`resources/views/layouts/app.blade.php` + `x-app-layout`) para todas as páginas internas; não crie layouts paralelos.
- Navegação principal (navbar) deve listar: Dashboard, Categorias, Livros, Empréstimos — itens de admin (Categorias/gestão de Livros/Empréstimos) só aparecem para `role === 'admin'`.
- Toda página de listagem segue o mesmo esqueleto: título + botão "Novo" (canto superior direito) + card com a tabela + paginação.
- Todo formulário (create/edit) segue o mesmo esqueleto: card único, campos empilhados verticalmente, erros de validação abaixo de cada campo (`<x-input-error>`), botões "Salvar" (primário) e "Cancelar" (secundário) alinhados à direita.

## Responsividade e UX

- Mobile-first: layout em coluna única abaixo de `sm:`, grid/tabela expandindo a partir de `md:`.
- Tabelas devem ter `overflow-x-auto` no container para não quebrar em telas pequenas.
- Toda ação destrutiva (excluir) exige confirmação (`onsubmit="return confirm('...')"` ou modal) antes de submeter.
- Estados vazios (nenhum resultado) sempre mostram uma mensagem amigável, nunca uma tabela em branco.
