#!/bin/bash



if [ -z "$cakey"  -a -z "$cakey_FILE" ];  then
        echo >&2 'error: No cakey is specified '
        echo >&2 '  You need to specify one of cakey or cakey_FILE'
        exit 1
fi
if [ ! -z "$cakey_FILE" -a -z "$cakey" ]; then
  cakey=$(cat $cakey_FILE)
fi

echo $ca
echo $C
echo $O
if [ -d "/var/www/html/files/ca" ] 
then
    echo "Found CA." 
else
	echo "First time install"
	mkdir /var/www/html/files/
	mkdir /var/www/html/files/ca
	echo -n $ca >> /var/www/html/files/ca/CA_NAME.txt
	echo -n $cakey >> /var/www/html/files/ca/CA_KEY.txt
	openssl genrsa -des3 -out /var/www/html/files/ca/ca.key -passout pass:$cakey 2048
	sleep 5s
	openssl req -x509 -new -nodes -key /var/www/html/files/ca/ca.key -sha256 -days 1825 -out /var/www/html/files/ca/ca.pem -passin pass:$cakey     -subj "/CN=${ca}/C=${C}/O=${O}"


fi


cp /var/www/index.php /var/www/html/index.php
cp /var/www/style.css /var/www/html/style.css
cp /var/www/bg.jpg /var/www/html/bg.jpg
cp /var/www/files.php /var/www/html/files.php
cp /var/www/server_cert_san_ext.conf /var/www/html/server_cert_san_ext.conf
cp /var/www/client_cert_san_ext.conf /var/www/html/client_cert_san_ext.conf
chown -R www-data:www-data /var/www/html
cp /var/www/html/files/ca/ca.pem /var/www/html/files/ca.pem

apache2-foreground
