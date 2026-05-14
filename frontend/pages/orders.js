import { useEffect, useState } from "react";
import Navbar from "../components/Navbar";
import Footer from "../components/Footer";
import AuthModal from "../components/AuthModal";
import RatingModal from "../components/RatingModal";
import { apiFetch } from "../lib/apiClient";
import {
    getCartItemCount,
    loadStoredCart,
    loadStoredUser,
    saveStoredUser,
} from "../lib/shopStorage";

export default function OrdersPage() {
    const [user, setUser] = useState(null);
    const [cart, setCart] = useState([]);
    const [orders, setOrders] = useState([]);
    const [loading, setLoading] = useState(true);
    const [showAuthModal, setShowAuthModal] = useState(false);
    const [selectedProduct, setSelectedProduct] = useState(null);

    useEffect(() => {
        setUser(loadStoredUser());
        setCart(loadStoredCart());
    }, []);

    useEffect(() => {
        saveStoredUser(user);
    }, [user]);

    useEffect(() => {
        if (!user) {
            setOrders([]);
            setLoading(false);
            return;
        }

        fetchOrders();
    }, [user]);

    async function fetchOrders() {
        try {
            setLoading(true);
            const response = await apiFetch(`/users/${user.id}/orders`);

            if (!response.ok) {
                throw new Error("Failed to fetch orders");
            }

            const data = await response.json();
            setOrders(data);
        } catch (error) {
            console.error("Orders fetch error:", error);
            alert("Unable to load your orders right now.");
        } finally {
            setLoading(false);
        }
    }

    async function handleLogout() {
        try {
            await apiFetch("/users/logout", { method: "POST" });
        } catch (error) {
            console.error("Logout error:", error);
        } finally {
            setUser(null);
            setOrders([]);
            setSelectedProduct(null);
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

            <section className="cart-section">
                <div className="container">
                    <h2 className="section-title">My Orders</h2>

                    {!user ? (
                        <div className="cart-summary">
                            <p>Please login first to view your orders.</p>
                            <button className="btn-primary checkout-btn" onClick={() => setShowAuthModal(true)}>
                                Login or Register
                            </button>
                        </div>
                    ) : null}

                    {user && loading ? (
                        <div className="cart-summary">
                            <p>Loading your orders...</p>
                        </div>
                    ) : null}

                    {user && !loading && orders.length === 0 ? (
                        <div className="cart-summary">
                            <p>You do not have any orders yet.</p>
                        </div>
                    ) : null}

                    {user && !loading && orders.length > 0 ? (
                        <div className="orders-list">
                            {orders.map((order) => (
                                <div className="order-card" key={order.id}>
                                    <div className="order-card-header">
                                        <div>
                                            <h3>Order #{order.id}</h3>
                                            <p>{new Date(order.created_at).toLocaleString()}</p>
                                        </div>
                                        <span className={`order-status status-${order.status.toLowerCase().replace(/\s+/g, "-")}`}>
                                            {order.status}
                                        </span>
                                    </div>

                                    <div className="order-meta">
                                        <p><strong>Deliver to:</strong> {order.delivery_name}</p>
                                        <p><strong>Phone:</strong> {order.delivery_phone}</p>
                                        <p><strong>Address:</strong> {order.delivery_address}</p>
                                    </div>

                                    <div className="order-items">
                                        {order.items.map((item) => (
                                            <div className="order-item-row" key={item.id}>
                                                <div>
                                                    <span>{item.product?.name || "Deleted product"}</span>
                                                    {item.product ? (
                                                        <div style={{ marginTop: "0.5rem" }}>
                                                            <button
                                                                type="button"
                                                                className="btn-secondary rate-btn"
                                                                onClick={() => setSelectedProduct(item.product)}
                                                            >
                                                                Rate with Stars
                                                            </button>
                                                        </div>
                                                    ) : null}
                                                </div>
                                                <span>{item.quantity} x PHP {parseFloat(item.price).toFixed(2)}</span>
                                            </div>
                                        ))}
                                    </div>

                                    <div className="order-total">
                                        Total: PHP {parseFloat(order.total).toFixed(2)}
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : null}
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

            <RatingModal
                isOpen={Boolean(selectedProduct)}
                onClose={() => setSelectedProduct(null)}
                user={user}
                product={selectedProduct}
                onRatingSaved={() => {
                    if (user) {
                        fetchOrders();
                    }
                }}
            />
        </main>
    );
}
