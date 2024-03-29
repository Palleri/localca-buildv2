#!/bin/bash

if [ -z "$cakey"  -a -z "$cakey_FILE" ];  then
        echo >&2 'error: No cakey is specified '
        echo >&2 '  You need to specify one of cakey or cakey_FILE'
        exit 1
fi
if [ ! -z "$cakey_FILE" -a -z "$cakey" ]; then
  cakey=$(cat $cakey_FILE)
fi






echo $ca > /var/www/ca.txt
echo $C > /var/www//C.txt
echo $O > /var/www/O.txt
if [ -d "/var/www/html/files/ca" ] 
then
    echo "Found CA." 
	cp /var/www/html/ssmtp.conf /etc/ssmtp/ssmtp.conf
	chown root:mail /etc/ssmtp/ssmtp.conf
	chmod 665 /etc/ssmtp/ssmtp.conf
else
	echo "First time install"
	mkdir /var/www/html/files/
	mkdir /var/www/html/files/ca
	mkdir /var/www/html/script
	echo -n $ca >> /var/www/html/files/ca/CA_NAME.txt
	echo -n $cakey >> /var/www/html/files/ca/CA_KEY.txt
	
	rm /etc/ssmtp/ssmtp.conf
	echo hostname=localca >> /etc/ssmtp/ssmtp.conf
	usermod -aG mail www-data
	chown root:mail /etc/ssmtp/ssmtp.conf
	chmod 665 /etc/ssmtp/ssmtp.conf

	openssl genrsa -des3 -out /var/www/html/files/ca/ca.key -passout pass:$cakey 2048
	sleep 5s
	openssl req -x509 -new -nodes -key /var/www/html/files/ca/ca.key -sha256 -days 1095 -out /var/www/html/files/ca/ca.pem -passin pass:$cakey     -subj "/CN=${ca}/C=${C}/O=${O}"
	cp -R /tmp/src/* /var/www/

	cp /var/www/index.php /var/www/html/index.php
	cp /var/www/settings.php /var/www/html/settings.php
	cp /var/www/style.css /var/www/html/style.css
	cp /var/www/bg.jpg /var/www/html/bg.jpg
	cp /var/www/favico.jpeg /var/www/html/favico.jpeg
	cp /var/www/files.php /var/www/html/files.php
	cp /var/www/server_cert_san_ext.conf /var/www/html/server_cert_san_ext.conf
	cp /var/www/client_cert_san_ext.conf /var/www/html/client_cert_san_ext.conf
	cp -r /var/www/script /var/www/html/

fi
cp /tmp/src/index.php /var/www/html/index.php
cp /tmp/src/settings.php /var/www/html/settings.php
cp /tmp/src/style.css /var/www/html/style.css
cp /tmp/src/files.php /var/www/html/files.php
cp -R /tmp/bin /etc/
cp /tmp/src/bg.jpg /var/www/html/bg.jpg
rm -rf /etc/cron.daily/*
cp /tmp/src/script/checkcert /var/www/html/script/checkcert
cp /var/www/ca.txt /var/www/html/files/ca/ca.txt
cp /var/www/C.txt /var/www/html/files/ca/C.txt
cp /var/www/O.txt /var/www/html/files/ca/O.txt

chown -R www-data:www-data /var/www/html
cp /var/www/html/files/ca/ca.pem /var/www/html/files/ca.pem
chmod +x /var/www/html/script/*


usermod -aG mail www-data
chown root:mail /etc/ssmtp/ssmtp.conf
chmod 665 /etc/ssmtp/ssmtp.conf

cp /var/www/html/script/checkcert /etc/cron.daily
chown root:root /etc/cron.daily/checkcert
chmod +x /etc/cron.daily/checkcert

service cron start

exec apache2-foreground
