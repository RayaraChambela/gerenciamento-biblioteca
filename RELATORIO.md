# Relatório — Sistema de Gestão de Bibliotecas

## Contexto e Planejamento

**Tema:** Sistema de Gestão de Bibliotecas — controle de acervo (livros e categorias) e do ciclo de empréstimo/devolução de exemplares a leitores cadastrados, com dois perfis de acesso (bibliotecário/admin e leitor).

A aplicação resolve um problema real de bibliotecas pequenas que hoje controlam empréstimos em planilhas/cadernos: não é simples saber, de forma confiável, quantos exemplares de um título estão disponíveis, quem está com um livro emprestado e quais empréstimos estão atrasados. O sistema centraliza esse controle, com baixa e reposição automática da quantidade de exemplares disponíveis a cada empréstimo/devolução.

O [`PLANO_IMPLEMENTACAO.md`](PLANO_IMPLEMENTACAO.md) foi escrito **antes** de qualquer código de aplicação ser gerado pela IA, cobrindo objetivo, público-alvo, escopo, entidades (`users` com perfil, `categorias`, `livros`, `emprestimos`), telas e ordem de implementação, além da parte técnica (tecnologias, riscos e critérios de aceite). O desenvolvimento seguiu exatamente a ordem definida nesse plano.

## Ferramentas de IA

### MCP utilizado: Laravel Boost MCP Server

**Qual MCP foi utilizado:** o próprio `laravel/boost`, instalado com `composer require laravel/boost --dev` e `php artisan boost:install`. O Boost expõe um servidor MCP (`php artisan boost:mcp`, referenciado em `.mcp.json`) com ferramentas específicas para agentes de IA trabalharem em projetos Laravel: `application-info`, `database-schema`, `database-query`, `database-connections`, `browser-logs`, `read-log-entries`, `last-error`, `get-absolute-url` e `search-docs`.

**Para qual finalidade:** o MCP foi usado para que o agente de IA (Claude) tivesse contexto real e atualizado da aplicação durante o desenvolvimento — versão exata do PHP/Laravel/pacotes instalados, schema real do banco (para confirmar que as migrations criadas batiam com o que foi planejado) e, potencialmente, consulta a erros e logs durante a depuração — em vez de assumir versões ou nomes de tabela/coluna incorretos.

**Exemplos concretos de utilização:** o servidor MCP foi acionado diretamente via protocolo (handshake `initialize` + `tools/call`) para validar o ambiente logo após a instalação do Boost. Duas chamadas reais e suas respostas:

- `application-info` retornou, entre outros dados, `"php_version":"8.5"`, `"laravel_version":"13.18.0"`, `"database_engine":"sqlite"` e a lista completa de pacotes instalados (Breeze v2.4.2, Boost v2.4.11, Pint v1.29.3 etc.) — usado para garantir que o código gerado (sintaxe de atributos `#[Fillable]`/`#[Hidden]` do Model, por exemplo) fosse compatível com a versão exata do Laravel 13 instalada, e não com uma versão genérica.
- `database-schema` (modo `summary`) retornou a lista de tabelas e tipos de coluna do banco SQLite logo após as primeiras migrations (`users`, `cache`, `jobs`, `sessions` etc.) — usado para confirmar, antes de gerar os Models e Controllers, que as tabelas `categorias`, `livros` e `emprestimos` haviam sido criadas com os tipos de coluna esperados.

Também foi usado o `boost:list-skills` (comando artisan do próprio pacote) para confirmar que as 4 Skills autorais foram corretamente descobertas em `.ai/skills` antes de sincronizá-las para o agente com `php artisan boost:install --skills -n`.

### Skills desenvolvidas

Todas em `.ai/skills/<nome>/SKILL.md` (pasta indicada pelo Boost para skills customizadas; sincronizadas para `.claude/skills` via `boost:install`/`boost:update`):

1. **`identidade-visual`** (obrigatória) — paleta de cores (azul-noite + dourado), tipografia, componentes padronizados (botões, cards, badges de status, tabela paginada) e regras de responsividade/UX para manter as telas visualmente consistentes.
2. **`crud-padrao`** (obrigatória) — estrutura obrigatória de rotas resourceful, controllers, Form Requests, paginação e mensagens de feedback para qualquer CRUD do projeto.
3. **`seguranca`** (opcional) — autorização por perfil (admin x leitor), proteção de mass assignment, CSRF e tratamento de senha.
4. **`testes`** (opcional) — convenções de testes automatizados com PHPUnit (Feature por CRUD + Unit para a regra de negócio de empréstimo), mirando o bônus de testes.

## Desenvolvimento

**Funcionalidades implementadas:** autenticação (Breeze), CRUD completo de Categorias, CRUD completo de Livros (com busca e filtro por categoria), CRUD de Empréstimos (registrar/devolver, com a regra de negócio de baixa/reposição de `quantidade_disponivel`), controle de acesso por perfil via middleware `role:admin`, e dashboard com métricas diferentes para admin e leitor.

**Decisões de projeto:**
- SQLite foi escolhido para eliminar a necessidade de configurar um servidor de banco à parte na correção — o README documenta como trocar para MySQL/Postgres se necessário.
- A data de empréstimo e a previsão de devolução (+14 dias) são calculadas automaticamente no `store()` do `EmprestimoController`, em vez de pedir essas datas no formulário, simplificando a UX e evitando datas inconsistentes.
- O status "atrasado" não é gravado por um job agendado: `Emprestimo::statusEfetivo()` calcula dinamicamente se um empréstimo em aberto já passou da data prevista, evitando a necessidade de um cron/scheduler para manter o status em dia.
- A regra de disponibilidade de exemplares foi centralizada em métodos do Model `Livro` (`reservarExemplar()`/`liberarExemplar()`), não nos Controllers, para ficar testável isoladamente (ver Skill de CRUD).

**Dificuldades encontradas:**
- O `Route::resource()` do Laravel registra a rota `show` (`/livros/{livro}`) antes de rotas customizadas subsequentes; como a rota `create` (`/livros/create`) precisa ser registrada *antes* da wildcard `{livro}` para não ser interpretada como um ID, as rotas de Livros e Empréstimos foram declaradas explicitamente (fora do `Route::resource()`) na ordem correta em vez de usar o resource completo.
- O `boost:install` é interativo por padrão e não detectou automaticamente o Claude Code como agente em um projeto recém-criado (sem pasta `.claude` ainda). Foi necessário criar a pasta `.claude` manualmente antes de rodar `php artisan boost:install --mcp --guidelines --skills -n` para que a detecção de agente funcionasse em modo não-interativo.
- A skill de testes foi escrita inicialmente citando Pest, mas o `composer create-project laravel/laravel` mais recente instala PHPUnit puro (classes estendendo `Tests\TestCase`), não Pest — a skill foi corrigida para refletir a convenção realmente usada no projeto antes de escrever os testes.

## Conclusão

**Limitações da aplicação:** não há reserva de livros, notificações por e-mail, cobrança de multas nem renovação automática de empréstimo — escopo deliberadamente contido para entregar os 3 CRUDs completos e testados dentro do prazo, em vez de mais funcionalidades incompletas. A regra de decremento/incremento de `quantidade_disponivel` não trata explicitamente condições de corrida sob alta concorrência (fora do escopo de um trabalho didático).

**Utilização da IA durante o desenvolvimento:** a IA (Claude, via Claude Code) gerou a primeira versão de todas as migrations, models, controllers, Form Requests, seeders, views Blade e testes, seguindo o Plano de Implementação e as Skills escritas antes do código. Todo o código gerado foi revisado, testado manualmente na aplicação rodando (login com os 3 perfis, CRUDs completos, fluxo de empréstimo/devolução) e via suíte automatizada (`php artisan test`, 50 testes/115 assertions passando) antes de ser considerado pronto.

**Conclusão geral:** o trabalho cumpriu os requisitos mínimos (autenticação funcional, banco com migrations/seeders, 3 CRUDs completos, MCP documentado com uso real, 4 Skills) e foi além do mínimo obrigatório de 1 CRUD, incluindo testes automatizados Feature/Unit para o bônus.
