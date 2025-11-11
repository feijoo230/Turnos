FROM gitlab.salta.gob.ar:8383/images/simple-php-apache-dev:5.6.30
COPY . /opt/app/
RUN rm -r /var/www/html && ln -s /opt/app /var/www/html
