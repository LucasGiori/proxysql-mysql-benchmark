#!/bin/bash
set -e

# Cores básicas
RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m' # No Color

# Espera o ProxySQL ficar disponível
echo -e "${GREEN}Waiting for ProxySQL...${NC}"
while ! mysqladmin ping -P6032 -uradmin -pradmin --silent; do
    sleep 1
done

# Carrega configuração inicial
echo -e "${GREEN}Loading configuration...${NC}"
mysql -P6032 -uradmin -pradmin < /docker-entrypoint-initdb.d/config.sql

echo -e "${GREEN}ProxySQL ready!${NC}"