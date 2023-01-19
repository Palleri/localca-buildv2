FROM php:8.1.9-apache
ENV ca='' \ 
cakey_FILE='' \
cakey='' \
TINI_VERSION='v0.19.0'
ADD https://github.com/krallin/tini/releases/download/${TINI_VERSION}/tini /tini
RUN apt update && apt install vim openssl ssmtp systemd sudo iputils-ping -y && chmod +x /tini
#RUN apk add --update openssl sudo vim bash


#ENV cakey_FILE=''
#ENV cakey=''
#ENV C=''
#ENV O=''

COPY files /tmp


#COPY run.sh /run.sh
#COPY bin/* /etc/
#COPY src /var/www/


#COPY index.php /var/www/
#COPY style.css /var/www/
#COPY files /var/www/
#COPY bg.jpg /var/www/
#RUN chown -R www-data:www-data /var/www/* 
#ENTRYPOINT ["/run.sh", "env"]


#RUN chmod +x /tini
ENTRYPOINT ["/tini", "--"]

CMD ["/tmp/run.sh", "env"]


