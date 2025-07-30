# 📁 Scripts - AjudaOngs

Esta pasta contém todos os scripts de automação e documentação do projeto AjudaOngs.

## 🚀 Scripts de Deploy

### `deploy.sh`
Script principal para deploy completo do projeto usando Docker.
```bash
./scripts/deploy.sh
```

**Funcionalidades:**
- Constrói e inicia containers Docker
- Configura bancos de dados automaticamente
- Executa migrações e seeders
- Verifica status dos serviços

## 🧪 Scripts de Teste

### `run-tests.sh`
Executa testes localmente (fora do Docker).
```bash
./scripts/run-tests.sh
```

### `run-tests-docker.sh`
Executa testes dentro do container Docker.
```bash
./scripts/run-tests-docker.sh
```

### `setup-test-db.sh`
Configura o banco de dados de teste.
```bash
./scripts/setup-test-db.sh
```

### `clean-test-db.sh`
Limpa e recria o banco de dados de teste.
```bash
./scripts/clean-test-db.sh
```

### `reset-db.sh`
Reseta completamente o banco de dados principal (migrações + seeders).
```bash
./scripts/reset-db.sh
```

## 📚 Documentação

### `TESTES_README.md`
Documentação completa sobre o sistema de testes:
- Tipos de testes (Unit, Feature, Integration)
- Como executar testes
- Configuração do banco de teste
- Troubleshooting

### `DOCKER_README.md`
Documentação completa sobre Docker:
- Configuração dos containers
- Comandos úteis
- Troubleshooting
- URLs de acesso

## 📋 Estrutura da Pasta

```
scripts/
├── README.md              # Este arquivo
├── deploy.sh              # Deploy completo
├── run-tests.sh           # Testes locais
├── run-tests-docker.sh    # Testes no Docker
├── setup-test-db.sh       # Configuração banco teste
├── clean-test-db.sh       # Limpeza banco teste
├── reset-db.sh            # Reset banco principal
├── TESTES_README.md       # Documentação de testes
└── DOCKER_README.md       # Documentação Docker
```

## 🔧 Como Usar

### Deploy Rápido
```bash
# Na raiz do projeto
./scripts/deploy.sh
```

### Executar Testes
```bash
# Testes locais
./scripts/run-tests.sh

# Testes no Docker
./scripts/run-tests-docker.sh
```

### Gerenciar Bancos de Dados
```bash
# Configurar banco de teste
./scripts/setup-test-db.sh

# Limpar banco de teste
./scripts/clean-test-db.sh

# Resetar banco principal
./scripts/reset-db.sh
```

## 📖 Documentação Detalhada

Para informações detalhadas sobre:
- **Testes**: Consulte `TESTES_README.md`
- **Docker**: Consulte `DOCKER_README.md`

---

**💡 Dica**: Todos os scripts são executáveis e podem ser chamados diretamente da raiz do projeto usando `./scripts/nome-do-script.sh` 