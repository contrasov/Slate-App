# Slate App 

![Vite](https://img.shields.io/badge/vite-%23646CFF.svg?style=for-the-badge&logo=vite&logoColor=white)
![Vue.js](https://img.shields.io/badge/vuejs-%2335495e.svg?style=for-the-badge&logo=vuedotjs&logoColor=%234FC08D)
![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![Postgres](https://img.shields.io/badge/postgres-%23316192.svg?style=for-the-badge&logo=postgresql&logoColor=white)

## Descrição
Slate é um sistema de fichas de anamnese e agendamentos de consultas, desenvolvido utilizando Laravel para o backend e Vue.js para o frontend. O sistema é projetado para facilitar a gestão de consultas e a coleta de informações dos pacientes.


## Como fazer rodar

Para executar o projeto, é necessário ter o **Docker**, **Docker Compose**, **PHP**, **Compose** instalados na máquina.

Possa ser que eu configure para rodar com docker.

### Build e subida dos containers
Na raiz do projeto, execute:

```bash
sudo docker compose build
sudo docker compose up
````

Esse processo irá:

* Construir as imagens necessárias
* Subir os containers
* Inicializar o banco de dados **PostgreSQL**

### Rodar o projeto Laravel

O projeto pode ser executado utilizando **PHP Legacy** ou **PHP 8.4**, conforme a configuração do ambiente.

Para iniciar a aplicação em modo de desenvolvimento, execute:

```bash
php-legacy compose run dev 
```
ou

```bash
php compose run dev 
```

ou 

```bash
php-legacy /usr/bin/composer run dev 
```

### Observações

* Verifique se as portas definidas no `docker-compose.yml` estão livres.
* O banco de dados PostgreSQL é iniciado automaticamente.
