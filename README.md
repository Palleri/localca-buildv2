
<img alt="logo" src="https://i.imgur.com/JwNEO4r.png">

-----------------------------------------------------------------------
# ONLY FOR INTERNAL ACCESS
<b>DO NOT PUBLISH ON THE INTERNET.</b>

<b>DO NOT USE FOR PRODUCTION.</b>

<b>NOT CHECKED FOR BUGS OR VULNERABILITIES.</b>


-----------------------------------------------------------------------
## Docker image

-----------------------------------------------------------------------
### Bugs and fixes

1. <s>Cronjob not working properly</s> <b>(Fixed 2023-01-27)</b>
-----------------------------------------------------------------------

### Aslong as the ca.pem remains in /var/www/html/files/ca the container will not install a new one
### To reinstall CA, delete folder ./data/files/ca

-----------------------------------------------------------------------

Create a file or a secret with your cakey and mount it to the container.
Use ENV:
```yml
environment:
  - cakey_FILE=/run/secrets/cakey
```
Or
```yml
environment:
  - cakey=supersecretpassword
```

-----------------------------------------------------------------------

### docker-compose.yml
```yml
version: '3.2'
secrets:
  cakey:
    file: ./cakey.txt
services:
  app:
    container_name: localca
    hostname: ca
    domainname: local.local
    secrets:
      - cakey
    image: 'palleri/localca:latest'
    restart: unless-stopped
    ports:
      - '80:80'
    volumes:
      - ./data:/var/www/html
    environment:
      - ca=ca.homelab.local # Name of the CA including your domain (FQDN)
      - cakey_FILE=/run/secrets/cakey
      - O=localca
      - C=SE # Country code
```

### DNS settings for docker-compose.
```
services:
  app:
  ....
    dns:
      - 192.168.1.1
  ....
```

### ca = Name of the ROOTCA
### cakey = privatekey (Use docker secrets instead of plain-text inside docker-compose.yml)

---------------------
Access webgui: http://x.x.x.x/index.php
---------------------

Convert to .p12:
This create certificate with client authentication attributes instead of server authentication.

---------------------

For more security:
Create your client certificate .p12 and add it to your browser.
Install ca.pem in your browser and nginx and activate ssl_verify_client on; 

NGINX proxy manager conf
```
ssl_client_certificate /etc/ssl/certs/ca.pem;
ssl_verify_client on;

```


<a href="https://github.com/Palleri/localca-buildv2">Github Palleri/LocalCA</a>



