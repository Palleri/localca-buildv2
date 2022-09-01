## Create SAN
cp /var/www/html/client_cert_san_ext.conf /var/www/html/$1.san.ext
echo "DNS.1 = $1" >> /var/www/html/$1.san.ext
## Create CSR and sign with CA
mkdir /var/www/html/files/$1
echo $2 >> /var/www/html/files/$1/$1.pw
openssl genrsa -out /var/www/html/files/$1/$1.key 2048
openssl req -new -key /var/www/html/files/$1/$1.key -out /var/www/html/files/$1/$1.csr -subj /CN=$1
openssl x509 -req -in /var/www/html/files/$1/$1.csr -CA /var/www/html/files/ca/ca.pem -CAkey /var/www/html/files/ca/ca.key -CAcreateserial -out /var/www/html/files/$1/$1.crt -days 365 -sha256 -passin pass:$3 -extfile /var/www/html/$1.san.ext
sleep 2

# #Convert .pem files to .p12
openssl pkcs12 -export -out /var/www/html/files/$1/$1.p12 -in /var/www/html/files/$1/$1.crt -inkey /var/www/html/files/$1/$1.key -passout pass:$2
sleep 2                    
                    # Change permission on created files
chmod 666 /var/www/html/files/$1/$1.p12
chmod 666 /var/www/html/files/$1/$1.key
chmod 666 /var/www/html/files/$1/$1.csr
chmod 666 /var/www/html/files/$1/$1.crt
rm -f /var/www/html/$1.san.ext