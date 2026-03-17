FROM php:8.2-apache

# ── Zależności systemowe ───────────────────────────────────────
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libssl-dev \
    pkg-config \
    libcurl4-openssl-dev \
    zip \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# ── Rozszerzenia PHP ───────────────────────────────────────────
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mysqli \
        gd \
        curl

# ── MongoDB extension (przez PECL) ────────────────────────────
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# ── Composer ──────────────────────────────────────────────────
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ── Apache mod_rewrite ────────────────────────────────────────
RUN a2enmod rewrite

# ── Konfiguracja Apache ───────────────────────────────────────
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html|' /etc/apache2/sites-available/000-default.conf \
    && sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

WORKDIR /var/www/html

EXPOSE 80