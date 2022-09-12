#!/bin/sh



#cd /var/www/html/files/
for i in $(find /var/www/html/files/ -type f -name \*.crt);
do
     
    CertExpires=`openssl x509 -in $i -inform PEM -text -noout -enddate | grep "Not After" | awk '{print $4, $5, $7}'`

TodayPlus30=`date -ud "+30 day" | awk '{print $2, $3, $6}'`

if [ "$CertExpires" = "$TodayPlus30" ]
then
echo "Your SSL Cert will expire in 30 days." 
fi


done




for i in $(find /var/www/html/files/ -type f -name \*.p12);
do
     
    CertExpires=`openssl x509 -in $i -inform pkcs12 -text -noout -enddate | grep "Not After" | awk '{print $4, $5, $7}'`

TodayPlus30=`date -ud "+30 day" | awk '{print $2, $3, $6}'`

if [ "$CertExpires" = "$TodayPlus30" ]
then
echo "Your SSL Cert will expire in 30 days." 
fi


done