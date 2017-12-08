#!/bin/bash
#sed -i -e "s/DOMAIN_IP/${DOMAIN_IP}/g" /var/www/html/config.php
#sed -i -e "s/DOMAIN_IP/${DOMAIN_IP}/g" /var/www/html/admin/config.php

#sed -i -e "s/MYSQL_ROOT_PASSWORD/${MYSQL_ROOT_PASSWORD}/g" /var/www/html/config.php
#sed -i -e "s/MYSQL_ROOT_PASSWORD/${MYSQL_ROOT_PASSWORD}/g" /var/www/html/admin/config.php

#chmod 0777 -R /var/www/html/system/storage/cache/
#chmod 0777 -R /var/www/html/system/storage/logs/
#chmod 0755 /var/www/html/system/storage/download/
#chmod 0755 /var/www/html/system/storage/upload/
#chmod 0777 -R /var/www/html/system/storage/modification/
#chmod 0777 /var/www/html/system/library/log.php
#chmod 0755 /var/www/html/config.php
#chmod 0755 /var/www/html/admin/config.php
#chmod 0777 -R /var/www/html/image/
#chmod 0777 -R /var/www/html/vqmod/

#a2enmod ssl
service apache2 reload

rm /var/www/html/index.html
