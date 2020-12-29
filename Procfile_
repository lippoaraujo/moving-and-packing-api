web: vendor/bin/heroku-php-nginx -C nginx_app.conf public/
release: php artisan module:migrate --force
release: php artisan scribe:generate
