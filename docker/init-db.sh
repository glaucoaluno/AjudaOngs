#!/bin/bash

echo "Inicializando bancos de dados..."

# Aguardar o PostgreSQL estar pronto
echo "Aguardando PostgreSQL..."
until pg_isready -h postgres -U bob -d trabalho_ajuda_ongs; do
    echo "PostgreSQL não está pronto ainda..."
    sleep 2
done

echo "PostgreSQL está pronto!"

# Verificar se o banco de teste existe
if psql -h postgres -U bob -lqt | cut -d \| -f 1 | grep -qw ajudaongs_test; then
    echo "Banco de teste 'ajudaongs_test' já existe"
else
    echo "Criando banco de teste 'ajudaongs_test'..."
    createdb -h postgres -U bob ajudaongs_test
    echo "Banco de teste criado com sucesso!"
fi

echo "Executando migrações no banco principal..."
php artisan migrate --force

echo "Executando migrações no banco de teste..."
php artisan migrate --env=testing --force

echo "Executando seeders no banco principal..."
php artisan db:seed --force

echo "Inicialização dos bancos de dados concluída!"
echo ""
echo "Status dos bancos:"
echo "   - Banco principal: trabalho_ajuda_ongs"
echo "   - Banco de teste: ajudaongs_test" 