# 🚀 SINAPSE TEST - API DE GERENCIAMENTO DE USUÁRIOS

API RESTful para cadastro, edição, listagem e remoção lógica de usuários, desenvolvida com **Laravel 13**, **Docker**, **PostgreSQL** e **TDD**.  

Projeto criado como parte de um desafio técnico.

---

## 📋 Requisitos

- Docker (versão 20.10+)
- Docker Compose
- Git

---

## ⚙️ Como rodar o projeto

### 1. Clone o repositório

```bash
git clone https://github.com/ketymonique/sinapse_test.git
cd sinapse_test
```

### 2. Configure o `.env`

Copie o `.env.example` e configure:

```env
DB_HOST=db
DB_DATABASE=db_sinapse
DB_USERNAME=root
DB_PASSWORD=root
```

> A `APP_KEY` será gerada automaticamente.

---

### 3. Suba os containers

```bash
docker-compose up -d
```

**Serviços iniciados:**

- `sinapse_test_app` (PHP-FPM)
- `sinapse_test_nginx` (http://localhost:8080)
- `sinapse_test_db` (PostgreSQL - porta 5432)
- `sinapse_test_db_test` (PostgreSQL - porta 5433)
- `sinapse_test_redis` (Redis - porta 6379)

---

### 4. Gerar chave da aplicação

```bash
docker exec -it sinapse_test_app php artisan key:generate
```

### 5. Rodar migrations

```bash
docker exec -it sinapse_test_app php artisan migrate
```

### 6. Testar API

Acesse:  
👉 http://localhost:8080/api/users

---

## 🧪 Testes automatizados

### Rodar todos os testes

```bash
docker exec -it sinapse_test_app php artisan test
```

### Rodar apenas Feature tests

```bash
docker exec -it sinapse_test_app php artisan test --testsuite=Feature
```

### Rodar teste específico

```bash
docker exec -it sinapse_test_app php artisan test --filter=StrongPasswordTest
```

---

## ✅ Resultado esperado

```
   PASS  Tests\Unit\StrongPasswordTest
  ✓ Validate                                                                                                                                                                                         0.02s  
  ✓ short       
  ✓ no uppercase
  ✓ no lowercase
  ✓ no number   
  ✓ no special char
  ✓ multiple fails

   PASS  Tests\Feature\UserTest
  ✓ create user                                                                                                                                                                                      8.23s  
  ✓ index returns 10 users per page                                                                                                                                                                  0.67s  
  ✓ index filters by id                                                                                                                                                                              0.21s  
  ✓ index filters by name                                                                                                                                                                            0.18s  
  ✓ show user                                                                                                                                                                                        0.16s  
  ✓ user not found                                                                                                                                                                                   0.25s  
  ✓ update user                                                                                                                                                                                      0.19s  
  ✓ update user not found                                                                                                                                                                            0.17s  
  ✓ update validates email                                                                                                                                                                           0.24s  
  ✓ soft delete user                                                                                                                                                                                 0.18s  
  ✓ deleted not in index                                                                                                                                                                             0.17s  
  ✓ deleted show 404                                                                                                                                                                                 0.17s  
  ✓ update validation errors                                                                                                                                                                         0.17s  

  Tests:    21 passed (66 assertions)
  Duration: 19.01s
```

---

## 📚 Documentação da API

A especificação completa está em:

```
openapi.yaml
```

Pode ser usada com Swagger, Postman ou Insomnia.

---

## 🔗 Endpoints

| Método | Rota         | Descrição        |
|--------|--------------|------------------|
| GET    | /users       | Lista usuários   |
| POST   | /users       | Cria usuário     |
| GET    | /users/{id}  | Busca usuário    |
| PUT    | /users/{id}  | Atualiza usuário |
| DELETE | /users/{id}  | Soft delete      |

---

## 📁 Estrutura do projeto (Resumida)

```bash
app/
├── Http/
│   ├── Controllers/
│   │   └── UserController.php
│   ├── Requests/
│   │   ├── StoreUserRequest.php
│   │   └── UpdateUserRequest.php
│   └── Resources/
│       └── UserResource.php
├── Models/
│   └── User.php
├── Providers/
│   └── AppServiceProvider.php
└── Rules/
    └── StrongPassword.php

database/
├── migrations/
│   └── ..._create_users_table.php
└── factories/
    └── UserFactory.php

tests/
├── Feature/
│   └── UserTest.php
└── Unit/
    └── StrongPasswordTest.php

routes/
└── api.php

docker-compose.yml
openapi.yaml
README.md
```

---

## 🧠 Observações

- Banco de testes isolado (`RefreshDatabase`)
- Senhas com hash automático
- Datas em ISO 8601
- Validação de senha forte detalhada

---

## 🛠️ Comandos úteis

| Ação             | Comando                                                      |
|------------------|--------------------------------------------------------------|
| Parar containers | `docker-compose down`                                        |
| Rebuild          | `docker-compose up -d --build`                               |
| Bash container   | `docker exec -it sinapse_test_app bash`                      |
| Acessar DB       | `docker exec -it sinapse_test_db psql -U root -d db_sinapse` |
| Limpar cache     | `php artisan config:clear`                                   |

---

## 👩‍💻 Desenvolvido por

**Kethelyn Couto**  
📅 Abril/2026
