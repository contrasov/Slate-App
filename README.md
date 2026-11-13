# Slate App

![Vite](https://img.shields.io/badge/vite-%23646CFF.svg?style=for-the-badge&logo=vite&logoColor=white)
![Vue.js](https://img.shields.io/badge/vuejs-%2335495e.svg?style=for-the-badge&logo=vuedotjs&logoColor=%234FC08D)
![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![Postgres](https://img.shields.io/badge/postgres-%23316192.svg?style=for-the-badge&logo=postgresql&logoColor=white)

Sistema de fichas de anamnese e agendamento de consultas, feito com Laravel, Vue.js, Inertia e PostgreSQL.

## Requisito

- Docker Engine com Docker Compose v2 (`docker compose`)

## Rodar o projeto

Na raiz do repositório, execute:

```bash
docker compose up --build
```

Na primeira execução, o Compose constrói a imagem, inicia o PostgreSQL e prepara a aplicação automaticamente: cria `.env` se necessário, instala as dependências, gera a chave do Laravel, aplica as migrações e inicia o servidor e o Vite.

A aplicação roda na porta 8000: abra <http://localhost:8000> para acessar o login. O Vite fica na porta 5173 (<http://localhost:5173>). As portas são acessíveis apenas pela própria máquina.

Para rodar em segundo plano:

```bash
docker compose up --build -d
docker compose logs -f app
```

Para parar:

```bash
docker compose down
```

Os dados do PostgreSQL permanecem salvos após parar os containers. Para apagar os containers e também os dados do banco:

```bash
docker compose down --volumes
```

O Compose usa uma senha de desenvolvimento local para o PostgreSQL. Não reutilize essa configuração fora da máquina local.
