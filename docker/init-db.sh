#!/bin/bash
set -e

mysql -u root -p${MYSQL_ROOT_PASSWORD} <<-EOSQL
    CREATE DATABASE IF NOT EXISTS lmeve;
    CREATE DATABASE IF NOT EXISTS eve_static_data;
    GRANT ALL PRIVILEGES ON lmeve.* TO '${MYSQL_USER}'@'%';
    GRANT ALL PRIVILEGES ON eve_static_data.* TO '${MYSQL_USER}'@'%';
    FLUSH PRIVILEGES;
EOSQL

# Schema is loaded by the eve_data_updater container (docker/update-eve-data.sh),
# which ships data/schema.sql from this repo directly.
