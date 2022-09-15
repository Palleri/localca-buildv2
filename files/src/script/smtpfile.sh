#!/bin/bash
  rm /var/www/html/script/certwarning.txt
  touch /var/www/html/script/certwarning.txt
  rm /var/www/html/script/testmail.txt
  touch /var/www/html/script/testmail.txt

  echo $1 >> /var/www/html/script/certwarning.txt
  echo $1 >> /var/www/html/script/testmail.txt
  
  echo "Subject: SSL expire
One of your SSL certificate will expire in 30 days" >> /var/www/html/script/certwarning.txt
  echo "Subject: SSL warning test" >> /var/www/html/script/testmail.txt
