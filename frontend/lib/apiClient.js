const configuredApiOrigin =
    process.env.NEXT_PUBLIC_API_ORIGIN || "http://127.0.0.1:8000";

export const API_ORIGIN = configuredApiOrigin.replace(/\/+$/, "");
export const API_BASE_URL = `${API_ORIGIN}/api`;

function getCookie(name) {
    if (typeof document === "undefined") {
        return "";
    }

    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);

    if (parts.length === 2) {
        return decodeURIComponent(parts.pop().split(";").shift());
    }

    return "";
}

async function ensureCsrfCookie() {
    await fetch(`${API_ORIGIN}/sanctum/csrf-cookie`, {
        credentials: "include",
    });
}

export async function apiFetch(path, options = {}) {
    const method = (options.method || "GET").toUpperCase();
    const headers = {
        Accept: "application/json",
        ...(options.headers || {}),
    };

    if (!["GET", "HEAD", "OPTIONS"].includes(method)) {
        await ensureCsrfCookie();
        headers["X-XSRF-TOKEN"] = getCookie("XSRF-TOKEN");
    }

    return fetch(`${API_BASE_URL}${path}`, {
        ...options,
        method,
        credentials: "include",
        headers,
    });
}
