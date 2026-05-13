import { useState, useEffect } from "react";
import Navbar from "../components/Navbar";
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

export default function CartPage() {
    const [cart, setCart] = useState([]);
    const [showAuthModal, setShowAuthModal] = useState(false);
    const [user, setUser] = useState(null);
    const [deliveryDetails, setDeliveryDetails] = useState({
        delivery_name: "",
        delivery_phone: "",
        delivery_address: "",
    });

    useEffect(() => {
        setCart(loadStoredCart());
        setUser(loadStoredUser());
    }, []);

    useEffect(() => {
        if (!user) {
            return;
        }

        setDeliveryDetails((current) => ({
            delivery_name: current.delivery_name || user.name || user.username || "",
            delivery_phone: current.delivery_phone || "",
            delivery_address: current.delivery_address || user.address || "",
        }));
    }, [user]);

    useEffect(() => {
        saveStoredCart(cart);
    }, [cart]);

    useEffect(() => {
        saveStoredUser(user);
    }, [user]);

    function removeFromCart(productId) {
        setCart(cart.filter((item) => item.id !== productId));
    }

    function updateQuantity(productId, quantity) {
        if (quantity <= 0) {
            removeFromCart(productId);
            return;
        }

        setCart(
            cart.map((item) =>
                item.id === productId ? { ...item, quantity } : item,
            ),
        );
    }

    const subtotal = cart.reduce(
        (sum, item) => sum + parseFloat(item.price) * item.quantity,
        0,
    );
    const total = subtotal;

    function handleDeliveryChange(e) {
        const { name, value } = e.target;
        setDeliveryDetails((current) => ({
            ...current,
            [name]: value,
        }));
    }

    async function handleCheckout() {
        if (!user) {
            setShowAuthModal(true);
            return;
        }

        if (cart.length === 0) {
            alert("Your cart is empty");
            return;
        }

        if (
            !deliveryDetails.delivery_name.trim() ||
            !deliveryDetails.delivery_phone.trim() ||
            !deliveryDetails.delivery_address.trim()
        ) {
            alert("Please complete the delivery details first.");
            return;
        }

        try {
            const response = await apiFetch("/orders", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    delivery_name: deliveryDetails.delivery_name,
                    delivery_phone: deliveryDetails.delivery_phone,
                    delivery_address: deliveryDetails.delivery_address,
                    items: cart.map((item) => ({
                        product_id: item.id,
                        quantity: item.quantity,
                        price: item.price,
                    })),
                }),
            });

            if (!response.ok) {
                alert("Error placing order");
                return;
            }

            alert("Order placed successfully!");
            setCart([]);
            setDeliveryDetails({
                delivery_name: user?.name || user?.username || "",
                delivery_phone: "",
                delivery_address: user?.address || "",
            });
        } catch (error) {
            console.error("Checkout error:", error);
            alert("Error placing order");
        }
    }

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

            <section className="cart-section">
                <div className="container">
                    <h2 className="section-title">Your Cart</h2>

                    <div className="cart-items">
                        {cart.length === 0 ? (
                            <p>
                                Your cart is empty.{" "}
                                <a href="/products">Start shopping!</a>
                            </p>
                        ) : (
                            cart.map((item) => (
                                <div key={item.id} className="cart-item">
                                    <div className="cart-item-details">
                                        <div className="cart-item-name">
                                            {item.name}
                                        </div>
                                        <div>
                                            PHP {parseFloat(item.price).toFixed(2)}
                                            {" x "}
                                            <input
                                                type="number"
                                                min="1"
                                                value={item.quantity}
                                                onChange={(e) =>
                                                    updateQuantity(
                                                        item.id,
                                                        parseInt(
                                                            e.target.value,
                                                            10,
                                                        ),
                                                    )
                                                }
                                                style={{
                                                    width: "60px",
                                                    padding: "0.25rem",
                                                }}
                                            />
                                            {" = "}
                                            <strong>
                                                PHP{" "}
                                                {(
                                                    parseFloat(item.price) *
                                                    item.quantity
                                                ).toFixed(2)}
                                            </strong>
                                        </div>
                                    </div>
                                    <button
                                        onClick={() => removeFromCart(item.id)}
                                        style={{
                                            background: "#ff6b6b",
                                            color: "white",
                                            border: "none",
                                            padding: "0.5rem 1rem",
                                            borderRadius: "10px",
                                            cursor: "pointer",
                                        }}
                                    >
                                        Remove
                                    </button>
                                </div>
                            ))
                        )}
                    </div>

                    <div className="cart-summary">
                        <div className="checkout-form">
                            <h3>Delivery Details</h3>
                            <div className="form-group">
                                <label htmlFor="delivery_name">Full Name</label>
                                <input
                                    type="text"
                                    id="delivery_name"
                                    name="delivery_name"
                                    value={deliveryDetails.delivery_name}
                                    onChange={handleDeliveryChange}
                                    placeholder="Enter full name"
                                    required
                                />
                            </div>
                            <div className="form-group">
                                <label htmlFor="delivery_phone">Phone Number</label>
                                <input
                                    type="text"
                                    id="delivery_phone"
                                    name="delivery_phone"
                                    value={deliveryDetails.delivery_phone}
                                    onChange={handleDeliveryChange}
                                    placeholder="09XXXXXXXXX"
                                    required
                                />
                            </div>
                            <div className="form-group">
                                <label htmlFor="delivery_address">Delivery Address</label>
                                <textarea
                                    id="delivery_address"
                                    name="delivery_address"
                                    value={deliveryDetails.delivery_address}
                                    onChange={handleDeliveryChange}
                                    placeholder="House number, street, barangay, city"
                                    rows="4"
                                    required
                                />
                            </div>
                        </div>

                        <div className="summary-row">
                            <span>Subtotal:</span>
                            <span>PHP {subtotal.toFixed(2)}</span>
                        </div>
                        <div className="summary-row total">
                            <span>Total:</span>
                            <span>PHP {total.toFixed(2)}</span>
                        </div>
                        <button
                            className="btn-primary checkout-btn"
                            onClick={handleCheckout}
                            disabled={cart.length === 0}
                        >
                            Checkout
                        </button>
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
