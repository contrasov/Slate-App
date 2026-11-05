# Slate App

![Vite](https://img.shields.io/badge/vite-%23646CFF.svg?style=for-the-badge&logo=vite&logoColor=white)
![Vue.js](https://img.shields.io/badge/vuejs-%2335495e.svg?style=for-the-badge&logo=vuedotjs&logoColor=%234FC08D)
![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![Postgres](https://img.shields.io/badge/postgres-%23316192.svg?style=for-the-badge&logo=postgresql&logoColor=white)

Sistema de fichas de anamnese e agendamento de consultas, feito com Laravel, Vue.js, Inertia e PostgreSQL.

## Requisitos

- Docker Engine
- Docker Compose v2 (`docker compose`)
- Git

PHP, Composer, Node.js e PostgreSQL são instalados ou executados nos containers; não é necessário instalá-los na máquina host.

## Executar em desenvolvimento

Na raiz do projeto, crie o arquivo de configuração local:

```bash
cp .env.example .env
```

Edite `.env` e defina `POSTGRES_PASSWORD` com uma senha longa e exclusiva. Depois gere a chave da aplicação:

```bash
docker compose run --rm app sh -lc "composer install && php artisan key:generate"
```

Esse comando inicia o PostgreSQL como dependência, se necessário. O serviço da aplicação é executado apenas para gerar a chave.

Construa as imagens e inicie os serviços:

```bash
docker compose up --build
```

Na primeira inicialização, o container da aplicação instala as dependências, executa as migrações e inicia Laravel, fila, logs e Vite. Acesse:

- Aplicação: <http://localhost:8000>
- Vite: <http://localhost:5173> (servidor de desenvolvimento)

As portas são publicadas somente em `127.0.0.1`. O PostgreSQL também fica disponível localmente em `127.0.0.1:5432`.

Para iniciar em segundo plano:

```bash
docker compose up --build -d
docker compose logs -f app
```

Para parar os serviços:

```bash
docker compose down
```

Os dados do PostgreSQL ficam em um volume Docker e permanecem após `docker compose down`. Para apagar também o banco e todos os dados salvos, use `docker compose down --volumes`.

## Comandos úteis

Executar comandos Artisan:

```bash
docker compose exec app php artisan about
docker compose exec app php artisan migrate:status
```

Instalar dependências após alterar os arquivos de lock:

```bash
docker compose exec app composer install
```

Gerar os assets para produção:

```bash
docker compose exec app npm run build
```

## Executar sem Docker

Também é possível rodar localmente, desde que a máquina tenha PHP 8.2 ou superior com as extensões exigidas pelo Laravel e PostgreSQL, Composer, Node.js e npm instalados. Primeiro copie o exemplo e configure a conexão do `.env` para o PostgreSQL local (`DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` e `DB_PASSWORD`):

```bash
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan migrate
```

Depois inicie os serviços de desenvolvimento:

```bash
composer run dev
```

O script inicia o servidor Laravel, o worker da fila, o visualizador de logs e o Vite. A aplicação fica em <http://localhost:8000>.
