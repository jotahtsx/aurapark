# AuraPark --- Painel Administrativo

Painel administrativo desenvolvido com **Laravel**, **TailwindCSS** e
**PostgreSQL**, utilizando **Docker** para padronização do ambiente de
banco de dados.

O projeto foi estruturado para facilitar o onboarding de novos
desenvolvedores, mantendo boas práticas de configuração, versionamento e
isolamento de ambiente.

------------------------------------------------------------------------

## 🧱 Stack Utilizada

-   **Laravel 12**
-   **PHP 8.2+**
-   **PostgreSQL 16 (Docker)**
-   **TailwindCSS**
-   **Vite**
-   **Docker & Docker Compose**
-   **Node.js + NPM**
-   **Git**

------------------------------------------------------------------------

## 🚀 Requisitos

Antes de iniciar, certifique-se de ter instalado:

-   PHP **8.2 ou superior**
-   Composer
-   Node.js (18+ recomendado)
-   Docker e Docker Compose
-   Git

------------------------------------------------------------------------

## 📦 Instalação

### 1. Clonar o repositório

``` bash
git clone https://github.com/jotahtsx/aurapark.git
cd aurapark
```

------------------------------------------------------------------------

### 2. Instalar dependências do PHP

``` bash
composer install
```

------------------------------------------------------------------------

### 3. Instalar dependências do frontend

``` bash
npm install
```

------------------------------------------------------------------------

### 4. Criar o arquivo de ambiente

``` bash
cp .env.example .env
```

> ⚠️ **Importante:**\
> Edite o arquivo `.env` conforme necessário, principalmente as
> variáveis de banco de dados.

------------------------------------------------------------------------

### 5. Gerar a chave da aplicação

``` bash
php artisan key:generate
```

------------------------------------------------------------------------

## 🐘 Banco de Dados (PostgreSQL com Docker)

### 1. Subir o container do banco

``` bash
docker compose up -d
```

------------------------------------------------------------------------

### 2. Rodar as migrações

``` bash
php artisan migrate
```

------------------------------------------------------------------------

### 3. Popular o banco com dados fake (opcional)

``` bash
php artisan db:seed
```

------------------------------------------------------------------------

## ▶️ Executando o Projeto

### Backend (Laravel)

``` bash
php artisan serve
```

A aplicação ficará disponível em:\
👉 `http://localhost:8000`

------------------------------------------------------------------------

### Frontend (Vite + Tailwind)

``` bash
npm run dev
```

------------------------------------------------------------------------

## 📂 Estrutura do Projeto

``` text
aurapark/
├── app/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── resources/
│   ├── views/
│   ├── css/
│   └── js/
├── routes/
│   └── web.php
├── docker-compose.yml
└── README.md
```

------------------------------------------------------------------------

## 🧪 Testes

``` bash
php artisan test
```

------------------------------------------------------------------------

## 🔐 Controle de Versão (.env)

O arquivo `.env` **não deve ser versionado**.

Sempre que clonar o projeto: 1. Copie o `.env.example` 2. Gere a chave
3. Ajuste as variáveis conforme o ambiente

------------------------------------------------------------------------

## 📜 Licença

Projeto privado.\
Nenhuma licença pública definida no momento.

------------------------------------------------------------------------

## 👨‍💻 Autor

Desenvolvido por **jotahdev**
