FROM ubuntu:24.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    php-mysql \
    php-pear \
    apache2 \
    libapache2-mod-php \
    php-cli \
    php-dev \
    libyaml-dev \
    php-mbstring \
    python3-yaml \
    wget \
    unzip \
    cron \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/lmeve

# Build from this checkout (the fork), not from upstream.
# docker-compose builds with context: .. so this COPY sees the whole repo.
COPY . /var/www/lmeve

RUN sed -i 's|/var/www/html|/var/www/lmeve/wwwroot|g' /etc/apache2/sites-available/000-default.conf

# Use the config template that ships in this repo (was previously fetched from
# roxlukas/lmeve on GitHub, which meant the container never actually ran the
# code you just built).
RUN cp /var/www/lmeve/config/config-dist.php /var/www/lmeve/config/config.php && \
    sed -i "s/\$LM_dbhost='localhost'/\$LM_dbhost=getenv('DB_HOST')/" /var/www/lmeve/config/config.php && \
    sed -i "s/\$LM_dbname='lmeve'/\$LM_dbname=getenv('DB_NAME_LMEVE')/" /var/www/lmeve/config/config.php && \
    sed -i "s/\$LM_dbuser='lmeve'/\$LM_dbuser=getenv('DB_USER')/" /var/www/lmeve/config/config.php && \
    sed -i "s/\$LM_dbpass='password'/\$LM_dbpass=getenv('DB_PASSWORD')/" /var/www/lmeve/config/config.php && \
    sed -i "s/\$LM_EVEDB='eve_tia100_dbo'/\$LM_EVEDB=getenv('DB_NAME_STATIC')/" /var/www/lmeve/config/config.php

# Enable the yaml extension for whichever PHP version Ubuntu ships (this used
# to be hardcoded to 8.3, which silently broke once the ubuntu:latest base
# moved on to a newer PHP release).
RUN apt-get update && apt-get install -y libyaml-0-2 libyaml-dev && \
    printf "\n" | pecl install yaml && \
    for ini in $(find /etc/php -name php.ini); do \
        echo "extension=yaml.so" >> "$ini"; \
    done

RUN echo "*/15 * * * * www-data /usr/bin/php /var/www/lmeve/bin/poller.php" >> /etc/crontab

RUN mkdir -p /var/www/lmeve/var && chown www-data:www-data /var/www/lmeve/var

EXPOSE 80

CMD apache2ctl -D FOREGROUND
