#!/bin/bash

echo "🔄 Resetando banco de dados..."
echo "=============================="

# Verificar se estamos no container Docker
if [ -f "/.dockerenv" ]; then
    echo "📦 Executando dentro do container Docker..."
    
    # Resetar banco de dados
    echo "🗑️  Limpando banco de dados..."
    php artisan migrate:fresh --force
    
    echo "🌱 Executando seeders..."
    php artisan db:seed --force
    
    echo "✅ Banco de dados resetado com sucesso!"
    
else
    echo "🖥️  Executando localmente..."
    
    # Resetar banco de dados
    echo "🗑️  Limpando banco de dados..."
    php artisan migrate:fresh --force
    
    echo "🌱 Executando seeders..."
    php artisan db:seed --force
    
    echo "✅ Banco de dados resetado com sucesso!"
fi

echo ""
echo "📊 Status do banco:"
echo "   - Doadores: $(php artisan tinker --execute='echo App\Models\Doador::count();' 2>/dev/null)"
echo "   - Famílias: $(php artisan tinker --execute='echo App\Models\FamiliaBeneficiada::count();' 2>/dev/null)"
echo "   - Usuários: $(php artisan tinker --execute='echo App\Models\User::count();' 2>/dev/null)" 