FROM php:8.1-fpm
#FROM php:8.3-fpm

# Instalar dependencias del sistema necesarias para las extensiones de PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libicu-dev \
    libzip-dev \
    libgd-dev \
    curl \
    gnupg

# Instalar las extensiones de PHP necesarias para Laravel
# pdo_mysql es la clave para la conexión a la base de datos
RUN docker-php-ext-install -j$(nproc) pdo_mysql bcmath gd intl zip

# Install Node.js and npm
ENV NODE_VERSION 20.x
RUN curl -fsSL https://deb.nodesource.com/setup_$NODE_VERSION | bash - \
    && apt-get update && apt-get install -y nodejs \
    && npm install -g npm@latest

# Verify installation
RUN node -v
RUN npm -v

# Limpiar caché de apt para reducir el tamaño de la imagen
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Exponer puerto
EXPOSE 9000
CMD ["php-fpm"]

