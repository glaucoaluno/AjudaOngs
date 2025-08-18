#!/bin/bash

echo "Iniciando testes do projeto AjudaOngs..."
echo "=========================================="

# Verificar se o PHPUnit está instalado
if ! command -v ./vendor/bin/phpunit &> /dev/null; then
    echo "PHPUnit não encontrado. Instalando dependências..."
    composer install
fi

echo ""
echo "Executando todos os testes..."
echo "--------------------------------"

# Executar todos os testes com PHPUnit binário (configurado para banco de teste)
echo "Usando ./vendor/bin/phpunit (configurado para banco de teste via phpunit.xml)"
./vendor/bin/phpunit

echo ""
echo "Executando testes unitários..."
echo "--------------------------------"

# Executar apenas testes unitários
./vendor/bin/phpunit --testsuite=Unit

echo ""
echo "Executando testes de funcionalidade..."
echo "----------------------------------------"

# Executar apenas testes de funcionalidade
./vendor/bin/phpunit --testsuite=Feature

echo ""
echo "Executando testes com cobertura..."
echo "------------------------------------"

# Executar testes com cobertura (se disponível)
./vendor/bin/phpunit --coverage-text

echo ""
echo "Testes concluídos!"
echo "====================" 