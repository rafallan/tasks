# Tasks

Gerenciador de tarefas para equipes, construído como um monólito moderno com Laravel, Inertia e Vue.

O sistema organiza o trabalho em equipes e listas, permite atribuir tarefas, acompanhar o andamento, usar checklists, registrar comentários e manter um histórico das alterações.

## Funcionalidades

- Autenticação com Laravel Fortify, recuperação de senha, verificação de e-mail, 2FA e passkeys.
- Papéis e permissões com Spatie Laravel Permission: `Admin`, `Gestor` e `User`.
- Equipes com membros e gestores definidos por equipe.
- Listas de tarefas por equipe.
- Criação de tarefas com prioridade, prazo, responsável e validação de pertencimento à equipe.
- Status: pendente, em andamento, bloqueada, concluída e cancelada.
- Checklist, comentários e histórico de eventos da tarefa.
- Dashboard com indicadores de tarefas pendentes, em andamento, bloqueadas, concluídas e atrasadas.
- Interface responsiva com Vue 3, Inertia.js 3 e Tailwind CSS 4.

## Stack

- PHP 8.3 ou superior
- Laravel 13
- MySQL 8 ou superior
- Vue 3 + Inertia.js 3
- Tailwind CSS 4
- Spatie Laravel Permission 8
- Pest 4 e PHPStan

## Requisitos

Antes de iniciar, tenha instalado:

- PHP 8.3+ com extensões `pdo_mysql`, `mbstring`, `xml`, `curl` e `zip`;
- Composer 2;
- Node.js 20+ e npm;
- MySQL 8+.

Para executar os testes configurados com SQLite em memória, habilite também a extensão `pdo_sqlite`.

## Instalação

Clone o repositório e instale as dependências:

```bash
git clone <URL_DO_SEU_REPOSITORIO> tasks
cd tasks
composer install
npm install
```

Crie e configure o arquivo de ambiente:

```bash
cp .env.example .env
php artisan key:generate
```

No `.env`, informe a conexão MySQL. Exemplo local:

```dotenv
APP_URL=http://localhost:8001

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tasks
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

Crie o banco `tasks` no MySQL e execute as migrations e os seeders:

```bash
php artisan migrate --seed
```

O seeder cria os papéis, as permissões e um usuário administrador de desenvolvimento:

| E-mail | Senha | Papel |
| --- | --- | --- |
| `test@example.com` | `password` | `Admin` |

Altere ou remova esse usuário antes de qualquer ambiente compartilhado ou de produção.

## Executando localmente

Em um terminal, inicie o Laravel:

```bash
php artisan serve --port=8001
```

Em outro terminal, inicie o Vite:

```bash
npm run dev
```

Abra `http://localhost:8001` no navegador.

Como alternativa, o comando abaixo inicia os processos de desenvolvimento registrados pelo projeto:

```bash
composer run dev
```

## Perfis de acesso

| Perfil | Escopo |
| --- | --- |
| `Admin` | Acesso total ao sistema por meio de um `Gate::before`. |
| `Gestor` | Gerencia apenas as equipes em que está marcado como gestor. Pode criar listas e tarefas, atribuir membros da equipe e acompanhar o trabalho. |
| `User` | Visualiza e atualiza apenas tarefas atribuídas a si, incluindo status, checklist e comentários. |

Além das permissões, as Policies verificam o vínculo do usuário com a equipe da tarefa. Isso impede, por exemplo, que um gestor atribua tarefas a alguém de outra equipe.

## Regras de negócio principais

- O responsável precisa ser membro ativo da equipe da lista.
- Usuários inativos não autenticam e não podem receber tarefas.
- Uma lista arquivada não recebe novas tarefas.
- O prazo não pode ser anterior à data de início.
- Concluir uma tarefa preenche `concluida_em`; reabri-la limpa esse campo.
- Mudanças relevantes, como criação, status, responsável, prazo e prioridade, são registradas no histórico.

## Estrutura de dados

As entidades principais são:

```text
users
equipes
equipe_user
listas_tarefas
tarefas
itens_checklist_tarefa
comentarios_tarefas
historicos_tarefas
roles / permissions / model_has_roles / model_has_permissions / role_has_permissions
```

## Qualidade e testes

Formate os arquivos PHP:

```bash
vendor/bin/pint --dirty --format agent
```

Execute a análise estática:

```bash
vendor/bin/phpstan analyse
```

Execute os testes:

```bash
php artisan test --compact
```

O `phpunit.xml` utiliza SQLite em memória para os testes. Caso apareça `could not find driver`, instale ou habilite `pdo_sqlite` na sua instalação do PHP.

Verificações do frontend:

```bash
npm run lint:check
npm run types:check
npm run build
```

## Licença

Este projeto está sob a licença [MIT](https://opensource.org/licenses/MIT).
