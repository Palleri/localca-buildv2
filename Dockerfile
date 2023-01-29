FROM php:8.1.9-apache
ENV ca='' \ 
cakey_FILE='' \
cakey='' \
TINI_VERSION='v0.19.0'
ADD https://github.com/krallin/tini/releases/download/${TINI_VERSION}/tini /tini
RUN apt update && apt install cron vim openssl ssmtp systemd sudo iputils-ping -y && chmod +x /tini

COPY files /tmp

ENTRYPOINT ["/tini", "--"]

CMD ["/tmp/run.sh", "env"]


