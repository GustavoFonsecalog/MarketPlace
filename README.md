🛒 Shopping Cart – Fullstack Challenge

Simulação completa de um carrinho de compras online, desenvolvido como desafio técnico para demonstrar organização de código, boas práticas e integração frontend + backend.

## ✨ Funcionalidades ##

Frontend (Vue.js)

Adicionar e remover produtos do carrinho
Escolher forma de pagamento:
Pix (desconto)
Cartão de crédito à vista
Cartão parcelado (com juros compostos)
Exibir resumo da compra com valor total atualizado em tempo real

Backend (PHP – Slim Framework)
API REST para processar o carrinho
Aplicação de regras de negócio (descontos e juros)
Retorno do valor final consolidado
Testes
Testes unitários no backend validando cálculos de desconto e juros

## 🛠️ Tecnologias ##

Frontend
Vue.js 3 (Composition API)
Axios (requisições HTTP)
Vite (build rápido)
TailwindCSS (estilização)

Backend
PHP 8+
Slim Framework (API REST)
PHPUnit (testes unitários)

Geral
Git & GitHub Flow (develop como branch principal)
ESLint & PHP-CS-Fixer (padrões de código)

## 📂 Estrutura de Pastas ##
.
├── backend/               # API em PHP (Slim)
│   ├── app/               # Controllers, Models, Services
│   ├── routes/            # Definição de rotas
│   ├── tests/             # Testes unitários (PHPUnit)
│   ├── composer.json
│   └── start-server.bat   # Script para subir o servidor
│
├── frontend/              # Aplicação Vue.js
│   ├── src/
│   │   ├── assets/        # Estilos, imagens
│   │   ├── components/    # Componentes reutilizáveis
│   │   ├── store/         # Estado global (Vuex/Pinia)
│   │   └── views/         # Páginas principais
│   └── package.json
│
├── .gitignore
├── README.md
└── docker-compose.yml     # (se houver ambiente containerizado)


## ⚙️ Como rodar o projeto ##

🔹 Backend
Dentro da pasta backend, basta rodar o script:
.\start-server.bat
A API ficará disponível em:
👉 http://localhost:8000

🔹 Frontend
Dentro da pasta frontend:
npm install
npm run dev
Aplicação disponível em:
👉 http://localhost:3000

✅ Testes
Backend
cd backend
./vendor/bin/phpunit

## 📝 Commits organizados ##

Mesmo sendo o primeiro push, os commits foram organizados por contexto para mostrar boas práticas:
chore: configuração inicial do projeto (dependências, env, gitignore)
feat(backend): criação da API base com rotas, models e controllers
feat(frontend): estrutura inicial de telas e componentes de layout
feat(frontend): lógica do carrinho, integração com API e fluxo de checkout
test(backend): criação de testes unitários para regras de negócio do carrinho
docs: documentação do projeto e instruções de execução


## Funcionalidades ##

Menu de Navegação: leva diretamente a listagem ja filtrada com a opção escolhida.
<p align="center"> <img src="https://github.com/user-attachments/assets/cfb3194c-cb8f-475b-8c43-1c63be41bdac" style="max-width:100%;" /> <img src="https://github.com/user-attachments/assets/666430f8-58c3-43e4-83dc-bc60f1c1f73c" style="max-width:100%;" /> </p>

Filtros
<p align="center"> <img src="https://github.com/user-attachments/assets/0c37b06d-8353-4ecd-918b-7fde0771f5b3" style="max-width:100%;" /> </p>

Modelo de visualização:
<p align="center"> <img src="https://github.com/user-attachments/assets/b44f7101-8521-4f8c-b080-cfb4c397c5eb" style="max-width:100%;" /> <img src="https://github.com/user-attachments/assets/4fb04426-d195-449a-87ba-33622069edf2" style="max-width:100%;" /> <img src="https://github.com/user-attachments/assets/5c3da4e1-ecad-4007-8c1a-3801acff79e2" style="max-width:100%;" /> </p>

Menu para demais funções:
<p align="center"> <img src="https://github.com/user-attachments/assets/0e1728ca-be54-487e-a617-a2400b382112" style="max-width:100%;" /> </p>

Meus pedidos: possível visualizar as compras ja finalizadas e detalhes da mesma.
<p align="center"> <img src="https://github.com/user-attachments/assets/f08583c1-e364-449f-a3c3-8d5365f2c6cb" style="max-width:100%;" /> </p>

Configurações: Limpar favoritas, tema Dark & Light, Listagem de favoritos.
<p align="center"> <img src="https://github.com/user-attachments/assets/36d0b0e8-1ab6-470e-afde-fa5009e6a9d0" style="max-width:100%;" /> </p>

Carrinho de compra e opões de pagamento:
<p align="center"> <img src="https://github.com/user-attachments/assets/83f54289-15ff-4ad4-91e2-8535f22d4155" style="max-width:100%;" /> <img src="https://github.com/user-attachments/assets/bb51a18f-a00e-410d-87ad-e47ed9a9b5bd" style="max-width:100%;" /> <img src="https://github.com/user-attachments/assets/deedc73a-e200-4e77-abce-67affb8f8e27" style="max-width:100%;" /> </p>






