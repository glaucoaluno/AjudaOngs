#!/bin/bash

echo "Deploy do projeto AjudaOngs"
echo "=============================="

# Verificar se o Docker está rodando
if ! docker info > /dev/null 2>&1; then
    echo "Docker não está rodando. Inicie o Docker primeiro."
    exit 1
fi

echo "Construindo containers..."
docker compose build

echo "Parando containers existentes..."
docker compose down

echo "Iniciando containers..."
docker compose up -d

echo "Aguardando containers estarem prontos..."
sleep 10

echo "Status dos containers:"
docker compose ps

echo ""
echo "Deploy concluído!"
echo ""
echo "Informações:"
echo "   - Frontend: http://localhost:8989"
echo "   - API: http://localhost:8989/api"
echo "   - Banco Principal: trabalho_ajuda_ongs"
echo "   - Banco de Teste: ajudaongs_test"
echo ""
echo "Para executar testes:"
echo "   docker compose exec app php artisan test --env=testing"
echo ""
echo "Para ver logs:"
echo "   docker compose logs -f app" 