#!/bin/bash

# Mailtrap Quick Setup Script
# Run this after getting your Mailtrap credentials

echo "📧 Mailtrap Configuration Helper"
echo "================================="
echo ""
echo "Please enter your Mailtrap credentials:"
echo ""

read -p "Mailtrap Username: " MAILTRAP_USERNAME
read -p "Mailtrap Password: " MAILTRAP_PASSWORD

echo ""
echo "Updating .env file..."

# Backup current .env
cp .env .env.backup

# Update mail configuration
sed -i.bak "s/MAIL_MAILER=.*/MAIL_MAILER=smtp/" .env
sed -i.bak "s/MAIL_HOST=.*/MAIL_HOST=sandbox.smtp.mailtrap.io/" .env
sed -i.bak "s/MAIL_PORT=.*/MAIL_PORT=2525/" .env
sed -i.bak "s/MAIL_USERNAME=.*/MAIL_USERNAME=${MAILTRAP_USERNAME}/" .env
sed -i.bak "s/MAIL_PASSWORD=.*/MAIL_PASSWORD=${MAILTRAP_PASSWORD}/" .env

# Add encryption if not exists
if ! grep -q "MAIL_ENCRYPTION" .env; then
    echo "MAIL_ENCRYPTION=tls" >> .env
fi

# Update from address
sed -i.bak "s/MAIL_FROM_ADDRESS=.*/MAIL_FROM_ADDRESS=\"noreply@adminpanel.com\"/" .env
sed -i.bak "s/MAIL_FROM_NAME=.*/MAIL_FROM_NAME=\"Admin Panel\"/" .env

# Clear config cache
php artisan config:clear
php artisan cache:clear

echo ""
echo "✅ Configuration updated!"
echo ""
echo "Next steps:"
echo "1. Go to http://127.0.0.1:8000/admin/register"
echo "2. Register a new admin"
echo "3. Check your Mailtrap inbox at https://mailtrap.io"
echo ""
echo "To test email immediately, run:"
echo "php artisan tinker --execute=\"Mail::raw('Test', function(\$m) { \$m->to('test@example.com')->subject('Test'); });\""
echo ""
