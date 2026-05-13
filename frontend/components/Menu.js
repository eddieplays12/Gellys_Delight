import { useState, useEffect } from "react";
import ProductCard from "./ProductCard";
import FeedbackModal from "./FeedbackModal";
import { API_BASE_URL } from "../lib/apiClient";

export default function Menu({ onAddToCart }) {
    const [products, setProducts] = useState([]);
    const [filteredProducts, setFilteredProducts] = useState([]);
    const [selectedCategory, setSelectedCategory] = useState("all");
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [feedbackProduct, setFeedbackProduct] = useState(null);

    const categories = ["all", "Coffee", "Cakes", "Pastry", "Drinks"];

    useEffect(() => {
        fetchProducts();
    }, []);

    async function fetchProducts() {
        try {
            setLoading(true);
            setError(null);

            const response = await fetch(`${API_BASE_URL}/products`);

            if (!response.ok) {
                throw new Error("Failed to fetch products");
            }

            const data = await response.json();

            setProducts(data);
            setFilteredProducts(data);
        } catch (fetchError) {
            console.error("Error fetching products:", fetchError);
            setError("Failed to load products. Please try again later.");
        } finally {
            setLoading(false);
        }
    }

    function handleCategoryFilter(category) {
        setSelectedCategory(category);

        if (category === "all") {
            setFilteredProducts(products);
            return;
        }

        setFilteredProducts(products.filter((product) => product.category === category));
    }

    return (
        <section id="menu" className="menu-section">
            <div className="container">
                <h2 className="section-title">Our Menu</h2>

                <div className="menu-categories">
                    {categories.map((category) => (
                        <button
                            key={category}
                            className={`category-btn ${
                                selectedCategory === category ? "active" : ""
                            }`}
                            onClick={() => handleCategoryFilter(category)}
                        >
                            {category === "all" ? "All Products" : category}
                        </button>
                    ))}
                </div>

                {loading && (
                    <div className="loading-container">
                        <p>Loading delicious products...</p>
                    </div>
                )}

                {error && (
                    <div className="error-container">
                        <p>{error}</p>
                        <button className="btn-retry" onClick={fetchProducts}>
                            Try Again
                        </button>
                    </div>
                )}

                {!loading && !error && filteredProducts.length > 0 ? (
                    <div className="menu-grid">
                        {filteredProducts.map((product) => (
                            <ProductCard
                                key={product.id}
                                product={product}
                                onAddToCart={onAddToCart}
                                onViewFeedback={setFeedbackProduct}
                            />
                        ))}
                    </div>
                ) : null}

                {!loading && !error && filteredProducts.length === 0 ? (
                    <div className="no-products-container">
                        <p>No products found in this category.</p>
                    </div>
                ) : null}
            </div>

            <FeedbackModal
                product={feedbackProduct}
                onClose={() => setFeedbackProduct(null)}
            />
        </section>
    );
}
