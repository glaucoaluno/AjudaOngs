#!/bin/bash

echo "🔧 Configurando banco de dados de teste..."

# Verificar se o banco de teste já existe
if docker compose exec postgres psql -U bob -lqt | cut -d \| -f 1 | grep -qw ajudaongs_test; then
    echo "✅ Banco de teste 'ajudaongs_test' já existe"
else
    echo "📦 Criando banco de teste 'ajudaongs_test'..."
    docker compose exec postgres createdb -U bob ajudaongs_test
    echo "✅ Banco de teste criado com sucesso!"
fi

# Executar migrações no banco de teste
echo "🔄 Executando migrações no banco de teste..."
php artisan migrate --env=testing

echo "✅ Configuração do banco de teste concluída!"
echo ""
echo "📋 Resumo:"
echo "   - Banco principal: trabalho_ajuda_ongs (dados de desenvolvimento)"
echo "   - Banco de teste: ajudaongs_test (dados de teste isolados)"
echo ""
echo "🧪 Para executar os testes:"
echo "   php artisan test" 