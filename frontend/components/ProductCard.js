import React from "react";
import { API_ORIGIN } from "../lib/apiClient";

export default function ProductCard({ product, onAddToCart, onViewFeedback }) {
    const imageUrl =
        product.image_url ||
        (product.image
            ? `${API_ORIGIN}/product-images/${product.image}`
            : "https://via.placeholder.com/300x200?text=No+Image");
    const averageRating = Number(product.ratings_avg_rating || 0);
    const roundedRating = Math.round(averageRating);

    return (
        <div className="product-card">
            <div className="product-card-image">
                <img
                    src={imageUrl}
                    alt={product.name}
                    onError={(e) => {
                        e.target.src =
                            "https://via.placeholder.com/300x200?text=No+Image";
                    }}
                />
                <span className="product-category-badge">
                    {product.category}
                </span>
            </div>

            <div className="product-card-content">
                <h3 className="product-name">{product.name}</h3>

                <p className="product-description">
                    {product.description.length > 60
                        ? product.description.substring(0, 60) + "..."
                        : product.description}
                </p>

                <div className="product-rating-summary">
                    <span className="rating-stars">
                        {Array.from({ length: 5 }, (_, index) => (
                            <span
                                key={index}
                                className={index < roundedRating ? "filled-star" : "empty-star"}
                            >
                                {String.fromCharCode(9733)}
                            </span>
                        ))}
                    </span>
                    <span className="rating-text">
                        {product.ratings_count > 0
                            ? `${averageRating.toFixed(1)} (${product.ratings_count})`
                            : "No ratings yet"}
                    </span>
                </div>

                <button
                    className="btn-feedback"
                    type="button"
                    onClick={() => onViewFeedback(product)}
                >
                    View Feedback
                </button>

                <div className="product-card-footer">
                    <div className="product-price">
                        PHP {parseFloat(product.price).toFixed(2)}
                    </div>

                    <button
                        className="btn-add-to-cart"
                        onClick={() => onAddToCart(product)}
                    >
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    );
}
