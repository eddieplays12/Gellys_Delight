# Admin Cookie Authentication

The admin API uses Laravel session cookies. This means the browser keeps the admin login state in a cookie instead of sending an `Authorization: Bearer ...` token.

## Protected Routes

These routes require an authenticated admin session:

- `GET /api/admin/products`
- `POST /api/admin/products`
- `PUT /api/admin/products/{productId}`
- `DELETE /api/admin/products/{productId}`
- `GET /api/admin/orders`
- `GET /api/admin/ratings`
- `POST /api/admin/logout`

If the admin is not logged in, these routes return:

```json
{
  "message": "Unauthenticated admin."
}
```

with HTTP status `401`.

## How It Works

1. The frontend first requests Laravel's CSRF cookie.
2. The frontend logs in through `/api/admin/login`.
3. Laravel validates the admin credentials.
4. Laravel stores `admin_id` and `admin_name` in the server-side session.
5. The browser stores Laravel's session cookie.
6. Future `/api/admin/*` requests include the cookie automatically when `credentials: "include"` is used.
7. The `admin.auth` middleware checks the session before allowing the request.

## Frontend Example

```js
const API_URL = "http://127.0.0.1:8000";

await fetch(`${API_URL}/sanctum/csrf-cookie`, {
  credentials: "include",
});

await fetch(`${API_URL}/api/admin/login`, {
  method: "POST",
  credentials: "include",
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
  body: JSON.stringify({
    admin_id: "admin",
    password: "password",
  }),
});

const ordersResponse = await fetch(`${API_URL}/api/admin/orders`, {
  credentials: "include",
  headers: {
    Accept: "application/json",
  },
});

const orders = await ordersResponse.json();
```

## Important Notes

- Do not use `Authorization: Bearer ...` for these admin API routes.
- Use `credentials: "include"` on login and protected admin API requests.
- For `POST`, `PUT`, and `DELETE` requests, CSRF protection applies because these routes use Laravel's `web` middleware.
- In production, set the correct `APP_URL`, CORS allowed origins, session domain, and HTTPS settings.
