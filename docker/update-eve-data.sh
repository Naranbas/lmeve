#!/bin/bash
set -e

#initialize LMeve database
#make sure to only do it once

LOCK_FILE="schema_installed.lock"

# Port to check
PORT=3306

# Timeout in seconds (adjust as needed)
TIMEOUT=60

echo "Waiting for port $PORT to be open..."

# Loop until the port is open or timeout is reached
until nc -z ${DB_HOST} "$PORT" &> /dev/null; do
  echo "Port $PORT not yet open. Sleeping for 5 seconds..."
  sleep 5
done

echo "Port $PORT is now open!"

if [ -e "$LOCK_FILE" ]; then
    echo "LMeve Schema already imported"
else
    touch "$LOCK_FILE"
    mysql -h ${DB_HOST} -u root -p${MYSQL_ROOT_PASSWORD} <<-EOSQL
    CREATE DATABASE IF NOT EXISTS lmeve;
    CREATE DATABASE IF NOT EXISTS eve_static_data;
    GRANT ALL PRIVILEGES ON lmeve.* TO '${DB_USER}'@'%';
    GRANT ALL PRIVILEGES ON eve_static_data.* TO '${DB_USER}'@'%';
    FLUSH PRIVILEGES;
EOSQL

    # Schema now ships in the image (see Dockerfile.updater) instead of being
    # fetched from roxlukas/lmeve on GitHub, so this always matches the code
    # you're actually running.
    mysql -h ${DB_HOST} -u root -p${MYSQL_ROOT_PASSWORD} lmeve < /app/data/schema.sql
fi

#update EVE Static Data
# fuzzwork.co.uk restructured their dump directory - the old
# "mysql-latest.tar.bz2" bundle is gone. The current convenience link is a
# single gzipped SQL file. If this ever moves again, check
# https://www.fuzzwork.co.uk/dump/ for the current "latest-mysql*" name.
wget "https://www.fuzzwork.co.uk/dump/latest-mysql.sql.gz" -O eve_static_data.sql.gz

gunzip -f eve_static_data.sql.gz

echo "Importing EVE Static Data (this can take a while)..."
mysql -h ${DB_HOST} -u ${DB_USER} -p${DB_PASSWORD} ${DB_NAME_STATIC} < eve_static_data.sql

# Clean up
rm -f eve_static_data.sql

echo "EVE Static Data updated successfully"
