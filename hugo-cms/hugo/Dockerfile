FROM ubuntu:16.04

RUN apt-get update \
    && apt-get install -y apache2 php libapache2-mod-php php-mcrypt php-mysql php-gd php-curl php-zip python-letsencrypt-apache php-xml \
    && rm -rf /var/lib/apt/lists/*

COPY upload /var/www/html
COPY init.sh /opt/opencart/
RUN chmod +x /opt/opencart/init.sh
WORKDIR /var/www/html/

#COPY apache/000-default-le-ssl.conf /etc/apache2/sites-available/000-default-le-ssl.conf
#RUN a2ensite 000-default-le-ssl.conf
#RUN cp -arp /etc/apache2/mods-available/headers.load  /etc/apache2/mods-enabled/headers.load
#RUN a2enmod rewrite

CMD /usr/sbin/apache2ctl -D FOREGROUND
