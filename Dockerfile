FROM php:8.1.9-apache
RUN apt update
RUN apt install vim openssl sudo -y
#RUN apk add --update openssl sudo vim bash

ENV ca=''
ENV cakey_FILE=''
ENV cakey=''
ENV C=''
ENV O=''
COPY run.sh /run.sh
COPY bin/* /etc/
COPY src /var/www/
#COPY index.php /var/www/
#COPY style.css /var/www/
#COPY files /var/www/
#COPY bg.jpg /var/www/
#RUN chown -R www-data:www-data /var/www/* 
ENTRYPOINT ["/run.sh", "env"]
