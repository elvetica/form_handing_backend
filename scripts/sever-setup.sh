#!/bin/bash

# Laravel 12 Server Setup Script for Ubuntu 25.04
# Run this ONCE on your new droplet as root

set -e

echo "🚀 Setting up Ubuntu 25.04 for Laravel 12..."

# Update system
apt update && apt upgrade -y

# Install required packages
apt install -y software-properties-common curl wget git unzip

# Add PHP repository (Laravel 12 requires PHP 8.2+)
add-apt-repository ppa:ondrej/php -y
apt update

# Install PHP 8.3 and required extensions
apt install -y php8.3 php8.3-fpm php8.3-mysql php8.3-pgsql php8.3-sqlite3 \
    php8.3-redis php8.3-xml php8.3-mbstring php8.3-curl php8.3-zip \
    php8.3-bcmath php8.3-gd php8.3-intl php8.3-soap php8.3-xsl \
    php8.3-cli php8.3-common php8.3-opcache

# Install Nginx
apt install -y nginx

# Install MySQL
apt install -y mysql-server

# Install Node.js (for asset compilation)
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

# Install Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create deployer user
useradd -m -s /bin/bash deployer
usermod -aG www-data deployer
usermod -aG sudo deployer

# Create deployment directory
mkdir -p /var/www/laravel-app
chown -R deployer:www-data /var/www/laravel-app
chmod -R 755 /var/www/laravel-app

# Setup SSH for deployer user
mkdir -p /home/deployer/.ssh
cp /root/.ssh/authorized_keys /home/deployer/.ssh/ 2>/dev/null || echo "No authorized_keys found"
chown -R deployer:deployer /home/deployer/.ssh
chmod 700 /home/deployer/.ssh
chmod 600 /home/deployer/.ssh/authorized_keys 2>/dev/null || true

# Configure Nginx
cat > /etc/nginx/sites-available/laravel-app << 'EOL'
server {
    listen 80;
    listen [::]:80;
    server_name _;
    root /var/www/laravel-app/current/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOL

# Enable site
ln -sf /etc/nginx/sites-available/laravel-app /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test and reload Nginx
nginx -t && systemctl reload nginx

# Configure PHP-FPM
sed -i 's/user = www-data/user = deployer/g' /etc/php/8.3/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = www-data/g' /etc/php/8.3/fpm/pool.d/www.conf
systemctl restart php8.3-fpm

# Enable services
systemctl enable nginx php8.3-fpm mysql

# Setup MySQL (basic security)
mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'your_secure_password';"
mysql -e "DELETE FROM mysql.user WHERE User='';"
mysql -e "DROP DATABASE IF EXISTS test;"
mysql -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';"
mysql -e "FLUSH PRIVILEGES;"

# Create database for Laravel
mysql -u root -pyour_secure_password -e "CREATE DATABASE laravel_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -pyour_secure_password -e "CREATE USER 'laravel'@'localhost' IDENTIFIED BY 'laravel_password';"
mysql -u root -pyour_secure_password -e "GRANT ALL PRIVILEGES ON laravel_app.* TO 'laravel'@'localhost';"
mysql -u root -pyour_secure_password -e "FLUSH PRIVILEGES;"

# Setup UFW firewall
ufw allow ssh
ufw allow 'Nginx Full'
ufw --force enable

echo "✅ Server setup complete!"
echo "📝 Next steps:"
echo "1. Update your GitHub secrets with:"
echo "   - DROPLET_HOST: $(curl -s http://checkip.amazonaws.com)"
echo "   - DROPLET_USER: deployer"
echo "   - DROPLET_PATH: /var/www/laravel-app"
echo "2. Add your SSH public key to /home/deployer/.ssh/authorized_keys"
echo "3. Update database credentials in your .env file"