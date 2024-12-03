#!/bin/bash

# Esperar pelo banco de dados estar disponível
echo "Aguardando o banco de dados..."
/usr/local/bin/wait-for-it database:3306 --timeout=20 -- echo "Banco de dados disponível!"

# Executar migrações
php artisan migrate --force

# Iniciar o Apache
apache2-foreground

# Ajustar permissões antes de iniciar
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
