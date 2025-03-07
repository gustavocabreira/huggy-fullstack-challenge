# Huggy Fullstack Challenge

### Candidato: Gustavo de Sousa Cabreira
### Vaga: Pessoa Desenvolvedora Fullstack (PHP + Vue.js)

## Sobre o projeto

API desenvolvida em Laravel para gerenciar os contatos de um usuário. É possível cadastrar, atualizar, excluir e recuperar informações de contato por meio de uma interface simples e segura.

## Tecnologias Utilizadas

- Docker e Docker Compose
- PHP 8.4
- Laravel Framework
- Swoole (Laravel Octane)
- MySQL
- [Nginx](https://www.nginx.com/) (Servidor de aplicações)
- [Redis](https://redis.io/) (Cache e Filas)
- [Pest](https://pestphp.com/) (Testes)
- [Supervisor](https://github.com/ochinchina/supervisord) (Gerenciador de processos)
- [Mailhog](https://github.com/mailhog/MailHog) (SMTP de teste) e [Jim](https://github.com/mailhog/MailHog/blob/master/docs/JIM.md) (Simulação de erros)
- [Meilisearch](https://www.meilisearch.com/) (Full-text Search)
- [Scramble](https://scramble.dedoc.co/) (Documentação)
- [NGROK](https://ngrok.com/) (Exposição de aplicações)
- [Huggy](https://www.huggy.io/pt-br) (Autenticação por OAuth2)
- [Twilio](https://www.twilio.com/) (VOIP)

## Instalação

### Requisitos

- [Docker](https://docs.docker.com/engine/install/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- Deverá ter as portas `80`, `1025`, `3306`, `4040`, `6379`, `7700`, `8025` e `9051` abertas e desocupadas.

Para fins de simuação mais próxima possível de um ambiente de produção, o servidor nginx está configurado para uso de subdomínios localhost:

- http://spa.localhost.com
- http://api.localhost.com

Então é necessário adicionar esses domínios ao /etc/hosts da maquina host:

```bash
echo -e "127.0.0.1 api.localhost.com\n127.0.0.1 web.localhost.com" | sudo tee -a /etc/hosts
```

Caso você esteja utlizando o Windows, você pode adicionar o arquivo hosts.txt no seu diretório de usuário.

### Executando o projeto

1. Clone o repositório

```bash
git clone https://github.com/gustavocabreira/huggy-fullstack-challenge.git
```

2. Entre na pasta do projeto

```bash
cd huggy-fullstack-challenge
```

3. Entre na pasta docker/local

```bash
cd docker/local
```

4. Antes da instalação do projeto, precisaremos inserir a chave do NGROK no arquivo `/docker/local/.env.example` na chave `NGROK_KEY`.

5. Execute o comando para instalar o projeto

```bash
sh install.sh --app-name=huggy-fullstack-challenge
```

6. Após a instalação do projeto, precisaremos acessar o NGROK para obter o endereço do nosso aplicativo.
- Acesse o NGROK em http://localhost:4040 e copie o endereço do nosso aplicativo.
- Cole o endereço no arquivo `.env`, na raiz do projeto, na chave `WEBHOOK_URL`. Deverá ficar assim: `WEBHOOK_URL=https://<ngrok_url>/api/webhook`
- Também precisamos alterar o endereço do webhook no Twilio para que possammos fazer a conexão com o VOIP.
- Acesse o console do Twilio em https://console.twilio.com/us1/develop/phone-numbers/manage/twiml-apps?frameUrl=%2Fconsole%2Fvoice%2Ftwiml%2Fapps%3Fx-target-region%3Dus1, acesse seu APP e configure o webhook.
- A URL do webhook do Twilio deve ser o endereço do nosso aplicativo, como: `https://<ngrok_url>/api/twilio-webhook`

7. Acesse a aplicação em http://api.localhost.com

8. Você pode acessar a documentação do projeto em http://api.localhost.com/docs/api

9. Se você quiser, você pode exportar a documentação da API para um arquivo JSON e utilizá-la no Postman acessando http://api.localhost.com/docs/api e exportando.

10. Após a instalação, você deve clonar o [repositório frontend](https://github.com/gustavocabreira/huggy-fullstack-challenge-spa) e seguir os passos descritos no README.md do repositório.

## Código de respostas HTTP

| Código | Descrição             | Explicação                                                                     | 
|--------|-----------------------|--------------------------------------------------------------------------------|
| 200    | OK                    | A requisição performou com sucesso.                                            |
| 201    | Created               | O recurso foi criado com sucesso.                                              |
| 204    | No Content            | A requisição performou com sucesso, mas não retornou nenhum conteúdo.          |
| 403    | Forbidden             | O recurso não pode ser acessado/alterado pois você não possui permissão.       |
| 404    | Not Found             | O recurso não foi encontrado.                                                  |
| 422    | Unprocessable Entity  | O recurso não pode ser processado devido a um erro nas informações fornecidas. |
| 500    | Internal Server Error | Ocorreu um erro no servidor.                                                   |

## Testes

Os testes do projeto estão no diretório `tests/` e foram desenvolvidos utilizando o pacote [Pest](https://pestphp.com/docs/installation).
Pest é uma biblioteca de testes para PHP que permite escrever testes de forma fácil e rápida.

Para executar os testes, siga os passos abaixo:

1. Acesse o diretório /docker/local
2. Execute o comando para interagir com o container

```bash
docker compose exec -it laravel sh
```

3. Execute o seguinte comando

```bash
php artisan test
```

## Mailhog e Jim

Mailhog é uma ferramenta de SMTP de teste que permite enviar e-mails para um servidor SMTP local.
Jim é uma ferramenta de simulação de erros que permite simular erros de conexão, erros de autenticação e outros erros comuns em aplicações que usam o protocolo SMTP.

Utilizando o JIM para simular o comportamento de um servidor SMTP, conseguimos criar cenários como:
- Enviar e-mails com sucesso
- Enviar e-mails com falha
- Enviar e-mails com erros de conexão
- Enviar e-mails com erros de autenticação

Para utilizá-lo, siga os passos abaixo:

1. Após a instalação do projeto, acesse http://localhost:8025

## Meilisearch

Meilisearch é uma plataforma de busca de texto completo que permite pesquisar em documentos de texto, imagens e outros tipos de dados.

Para acessá-lo, siga os passos abaixo:

1. Acesse http://localhost:7700
2. Informe a API Key `meilisearch1234`
