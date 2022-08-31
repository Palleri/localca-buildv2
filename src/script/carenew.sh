#!/bin/bash
openssl x509 -x509toreq -in /var/www/html/files/ca/ca.pem -signkey /var/www/html/files/ca/ca.key -out /var/www/html/files/ca/new-ca.csr -passin pass:$1
openssl x509 -req -days 1095 -in /var/www/html/files/ca/new-ca.csr -signkey /var/www/html/files/ca/ca.key -out /var/www/html/files/ca/ca-new.pem -passin pass:$1
cp /var/www/html/files/ca/ca-new.pem /var/www/html/files/ca.pem
cp /var/www/html/files/ca/ca-new.pem /var/www/html/files/ca/ca.pem
sleep 2

