# 🧺 Artezzana – Plataforma para Feirantes

---

# Índice

1. [Introdução](#introdução)
2. [Funcionalidades](#funcionalidades)
3. [Conponentes da Interface](#componentes_da_interface)
4. [Tecnologias Utilizadas](#tecnologias_utilizadas)
5. [Público-alvo](#público-alvo)
6. [Motivação](#motivação)
7. [Como executar o projeto](#como-executar-o-projeto)

---

## Introdução

Uma aplicação web interativa desenvolvida como projeto da disciplina **Desenvolvimento Front-End 1**, com foco em **HTML5**, **CSS3** e **JavaScript**. A proposta é criar uma plataforma onde feirantes possam divulgar seus produtos e consumidores possam encontrá-los de forma fácil, acessível e responsiva.

## Funcionalidades

- Página inicial com informações da plataforma
- Navbar responsiva com menu hambúrguer funcional em telas pequenas

---

## Componentes da Interface

### ✅ Navbar responsiva

A aplicação possui uma **barra de navegação (navbar)** fixa no topo, construída com HTML semântico (`<nav>` e `<ul>`), com as seguintes características:

- **Menu hambúrguer funcional** em dispositivos móveis (até 768px)
- Interatividade com JavaScript puro para alternar a visibilidade do menu
- Layout responsivo utilizando **media queries**
- Estilo ajustado para desktop e mobile (mobile-first)
- Links para as páginas principais: Buscar, Carrinho, Pedido e Perfil

A navbar foi estilizada no arquivo `/assets/css/global.css` e seu comportamento dinâmico implementado em `/assets/js/index.js`.

---

## Tecnologias Utilizadas

- **HTML5** (estrutura semântica)
- **CSS3** (Flexbox, Grid, Media Queries)
- **JavaScript** – manipulação do DOM, validação, interatividade
<!-- - **APIs HTML5**:
  - `localStorage` / `sessionStorage`
  - `Geolocation` (em breve)
- **Organização modular** com arquivos separados por responsabilidade -->

---

## Público-alvo

- Consumidores interessados em produtos de feira
- Feirantes locais que desejam divulgar seus produtos e localização

---

## Motivação

Fortalecer a presença digital dos feirantes e facilitar o acesso a produtos locais e artesanais, promovendo uma economia mais justa e regionalizada. Esse projeto também se conecta diretamente com o meu projeto de **TCC**, que visa desenvolver uma plataforma completa para pequenos produtores.

---

## Como executar o projeto

1. Clone este repositório:
```bash
git clone git@github.com:l-guidotti/artezzana.git
```