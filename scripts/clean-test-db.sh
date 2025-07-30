#!/bin/bash

echo "🧹 Limpando banco de dados de teste..."

# Confirmar ação
read -p "Tem certeza que deseja limpar o banco de teste 'ajudaongs_test'? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Operação cancelada"
    exit 1
fi

# Dropar e recriar o banco de teste
echo "🗑️  Removendo banco de teste..."
docker compose exec postgres dropdb -U bob ajudaongs_test --if-exists

echo "📦 Recriando banco de teste..."
docker compose exec postgres createdb -U bob ajudaongs_test

echo "🔄 Executando migrações..."
php artisan migrate --env=testing

echo "✅ Banco de teste limpo e recriado com sucesso!"
echo ""
echo "📋 Status:"
echo "   - Banco principal: trabalho_ajuda_ongs (intacto)"
echo "   - Banco de teste: ajudaongs_test (limpo e recriado)" 