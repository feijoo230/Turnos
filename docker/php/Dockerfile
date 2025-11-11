FROM php:8.0-fpm
#FROM php:8.3-fpm

# Instalar dependencias necesarias y habilitar la extensión pdo_mysql
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    libicu-dev \
    libxslt1-dev \
    #mysql-client \
    && docker-php-ext-install -j$(nproc) \
    bcmath \
    json \
    ctype \
    curl \
    dom \
    fileinfo \
    filter \
    hash \
    mbstring \
    openssl \
    pcre \
    pdo \
    session \
    tokenizer \
    xml \
    pdo_mysql \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get clean \
    && docker-php-ext-install pdo pdo_mysql \
    || tail -n 50 /var/log/apt/history.log

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de Laravel (o se montará con docker-compose)
COPY . .

# 🔥 Crear manualmente las carpetas antes de dar permisos
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache

# Ajustar permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer puerto
EXPOSE 9000
CMD ["php-fpm"]
