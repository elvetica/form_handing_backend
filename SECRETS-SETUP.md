# GitHub Secrets Configuration

Go to your GitHub repository → Settings → Secrets and variables → Actions

Add these repository secrets:

## Required Secrets

1. **DROPLET_HOST**

   - Value: Your droplet's IP address (e.g., 192.168.1.100)

2. **DROPLET_USER**

   - Value: `deployer`

3. **DROPLET_SSH_KEY**

   - Value: Your private SSH key (entire contents of ~/.ssh/id_rsa)
   - Generate with: `ssh-keygen -t rsa -b 4096 -C "your-email@example.com"`

4. **DROPLET_PATH**
   - Value: `/var/www/laravel-app`

## Optional Secrets (for enhanced security)

5. **DB_PASSWORD**

   - Value: Your database password

6. **APP_KEY**
   - Value: Your Laravel application key
