#!/usr/bin/env bash

mysql --user=root --password="$MYSQL_ROOT_PASSWORD" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS invoice_api;
    GRANT ALL PRIVILEGES ON \`invoice_api%\`.* TO '$MYSQL_USER'@'%';
EOSQL
