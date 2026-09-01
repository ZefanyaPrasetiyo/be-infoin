FROM php:8.3-cli

# 1. Install dependencies sistem operasi yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Install ekstensi PHP inti (wajib untuk database MySQL dan manipulasi file)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 3. Ambil Composer langsung dari image resminya
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Set folder kerja di dalam container
WORKDIR /app

# 5. Copy seluruh kode Laravel dari laptop ke dalam container
COPY . .

# 6. Install semua library PHP via Composer
RUN composer install --no-interaction --optimize-autoloader

# 7. Berikan hak akses penuh agar Laravel bisa membuat file log dan cache
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Buka port 8000
EXPOSE 8000

# Nyalakan server bawaan Laravel (terbuka untuk semua IP via 0.0.0.0)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]