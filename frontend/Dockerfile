# Etapa 1: Construção do Vue.js
FROM node:lts as build

WORKDIR /app

# Copiar arquivos necessários
COPY package*.json ./
RUN npm install
COPY . .

# Construir a aplicação
RUN npm run build

# Etapa 2: Servir com NGINX
FROM nginx:latest

# Copiar os arquivos estáticos gerados para a pasta padrão do NGINX
COPY --from=build /app/dist /usr/share/nginx/html

# Configuração do NGINX
COPY nginx-frontend.conf /etc/nginx/conf.d/default.conf
