#!/bin/bash


if [[ -v $2 ]];
then
  > /etc/ssmtp/ssmtp.conf
  
  echo hostname=localca >> /etc/ssmtp/ssmtp.conf
  echo FromLineOverride=YES >> /etc/ssmtp/ssmtp.conf
  echo $1 >> /etc/ssmtp/ssmtp.conf
  cp /etc/ssmtp/ssmtp.conf /var/www/html/ssmtp.conf
else
  echo $1 >> /etc/ssmtp/ssmtp.conf
  cp /etc/ssmtp/ssmtp.conf /var/www/html/ssmtp.conf
fi



