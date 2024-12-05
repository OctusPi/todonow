# Construir a aplicação Vue.js
FROM node:lts as build

WORKDIR /app

# Copiar arquivos necessários
COPY package*.json ./
RUN npm install
COPY . .

# Construi a aplicação para produção
RUN npm run build

# Serve aplicação com Nginx
FROM nginx:latest

# Copia os arquivos estáticos para o diretório padrão do Nginx
COPY --from=build /app/dist /usr/share/nginx/html

# Configuração do Nginx
COPY nginx-frontend.conf /etc/nginx/conf.d/default.conf
