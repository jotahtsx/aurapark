# AuraPark --- Painel Administrativo

Projeto desenvolvido em Laravel + TailwindCSS, utilizando PostgreSQL em
container Docker.

## 🚀 Requisitos

Antes de começar, certifique-se de ter instalado:

-   **PHP 8.2+**
-   **Composer**
-   **Node.js + NPM**
-   **Docker** e **Docker Compose**
-   **Git**

## 📦 Instalação

### 1. Clonar o repositório

    git clone https://github.com/jotahtsx/aurapark.git
    cd aurapark

### 2. Instalar dependências do PHP

    composer install

### 3. Instalar dependências do Node

    npm install

### 4. Criar o arquivo `.env`

    cp .env.example .env

### 5. Gerar a chave da aplicação

    php artisan key:generate

## 🐘 Banco de Dados com Docker

### 1. Subir o container PostgreSQL

    docker compose up -d

### 2. Rodar as migrações

    php artisan migrate

Para popular com seeds:

    php artisan db:seed

## ▶️ Executando o projeto

### 1. Iniciar o servidor Laravel

    php artisan serve

### 2. Iniciar o Vite (Tailwind)

    npm run dev

## 📂 Estrutura Principal

    aurapark/
    │
    ├── app/
    ├── resources/
    │   ├── views/
    │   ├── css/
    │   ├── js/
    │
    ├── routes/
    │   └── web.php
    │
    └── docker-compose.yml

## 🧪 Testes

    php artisan test

## 📜 Licença

Projeto privado sem licença pública configurada.

## 👨‍💻 Autor

Desenvolvido por **jotahdev**.