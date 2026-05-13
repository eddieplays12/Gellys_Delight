export const USER_STORAGE_KEY = "gellys_user";
export const CART_STORAGE_KEY = "gellys_cart";

export function loadStoredUser() {
    if (typeof window === "undefined") {
        return null;
    }

    const savedUser = localStorage.getItem(USER_STORAGE_KEY);
    return savedUser ? JSON.parse(savedUser) : null;
}

export function saveStoredUser(user) {
    if (typeof window === "undefined") {
        return;
    }

    if (user) {
        localStorage.setItem(USER_STORAGE_KEY, JSON.stringify(user));
    } else {
        localStorage.removeItem(USER_STORAGE_KEY);
    }
}

export function loadStoredCart() {
    if (typeof window === "undefined") {
        return [];
    }

    const savedCart = localStorage.getItem(CART_STORAGE_KEY);
    return savedCart ? JSON.parse(savedCart) : [];
}

export function saveStoredCart(cart) {
    if (typeof window === "undefined") {
        return;
    }

    localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
}

export function getCartItemCount(cart) {
    return cart.reduce((total, item) => total + item.quantity, 0);
}
