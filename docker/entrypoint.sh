#!/bin/bash

echo "Iniciando aplicação Laravel..."

# Verificar se as dependências estão instaladas
if [ ! -d "vendor" ]; then
    echo "Instalando dependências do Composer..."
    composer install --no-interaction --optimize-autoloader
fi

# Verificar se o arquivo .env existe
if [ ! -f ".env" ]; then
    echo "Copiando arquivo de ambiente..."
    cp .env.example .env
    php artisan key:generate
fi

# Executar script de inicialização do banco em background
echo "Configurando bancos de dados..."
/usr/local/bin/init-db.sh &

# Executar o comando original (php-fpm)
echo "Iniciando PHP-FPM..."
exec "$@" 