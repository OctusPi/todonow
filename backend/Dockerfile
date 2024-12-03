# Base para PHP com Apache
FROM php:8.3-apache

# Instalar extensões necessárias para o laravel funcionar
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git curl && \
    docker-php-ext-install zip pdo pdo_mysql && \
    a2enmod rewrite

# Configurar o Apache para usar "public" como DocumentRoot
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar o código Laravel e definir o diretório de trabalho
WORKDIR /var/www/html
COPY . .

# Instalar dependências do Laravel
RUN composer install --optimize-autoloader

# Ajustar permissões
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

#adiciona script wait for it para aguardar montagem do mysql server
COPY wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod +x /usr/local/bin/wait-for-it

# Definir ponto de entrada
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
