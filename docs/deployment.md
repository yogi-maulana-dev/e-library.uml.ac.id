# Deployment E-Library UML

## VPS Checklist

1. Install PHP 8.3+, Composer, MySQL, Redis, Node.js LTS, Nginx atau Apache.
2. Set document root ke folder `public`.
3. Salin `.env.example` ke `.env`, lalu isi `APP_URL`, database, mail, dan Redis.
4. Jalankan:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan security:integrity generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
```

## Security Hardening

Set production `.env`:

```env
APP_ENV=production
APP_DEBUG=false
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
SECURITY_INTEGRITY_ENABLED=true
SECURITY_INTEGRITY_FAIL_CLOSED=true
```

After every authorized deployment, regenerate the integrity manifest:

```bash
php artisan security:integrity generate
php artisan security:integrity check
```

If `routes/web.php`, auth routes, bootstrap, security middleware, or critical config files are changed without regenerating the manifest, the app will fail closed with HTTP 503 and log a critical alert. This protects against silent route tampering.

Use filesystem permissions so the web server user can write only to `storage` and `bootstrap/cache`; application code should be read-only:

```bash
sudo chown -R deploy:www-data /var/www/e-library.uml.ac.id
sudo find /var/www/e-library.uml.ac.id -type f -exec chmod 0644 {} \;
sudo find /var/www/e-library.uml.ac.id -type d -exec chmod 0755 {} \;
sudo chgrp -R www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
sudo chmod 0444 routes/web.php routes/auth.php bootstrap/app.php
```

For extra protection, deploy with route cache enabled. With `php artisan route:cache`, route file changes will not affect runtime until an authorized deploy clears and rebuilds cache.

## Windows / Laragon Hardening

For Laragon or Windows hosting, run PowerShell as Administrator:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\harden-windows.ps1
```

The script:

- regenerates and checks file integrity hashes
- caches routes/config/views
- marks critical route/bootstrap/security files as read-only
- applies read-only ACL for the current Windows user and the web user group

If you need to deploy authorized changes later, remove the read-only attribute first, patch the files, then rerun the hardening script.

## Nginx

```nginx
server {
    listen 80;
    server_name e-library.uml.ac.id;
    root /var/www/e-library.uml.ac.id/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Apache

```apache
<VirtualHost *:80>
    ServerName e-library.uml.ac.id
    DocumentRoot /var/www/e-library.uml.ac.id/public

    <Directory /var/www/e-library.uml.ac.id/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/elibrary-error.log
    CustomLog ${APACHE_LOG_DIR}/elibrary-access.log combined
</VirtualHost>
```

## HTTPS

```bash
sudo certbot --nginx -d e-library.uml.ac.id
```

Untuk Apache gunakan:

```bash
sudo certbot --apache -d e-library.uml.ac.id
```
