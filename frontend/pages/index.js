import { useEffect, useState } from "react";
import Navbar from "../components/Navbar";
import Hero from "../components/Hero";
import Menu from "../components/Menu";
import Footer from "../components/Footer";
import AuthModal from "../components/AuthModal";
import { apiFetch } from "../lib/apiClient";
import {
    getCartItemCount,
    loadStoredCart,
    loadStoredUser,
    saveStoredCart,
    saveStoredUser,
} from "../lib/shopStorage";

export default function Home() {
    const [showAuthModal, setShowAuthModal] = useState(false);
    const [user, setUser] = useState(null);
    const [cart, setCart] = useState([]);
    const [pendingProduct, setPendingProduct] = useState(null);

    useEffect(() => {
        setUser(loadStoredUser());
        setCart(loadStoredCart());
    }, []);

    useEffect(() => {
        saveStoredUser(user);
    }, [user]);

    useEffect(() => {
        saveStoredCart(cart);
    }, [cart]);

    useEffect(() => {
        if (user && pendingProduct) {
            addProductToCart(pendingProduct);
            setPendingProduct(null);
            setShowAuthModal(false);
        }
    }, [user, pendingProduct]);

    function addProductToCart(product) {
        setCart((currentCart) => {
            const existingItem = currentCart.find((item) => item.id === product.id);

            if (existingItem) {
                return currentCart.map((item) =>
                    item.id === product.id
                        ? { ...item, quantity: item.quantity + 1 }
                        : item,
                );
            }

            return [...currentCart, { ...product, quantity: 1 }];
        });

        alert(`${product.name} added to cart!`);
    }

    function handleAddToCart(product) {
        if (!user) {
            setPendingProduct(product);
            setShowAuthModal(true);
            return;
        }

        addProductToCart(product);
    }

    async function handleLogout() {
        try {
            await apiFetch("/users/logout", { method: "POST" });
        } catch (error) {
            console.error("Logout error:", error);
        } finally {
            setUser(null);
            setPendingProduct(null);
            setShowAuthModal(false);
        }
    }

    return (
        <main>
            <Navbar
                onLoginClick={() => setShowAuthModal(true)}
                onLogout={handleLogout}
                user={user}
                cartCount={getCartItemCount(cart)}
            />
            <Hero />
            <Menu onAddToCart={handleAddToCart} />
            <Footer />
            <AuthModal
                isOpen={showAuthModal}
                onClose={() => setShowAuthModal(false)}
                onLogin={(userData) => {
                    setUser(userData);
                }}
            />
        </main>
    );
}
