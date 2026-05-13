import { useEffect, useState } from "react";
import Navbar from "../components/Navbar";
import Footer from "../components/Footer";
import AuthModal from "../components/AuthModal";
import { apiFetch } from "../lib/apiClient";
import {
    getCartItemCount,
    loadStoredCart,
    loadStoredUser,
    saveStoredUser,
} from "../lib/shopStorage";

export default function AboutPage() {
    const [showAuthModal, setShowAuthModal] = useState(false);
    const [user, setUser] = useState(null);
    const [cart, setCart] = useState([]);

    useEffect(() => {
        setUser(loadStoredUser());
        setCart(loadStoredCart());
    }, []);

    useEffect(() => {
        saveStoredUser(user);
    }, [user]);

    async function handleLogout() {
        try {
            await apiFetch("/users/logout", { method: "POST" });
        } catch (error) {
            console.error("Logout error:", error);
        } finally {
            setUser(null);
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

            <section className="about-section">
                <div className="container about-layout">
                    <div className="about-copy">
                        <h1>About Gelly's Delights</h1>
                        <p>
                            Gelly's Delights serves cozy coffee, sweet cakes, pastries,
                            and refreshing drinks made for everyday cravings and simple
                            celebrations.
                        </p>
                        <p>
                            Our ordering system helps customers browse favorites, add
                            items to cart, and send delivery details quickly from any
                            device.
                        </p>
                        <a className="btn-primary about-action" href="/products">
                            View Bestsellers
                        </a>
                    </div>

                    <div className="about-panel">
                        <div>
                            <span className="about-label">Fresh Picks</span>
                            <strong>Coffee, cakes, pastry, and drinks</strong>
                        </div>
                        <div>
                            <span className="about-label">Easy Orders</span>
                            <strong>Cart, checkout, and order tracking</strong>
                        </div>
                        <div>
                            <span className="about-label">Customer Voice</span>
                            <strong>Ratings and feedback for every favorite</strong>
                        </div>
                    </div>
                </div>
            </section>

            <Footer />

            <AuthModal
                isOpen={showAuthModal}
                onClose={() => setShowAuthModal(false)}
                onLogin={(userData) => {
                    setUser(userData);
                    setShowAuthModal(false);
                }}
            />
        </main>
    );
}
