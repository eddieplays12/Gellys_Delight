# Student Online Guide

This setup is good for a school demo or short-term online access.

## What Will Be Online

- Frontend: Vercel
- Backend/API: your laptop through a temporary Cloudflare Tunnel URL

Users can open the Vercel site from any device, but your laptop must stay on and connected to the internet.

## Keep These Running

Open one terminal for Laravel:

```powershell
cd C:\xampp\htdocs\gellys-laravel
php artisan serve --host=127.0.0.1 --port=8000
```

Open another terminal for Cloudflare Tunnel:

```powershell
cloudflared tunnel --url http://127.0.0.1:8000
```

Cloudflare will print a temporary URL like:

```text
https://random-name.trycloudflare.com
```

Use that URL in Vercel:

```text
NEXT_PUBLIC_API_ORIGIN=https://random-name.trycloudflare.com
```

After changing the Vercel environment variable, redeploy the frontend.

## Important Limits

- If the laptop sleeps, shuts down, or loses internet, the backend goes offline.
- If the Cloudflare Tunnel is restarted, the temporary URL can change.
- If the URL changes, update `NEXT_PUBLIC_API_ORIGIN` in Vercel and redeploy.
- Keep the Laravel and Cloudflare Tunnel terminals open while users are testing.

## Laptop Settings For Multi-Day Demo

- Plug in the charger.
- Disable sleep mode.
- Keep Wi-Fi stable.
- Do not close the terminal windows.
- Do not restart the laptop while the demo is active.

## Local Check Before Going Online

Run:

```powershell
cd C:\xampp\htdocs\gellys-laravel
php artisan test
```

Then:

```powershell
cd C:\xampp\htdocs\gellys-laravel\frontend
npm.cmd run build
```

Both should pass before going online.
