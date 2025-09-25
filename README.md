# 🏥 Ajuda ONGs - Sistema de Gerenciamento de Doações

Sistema completo para auxílio no gerenciamento de doações para ONGs em Campinas.

**Projeto de Trabalho de Conclusão de Curso - IFSP Campinas**

## 👥 Autores

- **Elisângela**
- **Glauco Neto**

## 🚀 Deploy Rápido

### Pré-requisitos
- PHP e PostgreSQL
- Git

### Deploy Automático
```bash
# Clone o repositório
git clone https://github.com/glaucoaluno/AjudaOngs.git
cd AjudaOngs

# Execute o deploy
./scripts/deploy.sh
```

### URLs de Acesso
- **API**: http://localhost:8000/api
- **Admin**: http://localhost:8000/Administrativo

## 🧪 Testes

### Executar Testes
```bash

# Testes locais
./scripts/run-tests.sh
```

### Configurar Banco de Teste
```bash
# Configurar banco de teste
./scripts/setup-test-db.sh

# Limpar banco de teste
./scripts/clean-test-db.sh
```

## 🏗️ Arquitetura

### Backend
- **Framework**: Laravel 11
- **Banco de Dados**: PostgreSQL
- **Autenticação**: Laravel Sanctum
- **API**: RESTful

### Frontend
- **Tecnologia**: HTML, CSS, JavaScript (Vanilla)
- **HTTP Client**: Axios
- **Servidor**: Nginx

### Infraestrutura
- **Servidor Web**: Nginx
- **PHP**: PHP-FPM 8.3

## 📁 Estrutura do Projeto

```
AjudaOngs/
├── app/                    # Código Laravel
├── database/              # Migrações e Seeders
├── scripts/               # Scripts de automação
│   ├── deploy.sh          # Deploy completo
│   ├── run-tests.sh       # Testes locais
│   ├── setup-test-db.sh   # Configuração banco teste
│   ├── clean-test-db.sh   # Limpeza banco teste
│   ├── TESTES_README.md   # Documentação de testes
├── tests/                 # Testes automatizados
├── tcfinal/              # Frontend (HTML/CSS/JS)
└── README.md            # Este arquivo
```

## 🗄️ Bancos de Dados

### Banco Principal
- **Nome**: `trabalho_ajuda_ongs`
- **Propósito**: Dados de desenvolvimento/produção

### Banco de Teste
- **Nome**: `ajudaongs_test`
- **Propósito**: Testes isolados

## 🔧 Comandos Úteis

### Laravel
```bash
# Executar migrações
php artisan migrate

# Executar seeders
php artisan db:seed

# Limpar cache
php artisan cache:clear
```

## 📚 Documentação

- **Scripts e Automação**: `scripts/README.md`
- **Sistema de Testes**: `scripts/TESTES_README.md`

## 🧪 Status dos Testes

- **Total de Testes**: 57
- **Testes Passando**: 55
- **Testes Pulados**: 2
- **Cobertura**: Funcionalidades principais

## 🔐 Credenciais de Acesso

### Admin
- **Email**: admin@admin.com
- **Senha**: admin1234

## 📋 Funcionalidades

### Público
- Cadastro de doações
- Visualização de informações

### Administrativo
- Login seguro
- Gerenciamento de famílias beneficiadas
- Registro de doações
- Relatórios de distribuição
- Teste de API

## 🚀 Próximos Passos

1. **Deploy em Produção**
2. **Monitoramento e Logs**
3. **Backup Automático**
4. **CI/CD Pipeline**

---

**💡 Dica**: Use `./scripts/deploy.sh` para um deploy completo e automatizado!