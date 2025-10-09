# Mailtrap Setup Guide

## What is Mailtrap?

Mailtrap is a fake SMTP server for development. It captures all outgoing emails so you can:

-   See exactly what your emails look like
-   Test email functionality without sending real emails
-   Preview emails on different devices/clients
-   Check spam score
-   View HTML and text versions

## Step-by-Step Setup

### 1. Create a Mailtrap Account

1. Go to https://mailtrap.io/register/signup
2. Sign up for a FREE account (no credit card required)
3. Verify your email address

### 2. Get Your SMTP Credentials

1. After logging in, you'll see an inbox (usually called "My Inbox")
2. Click on the inbox
3. Go to **SMTP Settings** tab
4. In the **Integrations** dropdown, select **"Laravel 9+"**
5. You'll see credentials like this:

```php
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=1a2b3c4d5e6f7g    // Your username
MAIL_PASSWORD=1a2b3c4d5e6f7g    // Your password
MAIL_ENCRYPTION=tls
```

### 3. Update Your `.env` File

Open `/Users/paul/Repositories/elvetica/library/backend/form_handling_next/.env`

**Replace these lines:**

```env
MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**With your Mailtrap credentials:**

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username_here
MAIL_PASSWORD=your_mailtrap_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourapp.com"
MAIL_FROM_NAME="Admin Panel"
```

### 4. Clear Config Cache

After updating `.env`, run:

```bash
php artisan config:clear
```

### 5. Test the Email Verification

1. **Register a new admin:**

    - Go to http://127.0.0.1:8000/admin/register
    - Fill in the form
    - Click Register

2. **Check Mailtrap:**

    - Go to your Mailtrap inbox
    - You should see the verification email appear in seconds!
    - Click on it to see the beautiful rendered email

3. **Click the Verify Link:**

    - In Mailtrap, click "Check HTML" or "View"
    - Click the verification link in the email
    - It will open in your browser and verify your email!

4. **Login:**
    - Go to http://127.0.0.1:8000/admin/login
    - Login with your new credentials
    - Access the dashboard!

## What You'll See in Mailtrap

### Email Details:

-   **Subject:** "Verify Email Address"
-   **From:** Admin Panel <noreply@yourapp.com>
-   **To:** The email you registered with
-   **HTML Preview:** Beautiful formatted email
-   **Text Preview:** Plain text version
-   **Raw:** See the actual email source

### Features You Can Use:

-   📱 **Preview on different devices** (iPhone, Android, etc.)
-   📧 **Check spam score** (make sure your emails won't go to spam)
-   🔍 **Inspect HTML/CSS** (see if rendering is correct)
-   📋 **Forward to real email** (test in real inbox)
-   💾 **Share with team** (get feedback on email design)

## Troubleshooting

### Email Not Appearing in Mailtrap?

**Check config cache:**

```bash
php artisan config:clear
php artisan cache:clear
```

**Verify credentials:**

```bash
php artisan tinker
>>> config('mail.mailers.smtp')
```

Should show:

```
[
  "transport" => "smtp",
  "host" => "sandbox.smtp.mailtrap.io",
  "port" => 2525,
  "username" => "your_username",
  "password" => "your_password",
  ...
]
```

### Still Not Working?

**Test mail directly:**

```bash
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

Check Mailtrap inbox - you should see this test email.

### Connection Refused Error?

Make sure:

1. Your Mailtrap credentials are correct
2. Port 2525 is not blocked by firewall
3. You've cleared the config cache

## Next Steps

Once Mailtrap is working:

1. **Customize the Email Template**

    - Laravel's default verification email is plain
    - You can publish and customize it:
        ```bash
        php artisan vendor:publish --tag=laravel-mail
        ```

2. **Add Your Logo/Branding**

    - Edit `resources/views/vendor/mail/html/header.blade.php`
    - Add your logo URL in `config/mail.php`

3. **Test Different Scenarios**
    - Multiple registrations
    - Resend verification
    - Expired links
    - Already verified

## Free Tier Limits

Mailtrap Free Account includes:

-   ✅ 500 emails/month
-   ✅ 1 inbox
-   ✅ Unlimited team members
-   ✅ Email history (1 month)
-   ✅ All preview features

Perfect for development and testing!

## When to Switch to Real SMTP

Switch from Mailtrap to real email service when:

-   Deploying to production
-   Need to send to real email addresses
-   Need higher volume (>500/month)

Popular production services:

-   **SendGrid** (100 emails/day free)
-   **Mailgun** (5,000 emails/month free)
-   **Amazon SES** (Pay as you go)
-   **Postmark** (100 emails/month free)

---

## Quick Reference

**Current Setup (Logging):**

-   Emails go to: `storage/logs/laravel.log`
-   Good for: Quick debugging
-   Bad for: Seeing actual email appearance

**With Mailtrap:**

-   Emails go to: Mailtrap inbox (web interface)
-   Good for: Testing email appearance, functionality
-   Bad for: Testing with real email clients

**Production:**

-   Emails go to: Real inboxes
-   Good for: Actual users
-   Bad for: Testing (costs money, emails are permanent)
