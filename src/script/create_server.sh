#!/bin/bash
cp /var/www/html/server_cert_san_ext.conf /var/www/html/$1.san.ext
echo "DNS.1 = $1" >> /var/www/html/$1.san.ext
mkdir /var/www/html/files/$1
openssl genrsa -out /var/www/html/files/$1/$1.key 2048
openssl req -new -key /var/www/html/files/$1/$1.key -out /var/www/html/files/$1/$1.csr -subj /CN=$1
openssl x509 -req -in /var/www/html/files/$1/$1.csr -CA /var/www/html/files/ca/ca.pem -CAkey /var/www/html/files/ca/ca.key -CAcreateserial -out /var/www/html/files/$1/$1.crt -days 365 -sha256 -passin pass:$2 -extfile /var/www/html/$1.san.ext
sleep 2   	
chmod 666 /var/www/html/files/$1/$1.csr
chmod 666 /var/www/html/files/$1/$1.crt
chmod 666 /var/www/html/files/$1/$1.key
rm -f /var/www/html/$1.san.ext