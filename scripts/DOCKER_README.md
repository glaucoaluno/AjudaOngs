# 🐳 Docker - AjudaOngs

Este documento explica como usar o Docker para executar o projeto AjudaOngs.

## 🚀 Deploy Rápido

### Opção 1: Deploy Automático (Recomendado)
```bash
./scripts/deploy.sh
```

### Opção 2: Deploy Manual
```bash
# Construir e iniciar containers
docker compose up -d --build

# Verificar status
docker compose ps
```

## 📋 Serviços Configurados

| Serviço | Porta | Descrição |
|---------|-------|-----------|
| **app** | 9000 | Laravel PHP-FPM |
| **nginx** | 8989 | Servidor Web |
| **postgres** | 5432 | Banco de Dados |

## 🗄️ Bancos de Dados

### Banco Principal
- **Nome**: `trabalho_ajuda_ongs`
- **Usuário**: `bob`
- **Senha**: `postgres_bob_user_docker`
- **Propósito**: Dados de desenvolvimento/produção

### Banco de Teste
- **Nome**: `ajudaongs_test`
- **Usuário**: `bob`
- **Senha**: `postgres_bob_user_docker`
- **Propósito**: Testes isolados

## 🔧 Comandos Úteis

### Gerenciamento de Containers
```bash
# Iniciar containers
docker compose up -d

# Parar containers
docker compose down

# Ver logs
docker compose logs -f app

# Acessar container
docker compose exec app bash
```

### Banco de Dados
```bash
# Acessar PostgreSQL
docker compose exec postgres psql -U bob -d trabalho_ajuda_ongs

# Verificar bancos
docker compose exec postgres psql -U bob -l

# Backup do banco principal
docker compose exec postgres pg_dump -U bob trabalho_ajuda_ongs > backup.sql
```

### Testes
```bash
# Executar testes
docker compose exec app php artisan test --env=testing

# Executar testes específicos
docker compose exec app php artisan test --filter=test_user_can_login --env=testing
```

### Laravel
```bash
# Executar migrações
docker compose exec app php artisan migrate

# Executar seeders
docker compose exec app php artisan db:seed

# Limpar cache
docker compose exec app php artisan cache:clear
```

## 🔄 Scripts de Inicialização

### `docker/entrypoint.sh`
- Instala dependências do Composer
- Configura arquivo `.env`
- Executa migrações e seeders
- Inicia PHP-FPM

### `docker/init-db.sh`
- Aguarda PostgreSQL estar pronto
- Cria banco de teste se não existir
- Executa migrações em ambos os bancos
- Executa seeders no banco principal

### `docker/init-multiple-databases.sh`
- Cria múltiplos bancos de dados no PostgreSQL
- Configura permissões de usuário

## 📁 Estrutura de Arquivos Docker

```
docker/
├── entrypoint.sh              # Script de inicialização da aplicação
├── init-db.sh                 # Script de configuração dos bancos
├── init-multiple-databases.sh # Script de criação de múltiplos bancos
├── nginx/
│   └── laravel.conf          # Configuração do Nginx
└── php/
    └── custom.ini            # Configurações PHP personalizadas
```

## 🔍 Troubleshooting

### Container não inicia
```bash
# Verificar logs
docker compose logs app

# Reconstruir container
docker compose build --no-cache app
```

### Banco de dados não conecta
```bash
# Verificar se PostgreSQL está rodando
docker compose ps postgres

# Verificar logs do PostgreSQL
docker compose logs postgres
```

### Migrações falham
```bash
# Executar migrações manualmente
docker compose exec app php artisan migrate --force

# Verificar status das migrações
docker compose exec app php artisan migrate:status
```

## 🌐 URLs de Acesso

- **API**: http://localhost:8989/api
- **Admin**: http://localhost:8989/Administrativo

## 📊 Monitoramento

### Verificar uso de recursos
```bash
docker stats
```

### Verificar volumes
```bash
docker volume ls
```

### Limpar recursos não utilizados
```bash
docker system prune -a
```

---

**💡 Dica**: Use o script `./deploy.sh` para um deploy completo e automatizado! 