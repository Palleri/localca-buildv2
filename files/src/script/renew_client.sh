#!/bin/bash               
cp /var/www/html/client_cert_san_ext.conf /var/www/html/$1.san.ext
echo "DNS.1 = $1" >> /var/www/html/$1.san.ext
openssl req -new -key /var/www/html/files/$1/$1.key -out /var/www/html/files/$1/$1.new.csr -subj /CN=$1
openssl x509 -req -in /var/www/html/files/$1/$1.new.csr -CA /var/www/html/files/ca/ca.pem -CAkey /var/www/html/files/ca/ca.key -CAcreateserial -out /var/www/html/files/$1/$1.crt -days 365 -sha256 -passin pass:$2 -extfile /var/www/html/$1.san.ext
rm -f /var/www/html/$1.san.ext
openssl pkcs12 -export -out /var/www/html/files/$1/$1.p12 -in /var/www/html/files/$1/$1.crt -inkey /var/www/html/files/$1/$1.key -passout pass:$3

               
               
       