# Travel Orders API

Uma API robusta para gerenciamento de ordens de viagem, desenvolvida com Laravel 12 e autenticação JWT. Sistema que permite usuários autenticados criarem, listarem e visualizarem suas próprias ordens de viagem de forma segura.

## 📋 Resumo do Projeto

API backend completa para gerenciamento de ordens de viagem com foco na segurança e isolamento de dados por usuário. Desenvolvida seguindo as melhores práticas do Laravel com arquitetura limpa e escalável.

### Principais Funcionalidades

- **Autenticação JWT**: Sistema seguro baseado em JSON Web Tokens
- **CRUD de Ordens de Viagem**: Gerenciamento completo de ordens por usuário
- **Sistema de Destinos**: Gerenciamento de cidades e estados
- **API RESTful**: Endpoints bem estruturados
- **Testes Automatizados**: Cobertura com PHPUnit
- **Containerização**: Docker para desenvolvimento
- **Cadastro de Usuario**: Cadastrar usuários para testes

### Tecnologias

- **Backend**: Laravel 12 (PHP 8.2+)
- **Autenticação**: JWT (tymon/jwt-auth)
- **Banco de Dados**: MySQL 8.0
- **Containerização**: Docker & Docker Compose
- **Testes**: PHPUnit
- **Email**: Mailpit (desenvolvimento)

## 🚀 Como Executar Localmente

### Pré-requisitos

- **Docker** (versão 20.10+)
- **Docker Compose** (versão 2.0+)
- **Git**
- **Composer**

### Método 1: Laravel Sail (Linux/Mac)

#### 1. Clonar e Configurar

```bash
git clone https://github.com/CaioCLDias/travel-orders-api.git
cd travel-orders-api
cp .env.example .env
```

#### 2. Instalar Dependências

```bash
    composer install 
```

#### 3. Iniciar Aplicação

```bash
# Subir containers
./vendor/bin/sail up -d

# Configurar aplicação
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan jwt:secret
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed

# Iniciar filas de email (em terminal separado)
./vendor/bin/sail artisan queue:work
```

#### 4. Acessar Aplicação

- **API**: http://localhost
- **Mailpit**: http://localhost:8025

### Método 2: Dockerfile.dev + Docker Compose

#### 1. Clonar e Configurar

```bash
git clone https://github.com/CaioCLDias/travel-orders-api.git
cd travel-orders-api
cp .env.example .env
```


#### 2. Executar com Docker Compose Dev

```bash
# Subir containers com configuração de desenvolvimento
docker-compose -f docker-compose.dev.yml up -d

# Aguardar containers iniciarem (30 segundos)
sleep 30

# Configurar aplicação
docker-compose -f docker-compose.dev.yml exec laravel php artisan key:generate
docker-compose -f docker-compose.dev.yml exec laravel php artisan jwt:secret
docker-compose -f docker-compose.dev.yml exec laravel php artisan migrate
```

#### 3. Acessar Aplicação

- **API**: http://localhost:8080


## 🧪 Execução de Testes

### Executar Testes

**Com Laravel Sail**
```bash
./vendor/bin/sail artisan test
./vendor/bin/sail artisan test --coverage
```

**Com Docker**
```bash
docker-compose -f docker-compose.dev.yml exec laravel php artisan test
docker-compose -f docker-compose.dev.yml exec laravel php artisan test --coverage
```

## 🌐 Endpoints da API

### Autenticação
```
POST   /api/login              # Login
GET    /api/me                 # Dados do usuário
POST   /api/logout             # Logout
POST   /api/refresh            # Refresh token
```

### Ordens de Viagem
```
GET    /api/travel-orders       # Listar ordens do usuário
POST   /api/travel-orders       # Criar nova ordem
GET    /api/travel-orders/{id}  # Visualizar ordem específica
```

### Destinos
```
GET    /api/destinations/states                    # Listar estados
GET    /api/destinations/cities/{stateIbgeCode}    # Listar cidades por estado
```

### Admin (Requer is_admin)
```
GET    /api/admin/travel-orders     # Listar todas as ordens
PUT    /api/admin/travel-orders/{id} # Atualizar status da ordem
POST   /api/admin/users            # Criar usuário
```
---

## 🔒 Segurança e Boas Práticas

### Implementadas
- ✅ Autenticação JWT com expiração
- ✅ Isolamento de dados por usuário
- ✅ Validação rigorosa de entrada
- ✅ Middleware de autorização
- ✅ Form Requests para validação
- ✅ API Resources para controle de exposição

### Arquitetura
- **MVC + Services**: Separação clara de responsabilidades
- **Repository Pattern**: Abstração de acesso a dados
- **Dependency Injection**: Inversão de controle
- **Exception Handling**: Tratamento centralizado de erros

## 👥 Usuários de Teste

- **Admin**
  - Email: `admin@onfly.com.br`
  - Senha: `password`

- **Comum**
  - Email: `common@onfly.com.br`
  - Senha: `password`

---

## 👨‍💻 Autor

**Caio Dias** – [@CaioCLDias](https://github.com/CaioCLDias)

---

## 📄 Licença

Este projeto está sob a licença MIT.

---

*Desenvolvido com ❤️ usando Laravel e as melhores práticas de desenvolvimento.*

