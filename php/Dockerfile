FROM php:8.2.12-apache
RUN apt-get update && apt-get install -y default-mysql-client
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get upgrade -y
