FROM php:7.2.12-apache
COPY php.ini /usr/local/etc/php/

RUN a2enmod rewrite

RUN apt-get update \
	&& apt-get install -y libfreetype6-dev vim libjpeg62-turbo-dev libpng-dev zip unzip ssh sshpass rsync\
	&& docker-php-ext-install pdo_mysql mysqli mbstring gd iconv

WORKDIR /var/www/html

# Install Wordpress
ENV WORDPRESS_VERSION 5.5

RUN set -ex; \
	curl -fSL "https://wordpress.org/wordpress-${WORDPRESS_VERSION}.tar.gz" -o wordpress.tar.gz \
	&& tar  -xz --strip-components=1 -f wordpress.tar.gz \
	&& rm wordpress.tar.gz \
	&& chown -R www-data:www-data /var/www/html/wp-content

# Pass key from secrets
RUN mkdir /root/.ssh/ && ln -s /run/secrets/host_ssh_key /root/.ssh/id_rsa
RUN touch /root/.ssh/known_hosts
RUN echo "StrictHostKeyChecking no" >> /etc/ssh/ssh_config

COPY ./code/config/wp-config.php ./
COPY ./code/config/.htaccess ./

COPY ./commands/plugin-sync.sh ./
RUN chmod +x plugin-sync.sh

CMD bash -c "./plugin-sync.sh" & bash -c "apache2-foreground"
