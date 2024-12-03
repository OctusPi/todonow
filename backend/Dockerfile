# Base para PHP com Apache
FROM php:8.3-apache

RUN mkdir -p /var/www/html \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html

# Instalar extensões do PHP necessárias para o Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git curl && \
    docker-php-ext-install zip pdo pdo_mysql && \
    a2enmod rewrite

# Garanta que os diretórios têm permissão de execução
RUN find /var/www/html -type d -exec chmod 755 {} \;

# Garanta que os arquivos têm permissão de leitura e gravação
RUN find /var/www/html -type f -exec chmod 644 {} \;

# Instalar o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar o código fonte
WORKDIR /var/www/html
COPY . .

# Instalar as dependências do Laravel
RUN composer install --optimize-autoloader

# Ajustar permissões de pasta
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

