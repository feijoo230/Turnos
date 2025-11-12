FROM gitlab.salta.gob.ar:8383/images/simple-php-apache-dev:5.6.30
COPY . /opt/app/
RUN rm -r /var/www/html && ln -s /opt/app /var/www/html

# Install Node.js and npm
ENV NODE_VERSION 16.x
RUN apt-get update && apt-get install -y \
    curl \
    gnupg \
    && rm -rf /var/lib/apt/lists/* \
    && curl -fsSL https://deb.nodesource.com/setup_$NODE_VERSION | bash - \
    && apt-get update && apt-get install -y nodejs \
    && npm install -g npm@latest

# Verify installation
RUN node -v
RUN npm -v
