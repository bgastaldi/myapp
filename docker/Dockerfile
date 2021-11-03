FROM php:8.0.10-fpm-alpine3.14

# Instalando NGINX e Supervisor
# Ngnix needs a run directory
# Send log output to supervisord's standard I/O
# (Note that supervisord is process 1)
RUN apk add --no-cache nginx supervisor && \
        install -d /run/nginx && \
        ln -s /proc/1/fd/1 /var/log/nginx/access.log && \
        ln -s /proc/1/fd/2 /var/log/nginx/error.log;

# Starting with alpine 3.14 nginx is using http.d instead of conf.d - symlink http.d to conf.d
RUN if [[ -d /etc/nginx/http.d ]]; then ln -s /etc/nginx/http.d /etc/nginx/conf.d; fi

# Copy Nginx configuration
COPY ./config/nginx-http.conf          /etc/nginx/conf.d/0-http.conf
COPY ./config/nginx-default.conf       /etc/nginx/conf.d/default.conf

# Copy PHP-FPM configuration
COPY ./config/php-fpm.conf             /usr/local/etc/php-fpm.d/zz-docker_nginx.conf

# Copy Supervisor configuration
COPY ./config/supervisord.conf         /etc/supervisor.d/supervisord.ini
COPY ./config/supervisord-php-fpm.conf /etc/supervisor.d/php-fpm.ini
COPY ./config/supervisord-nginx.conf   /etc/supervisor.d/nginx.ini


# Set working directory to where the application lives
WORKDIR /var/www

RUN rm -rf /var/www/html
COPY ../app /var/www

# Install all extension that application depends
RUN apk add --no-cache zip libzip-dev
RUN docker-php-ext-configure zip && \
        docker-php-ext-install zip && \
        docker-php-ext-install mysqli pdo pdo_mysql && \
        docker-php-ext-enable pdo_mysql

RUN chown -R www-data:www-data /var/www

# Set command to run
CMD [ "supervisord", "--nodaemon", "-c", "/etc/supervisord.conf" ]

# Expose HTTP port
EXPOSE 80
