# Projeto To Do List

Este projeto é composto por três partes principais:

- **Backend:** Aplicação Laravel.
- **Frontend:** Aplicação Vue.js.
- **Banco de Dados:** MySQL.

O projeto está preparado para ser executado tanto manualmente quanto utilizando Docker para simplificar o processo de build.

---

## Estrutura do Projeto

- `/backend`: Contém o código da API em Laravel.
- `/frontend`: Contém o código do frontend em Vue.js.
- `docker-compose.yml`: Arquivo de configuração para containers Docker.

## Tecnologias Utilizadas

### Frontend
- [NodeJS](https://nodejs.org/pt) - Node JS
- [Vue.js](https://vuejs.org/) - Framework JavaScript progressivo.
- [TailwindCSS](https://tailwindcss.com/) - Framework CSS utilitário.

### Backend
- [PHP](https://www.php.net/) - PHP
- [Laravel](https://laravel.com/) - Framework PHP para desenvolvimento backend.
- [MySQL](https://www.mysql.com/) - Banco de dados relacional para armazenamento de dados.
- [Composer](https://getcomposer.org/) - Gerenciador de dependências para PHP.

---

## Configuração do Ambiente

### 1. Pré-requisitos

- **Docker** ( opcional )

ou

- **PHP** >= 8.3
- **Composer** >= 2.7
- **Node.js** >= 18
- **MySQL** >= 8.0


### 2. Criar os arquivos `.env`
Os arquivos de configuração `.env` são necessários para o funcionamento correto do projeto. Para criar esses arquivos:

1. Vá até a pasta `raiz` e copie o arquivo `.env.example` para `.env` para criar as variavels de construção do docker composer:

   ```bash
   cp .env.example .env
   ```

2. Vá até a pasta `backend` e copie o arquivo `.env.example` para `.env`:

   ```bash
   cp backend/.env.example backend/.env
   ```
3. Vá até a pasta `frontend` e copie o arquivo `.env.example` para `.env`:

   ```bash
   cp frontend/.env.example frontend/.env
   ```

Certifique-se que os dados de acesso ao banco de dados e portas estão corretamente configuradas em todos os arquivos de variaveis de ambiente.



## Fazer build da aplicação com Docker

### 1. Usar docker compose para criar os containers
O compose criará 03 containers para executar a aplicação frontend(`ngix`), backend(`php8.3-apache`) database(`mysql8.1`):

1. Vá até a pasta `raiz` da aplicação e execute o comando:

   ```bash
   docker compose up --build
   ```
Através dos dockerfiles existentes o docker subirá os containers de acordo com as portas configuradas no arquivo `.env` da pasta raiz.
O banco de dados será contruído automáticamente através das migrations definidas no backend com o laravel

Por padrão a aplicação será servida nas portas:

API: `http://localhost:8000/api`

CLIENT: `http://localhost:5173`

Caso realize alterações nas portas padrões lembre-se de confirurar os demais arquivos `.env` para o backend e frontend com as mesmas portas.


## Construindo a aplicação manualmente

### 1. Instalação

#### Backend (pasta backend)

1. Configure seu arquivo .env

    ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=todonow
    DB_USERNAME=todo
    DB_PASSWORD=senha123
    ```
Tenha certeza de ter um banco de dados rodando em seu ambiente e configure o arquivo `.env` com suas credenciais corretamente.

2. Navegue até a pasta `backend`:

   ```bash
   cd backend
   composer install
   php artisan migrate
   php artisan serve
    ``` 

#### Frontend (pasta frontend)

1. Configure seu arquivo .env

    ```bash
    cd frontend
    npm install
    npm run dev
    ```

### 2. Teste da Aplicação

#### Backend (pasta backend)
1. Navegue até a pasta `backend`:

   ```bash
   php artisan test
    ```

#### Frontend (pasta frontend)
2. Navegue até a pasta `frontend`:

   ```bash
   npx vitest
    ```