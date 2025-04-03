# Teste para Vaga de Desenvolvedor PHP Sênior - Sistema de Gestão de Solicitações

## Objetivo

Desenvolver uma aplicação web simples para gerenciar solicitações internas de uma empresa (ex.: pedidos de suprimentos, solicitações de TI), com foco em usabilidade, escalabilidade, organização de código e funcionalidades úteis.

## Prazo

7 dias a partir do envio do teste.

## Instruções Gerais

-   Envie o código em um repositório Git (ex.: GitHub, GitLab) com instruções claras no README para instalação e execução.
-   Use PHP 7.4 ou superior, com framework Laravel.
-   Persista os dados em um banco de dados relacional (ex.: SQLite, MySQL, PostgresSQL).
-   A interface deve ser funcional e limpa, com CSS básico ou framework (ex.: Bootstrap).
-   Foque em uma solução prática e reutilizável, como seria em um ambiente empresarial.

---

## Parte Prática: Projeto

### Tarefa

Criar um sistema web para registro e acompanhamento de solicitações internas.

### Requisitos Funcionais

1. **Entidades:**
    - **Solicitação:** ID, título, descrição, categoria (ex.: TI, Suprimentos, RH), status (aberta, em andamento, concluída), data de criação, solicitante (nome ou e-mail simples).
    - **Usuário:** Apenas um campo para identificar o solicitante (sem autenticação complexa).
2. **Funcionalidades:**
    - **Criar Solicitação:** Formulário para registrar uma nova solicitação com os campos acima.
    - **Listar Solicitações:** Tabela com todas as solicitações, com filtros por status e categoria.
    - **Atualizar Status:** Permitir alterar o status de uma solicitação (ex.: de "aberta" para "em andamento").
    - **Detalhes:** Visualizar os detalhes de uma solicitação específica ao clicar nela.
3. **Regras de Negócio:**
    - Título e categoria são obrigatórios.
    - O status inicial de uma solicitação é "aberta".
    - A data de criação é preenchida automaticamente.

### Requisitos Técnicos

-   Use POO para organizar o código (ex.: classes para `Solicitacao` e `SolicitacaoService`).
-   Siga o padrão PSR-12 para o PHP.
-   Trate erros de entrada (ex.: campos obrigatórios não preenchidos) com mensagens claras.
-   Persista os dados em um banco de dados com uma tabela simples (ex.: `solicitacoes`).
-   Escreva pelo menos 2 testes unitários (ex.: PHPUnit) para validar:
    -   Criação de uma solicitação.
    -   Atualização de status.
-   Interface simples e funcional (ex.: tabela com filtros e formulário básico).

### Diferenciais (Opcional)

-   Adicionar exportação da lista de solicitações para CSV.
-   Implementar um sistema de notificação fictício (ex.: log de alterações em uma tabela separada).
-   Adicionar busca por palavras-chave no título ou descrição.
-   Estilizar a interface com um tema empresarial (ex.: cores neutras, layout profissional).

---

### Importantes:
    • Candidato: Willian Okubo
    • Data do envio do teste: 03/04/2025
    • Data da apresentação:  10/04/2025 às 14:00hs
    • Link: https://meet.google.com/wee-vcrn-ifc?authuser=0
    • Caso termine antes podemos adiantar a reunião.

-   Para qualquer dúvida, estaremos disponíveis para esclarecimentos durante o período de teste.
Contato: (41) 99206-4136 – Alexandro
