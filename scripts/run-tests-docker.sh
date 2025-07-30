#!/bin/bash

echo "Executando testes dentro do container Docker..."

# Executar testes dentro do container
docker compose exec app php artisan test

echo "Testes concluídos!" 