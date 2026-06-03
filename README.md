# FEMAQUA (Ferramentas Maravilhosas Que Adoro) API

Uma API para gerenciamento de ferramentas (Ferramentas Maravilhosas Que Adoro). Este projeto permite cadastrar, listar, filtrar e remover ferramentas.

## Desafio
Sua tarefa é construir uma API e banco de dados para a aplicação FEMAQUA (Ferramentas Maravilhosas Que Adoro). A aplicação é um simples repositório para gerenciar ferramentas com seus respectivos nomes, links, descrições e tags.

A aplicação deve ser construída utilizando Node (JS) ou Laravel (PHP) e MySql ou PostgreSql como solução de banco de dados. Fique livre para utilizar frameworks e ferramentas adicionais de sua preferência.

A API deverá ser documentada utilizando o Swagger.

## Tecnologias Utilizadas

- **PHP 8.5**
- **Laravel 13**
- **Laravel Sail** (Docker)
- **Spatie Laravel Query Builder** (Filtros e Ordenação)
- **Laravel Octane** (Performance)
- **L5-Swagger** (Documentação OpenAPI 3.0)
- **MySQL**
- **Pest** (Testes)
- **Filament** (Painel administrativo)

## Pré-requisitos

Antes de começar, você precisará ter instalado em sua máquina:
- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

---

## Instalação e Configuração

Siga os passos abaixo para configurar o ambiente de desenvolvimento:

1. **Clonar o repositório**
   ```bash
   git clone git@github.com:Tatyorn/FEMAQUA.git
   ```

2. **Instalar as dependências do Composer**
   (Caso não tenha o PHP instalado localmente, você pode usar uma imagem Docker temporária)
   ```bash
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v "$(pwd):/var/www/html" \
       -w "/var/www/html" \
       laravelsail/php83-composer:latest \
       composer install --ignore-platform-reqs
   ```

3. **Configurar o arquivo .env**
   ```bash
   cp .env.example .env
   ```

4. **Subir os containers com Laravel Sail**
   ```bash
   ./vendor/bin/sail up -d
   ```

5. **Gerar a chave da aplicação**
   ```bash
   ./vendor/bin/sail artisan key:generate
   ```

6. **Executar as Migrations**
   ```bash
   ./vendor/bin/sail artisan migrate --seed
   ```

---

## Como Executar

Após seguir os passos de instalação, a aplicação estará disponível em: [http://localhost:3000](http://localhost:3000).

### Comandos úteis do Sail:

- **Parar os containers:** `./vendor/bin/sail stop`
- **Iniciar os containers:** `./vendor/bin/sail up -d`
- **Executar testes:** `./vendor/bin/sail test`
- **Artisan:** `./vendor/bin/sail artisan <comando>`

---

## Documentação da API (Swagger)

A API utiliza o Swagger para documentar os endpoints.

1. **Gerar a documentação:**
   Sempre que houver alterações nas anotações da API, execute:
   ```bash
   ./vendor/bin/sail artisan l5-swagger:generate
   ```

2. **Acessar a interface UI:**
   A documentação interativa pode ser acessada em: [http://localhost:3000/api/documentation](http://localhost:3000/api/documentation)

---

## Endpoints Principais

- `GET  /tools`: Lista todas as ferramentas (suporta busca por tag e ordenação).
- `POST  /tools`: Cadastra uma nova ferramenta.
- `DELETE /tools/{id}`: Remove uma ferramenta.

## Endpoints Autenticados
- `GET  auth/tools`: Lista todas as ferramentas.
- `POST  auth/tools`: Cadastra uma nova ferramenta.
- `DELETE auth/tools/{id}`: Remove uma ferramenta.

---

## Acesso às rotas autenticadas

- Após instalar os pacotes e executar as migrations e seeders
    ```bash
   ./vendor/bin/sail artisan migrate --seed
   ```
- Você pode acessar as rotas autenticadas: [http://localhost:3000/auth/tools](http://localhost:3000/auth/tools)
com a chave gerada na rota: `POST /login`
   ```bash
  email: admin@email.com
  senha: biztrip
  ```
- você também pode acessar o painel de admin em [http://localhost:3000/admin/login](http://localhost:3000/admin/login)
