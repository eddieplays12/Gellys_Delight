import { useState, useEffect } from "react";
import ProductCard from "./ProductCard";
import FeedbackModal from "./FeedbackModal";
import { API_BASE_URL } from "../lib/apiClient";

export default function Menu({
    onAddToCart,
    title = "Our Menu",
    subtitle = "",
    showCategories = true,
    bestsellersOnly = false,
    maxItems = null,
}) {
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

            const visibleProducts = prepareProducts(data);

            setProducts(data);
            setFilteredProducts(visibleProducts);
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
            setFilteredProducts(prepareProducts(products));
            return;
        }

        setFilteredProducts(
            prepareProducts(products.filter((product) => product.category === category)),
        );
    }

    function prepareProducts(productList) {
        const sortedProducts = bestsellersOnly
            ? [...productList].sort((a, b) => {
                const aScore =
                    Number(a.sold_quantity || 0) * 10 +
                    Number(a.ratings_avg_rating || 0) * 2 +
                    Number(a.ratings_count || 0);
                const bScore =
                    Number(b.sold_quantity || 0) * 10 +
                    Number(b.ratings_avg_rating || 0) * 2 +
                    Number(b.ratings_count || 0);

                return bScore - aScore;
            })
            : productList;

        return maxItems ? sortedProducts.slice(0, maxItems) : sortedProducts;
    }

    return (
        <section id="menu" className="menu-section">
            <div className="container">
                <h2 className="section-title">{title}</h2>

                {subtitle ? <p className="section-subtitle">{subtitle}</p> : null}

                {showCategories ? (
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
                ) : null}

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
