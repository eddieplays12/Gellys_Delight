# Gelly's Delight Demo Documentation

This document explains how to access, run, and maintain the current school demo setup.

## Public Links

Customer website:

```text
https://gellys-delight.vercel.app
```

Backend/admin base URL:

```text
https://households-resort-mario-wow.trycloudflare.com/gellys-laravel/public
```

Admin login:

```text
https://households-resort-mario-wow.trycloudflare.com/gellys-laravel/public/admin/login
```

GitHub repository:

```text
https://github.com/eddieplays12/Gellys_Delight
```

## What Each Part Does

Vercel frontend:

- Shows the customer website.
- Handles home, menu, cart, checkout, orders, login, and register pages.
- Calls the Laravel API through the Cloudflare Tunnel URL.

Laravel backend on the laptop:

- Stores products, users, orders, ratings, and admin data.
- Provides API routes for the frontend.
- Provides the admin dashboard.
- Uses the local XAMPP MySQL database.

Cloudflare Tunnel:

- Makes the laptop backend accessible online.
- Allows other devices to access the backend without router port forwarding.

## Required Apps Running On The Laptop

Keep these running while the demo is online:

- XAMPP Apache
- XAMPP MySQL
- Cloudflare Tunnel (`cloudflared.exe`)

If the laptop turns off, sleeps, loses internet, or the tunnel stops, the frontend may still open but products, login, checkout, admin, and API requests may stop working.

## Current Backend Setup

The Laravel backend is served by XAMPP Apache at:

```text
http://127.0.0.1/gellys-laravel/public
```

Cloudflare Tunnel forwards online traffic to:

```text
http://127.0.0.1
```

That is why the online backend URL includes:

```text
/gellys-laravel/public
```

## Starting The Tunnel

The downloaded tunnel app is stored at:

```text
C:\Users\User\Documents\Codex\2026-05-14\files-mentioned-by-the-user-gellys\cloudflared.exe
```

To start a quick tunnel manually:

```powershell
C:\Users\User\Documents\Codex\2026-05-14\files-mentioned-by-the-user-gellys\cloudflared.exe tunnel --url http://127.0.0.1
```

Cloudflare will print a public URL like:

```text
https://random-name.trycloudflare.com
```

If this URL changes, update Vercel and Laravel settings.

## Vercel Settings

Project:

```text
gellys-delight
```

Root Directory:

```text
frontend
```

Framework:

```text
Next.js
```

Environment variable:

```text
NEXT_PUBLIC_API_ORIGIN=https://households-resort-mario-wow.trycloudflare.com/gellys-laravel/public
```

If the Cloudflare Tunnel URL changes, replace the value above and redeploy the Vercel project.

## Laravel Environment Settings

The local `.env` file should point to the current tunnel URL:

```text
APP_ENV=production
APP_DEBUG=false
APP_URL=https://households-resort-mario-wow.trycloudflare.com/gellys-laravel/public
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://127.0.0.1:3000,https://gellys-delight.vercel.app
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none
```

After changing `.env`, run:

```powershell
cd C:\xampp\htdocs\gellys-laravel
php artisan config:clear
php artisan cache:clear
```

## Admin Access

Admin login page:

```text
https://households-resort-mario-wow.trycloudflare.com/gellys-laravel/public/admin/login
```

Admin ID:

```text
admin
```

Use the demo password provided by the project owner. Do not commit real passwords to GitHub.

## Testing Checklist

Before presenting:

1. Open `https://gellys-delight.vercel.app`.
2. Open the Menu page and confirm products load.
3. Register or log in as a customer.
4. Add an item to cart.
5. Submit a checkout order.
6. Open admin login.
7. Log in as admin.
8. Check products, orders, and ratings pages.

## Common Problems

Products do not load:

- Check if XAMPP Apache and MySQL are running.
- Check if `cloudflared.exe` is still running.
- Check if the Vercel `NEXT_PUBLIC_API_ORIGIN` still matches the current tunnel URL.

Admin login shows `419 PAGE EXPIRED`:

- Refresh the page.
- Try incognito/private browsing.
- Run `php artisan config:clear`.
- Make sure `APP_URL` uses `https://` and the current tunnel URL.

Frontend opens but checkout/login fails:

- Backend is probably offline or the tunnel URL changed.
- Restart Cloudflare Tunnel and update Vercel if the URL changed.

Tunnel URL changed:

1. Copy the new `trycloudflare.com` URL.
2. Add `/gellys-laravel/public` to the end.
3. Update `NEXT_PUBLIC_API_ORIGIN` in Vercel.
4. Update `APP_URL` in Laravel `.env`.
5. Run `php artisan config:clear`.
6. Redeploy Vercel.

