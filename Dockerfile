FROM webdevops/php-apache-dev:5.6
MAINTAINER ed.mizurov@notebook.com

RUN apt update && apt install -y libpq-dev libgearman-dev && docker-php-ext-install pdo pdo_pgsql
RUN apt install -y libgearman-dev && curl https://pecl.php.net/get/gearman-1.1.2.tgz -o /tmp/gearman-1.1.2.tgz
RUN cd /tmp; tar -xzvf gearman-1.1.2.tgz && cd gearman-1.1.2 && phpize && ./configure && make && make install   
RUN mv /tmp/gearman-1.1.2/modules/gearman.so /usr/local/lib/php/extensions/no-debug-non-zts-20131226/
RUN echo "extension=gearman.so" > /usr/local/etc/php/conf.d/gearman.ini

 