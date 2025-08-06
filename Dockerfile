FROM php:8.2-fpm

# 설치 패키지
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev supervisor \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composer 설치
COPY --from=composer:latest  /usr/bin/composer /usr/bin/composer
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Redis 확장 설치 (선택사항)
RUN pecl install redis && docker-php-ext-enable redis

# Laravel 설치 스크립트
WORKDIR /var/www/html
# RUN composer create-project --prefer-dist laravel/laravel .

# ✅ 로컬 app 폴더를 이미지에 포함
COPY ./app /var/www/html

# Supervisord 설정 파일 복사
COPY ./config/supervisord/supervisord_dev.conf /etc/supervisord.conf
# COPY ./config/supervisord/supervisord_prod.conf /etc/supervisord.conf
COPY ./config/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf

# 필요한 패키지 설치
RUN composer install --no-dev --optimize-autoloader

# Sanctum 설치 (추가)
# RUN composer require laravel/sanctum

# 작업 디렉토리
# WORKDIR /var/www/html

# 권한 설정
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Storage 및 Cache 권한 설정
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# supervisord를 기본 실행 명령어로 설정
CMD ["supervisord", "-c", "/etc/supervisord.conf"]