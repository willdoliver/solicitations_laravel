# Sistema de Gestão de Solicitações

## Objetivo

Desenvolver uma aplicação web simples para gerenciar solicitações internas de uma empresa (ex.: pedidos de suprimentos, solicitações de TI), com foco em usabilidade, escalabilidade, organização de código e funcionalidades úteis.

## Ambiente

-   Rodar container

`docker compose up -d`

-   Rodar Migration e adicionar dados padrões

`php artisan migrate`\
`php artisan db:seed`

-   Executar testes

`./vendor/bin/phpunit` (caso esteja instalado somente no ambiente local)\
`phpunit` (caso esteja instalado globalmente)

-   Compilar JS/CSS com Laravel Mix

`npm run dev`

-   Subir Laravel

`php artisan serve --port 8088`

## Diagrama Usuário/Funcionalidades

![Solicitations](https://github.com/user-attachments/assets/19fde573-b509-43f2-9d25-ba472a202bb9)
