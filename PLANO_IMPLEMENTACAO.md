# Plano de Implementação — Sistema de Gestão de Bibliotecas

## Contexto

### Objetivo da aplicação
Fornecer a uma biblioteca (escolar, comunitária ou institucional) uma ferramenta web simples para controlar o acervo de livros, as categorias do catálogo e o ciclo de empréstimo/devolução de exemplares aos leitores cadastrados.

### Problema que resolve
Bibliotecas pequenas frequentemente controlam empréstimos em planilhas ou cadernos, o que dificulta saber rapidamente quantos exemplares de um título estão disponíveis, quem está com um livro emprestado e quais empréstimos estão atrasados. A aplicação centraliza esse controle em um único sistema com histórico e regras automáticas de disponibilidade.

### Público-alvo
- **Bibliotecário/Administrador**: gerencia o acervo (livros e categorias) e registra/baixa empréstimos.
- **Leitor**: usuário cadastrado que consulta o catálogo e acompanha seus próprios empréstimos.

### Escopo

**Funcionalidades**
- Autenticação (login/registro/logout) via Laravel Breeze.
- CRUD de Categorias (nome do gênero/assunto do livro).
- CRUD de Livros (título, autor, categoria, ISBN, ano, quantidade total e disponível).
- CRUD de Empréstimos (registrar empréstimo de um livro a um leitor, marcar devolução), com baixa/reposição automática da quantidade disponível do livro.
- Dashboard com métricas gerais (total de livros, empréstimos ativos, empréstimos atrasados).
- Controle de acesso por perfil (admin x leitor).

**Fora do escopo** (por limitação de tempo/tamanho do trabalho): reservas de livros, notificações por e-mail, multas financeiras, renovação automática de empréstimo, API pública.

**Entidades do banco**
| Entidade | Campos principais |
|---|---|
| `users` | name, email, password, **role** (`admin`\|`leitor`) |
| `categorias` | nome |
| `livros` | titulo, autor, categoria_id (FK), isbn, ano_publicacao, quantidade_total, quantidade_disponivel |
| `emprestimos` | livro_id (FK), user_id (FK, leitor), data_emprestimo, data_prevista_devolucao, data_devolucao (nullable), status (`emprestado`\|`devolvido`\|`atrasado`) |

Regra de negócio central: ao registrar um empréstimo, `quantidade_disponivel` do livro é decrementada (bloqueado se chegar a 0); ao registrar a devolução, é incrementada de volta e `data_devolucao`/`status` são atualizados.

**Telas**
1. Login / Registro (Breeze)
2. Dashboard (métricas gerais pós-login)
3. Categorias — listagem paginada, criar, editar, excluir
4. Livros — listagem paginada com busca por título/autor e filtro por categoria, criar, editar, ver detalhes, excluir
5. Empréstimos — listagem paginada com filtro por status, registrar novo empréstimo, marcar devolução, ver detalhes

**Ordem de implementação**
1. Instalação do Laravel + configuração de banco/`.env`/migrations + autenticação (Breeze)
2. Instalação do Laravel Boost
3. Este Plano de Implementação
4. Skills do Boost (Identidade Visual, CRUD, Segurança, Testes)
5. Migrations, Models e relacionamentos
6. Seeders (categorias, livros, usuários de teste, empréstimos)
7. Controle de acesso por perfil (admin/leitor)
8. CRUD de Categorias
9. CRUD de Livros
10. CRUD de Empréstimos
11. Dashboard
12. Testes automatizados (bônus)
13. README.md e RELATORIO.md

## Técnico

**Tecnologias utilizadas**
- Laravel 13 (PHP 8.5)
- Laravel Boost (MCP + guidelines para o agente de IA)
- Laravel Breeze (stack Blade) para autenticação
- SQLite como banco de dados (zero setup para avaliação; instruções para trocar por MySQL no README)
- Tailwind CSS (padrão do Breeze) para a camada visual
- PHPUnit para testes automatizados

**Riscos**
- O instalador `boost:install` é interativo (escolha de cliente de IA/guidelines); se não houver flag para modo não-interativo, será necessário confirmar manualmente durante a execução.
- SQLite simplifica a correção, mas difere de um ambiente de produção com MySQL/Postgres — documentado como trade-off consciente.
- Prazo do trabalho é curto (mesmo dia); o escopo foi deliberadamente contido a 3 entidades para entregar CRUDs completos e testados, em vez de mais entidades incompletas.
- Regra de decremento/incremento de `quantidade_disponivel` pode sofrer condição de corrida sob acesso concorrente; fora do escopo de testes de carga deste trabalho, mas a lógica é centralizada no Model/Controller para facilitar evolução futura (ex.: lock otimista).

**Critérios de aceite**
- Aplicação sobe do zero com `php artisan migrate:fresh --seed` sem erros.
- Login funcional com os usuários de teste seedados (perfis admin e leitor).
- Os 3 CRUDs (Categorias, Livros, Empréstimos) completos, com paginação, validação via Form Requests e mensagens de feedback ao usuário.
- Laravel Boost instalado e com pelo menos um MCP utilizado e documentado no `RELATORIO.md` com exemplos concretos.
- 4 Skills presentes em `.ai/skills` e descobertas pelo Boost.
- `README.md` e `RELATORIO.md` completos conforme exigido pelo enunciado.
- Estrutura do repositório conforme especificado (README.md, RELATORIO.md, app/, bootstrap/, config/, database/, resources/, routes/, tests/ na raiz).
