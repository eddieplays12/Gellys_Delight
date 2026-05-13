import { useEffect, useState } from "react";
import { API_BASE_URL } from "../lib/apiClient";

function renderStars(value) {
    const rating = Number(value || 0);

    return Array.from({ length: 5 }, (_, index) => (
        <span key={index} className={index < rating ? "filled-star" : "empty-star"}>
            {String.fromCharCode(9733)}
        </span>
    ));
}

export default function FeedbackModal({ product, onClose }) {
    const [ratings, setRatings] = useState([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    useEffect(() => {
        if (!product) {
            return;
        }

        fetchRatings();
    }, [product]);

    async function fetchRatings() {
        try {
            setLoading(true);
            setError(null);

            const response = await fetch(`${API_BASE_URL}/products/${product.id}/ratings`, {
                headers: { Accept: "application/json" },
            });

            if (!response.ok) {
                throw new Error("Failed to load feedback");
            }

            const data = await response.json();
            setRatings(data);
        } catch (feedbackError) {
            console.error("Feedback load error:", feedbackError);
            setError("Unable to load feedback right now.");
        } finally {
            setLoading(false);
        }
    }

    if (!product) {
        return null;
    }

    return (
        <div className="modal-overlay active">
            <div className="modal-content feedback-modal-content">
                <button className="close-btn" onClick={onClose} type="button">
                    x
                </button>

                <h3 className="feedback-title">Customer Feedback</h3>
                <p className="feedback-product-name">{product.name}</p>

                {loading ? (
                    <p className="feedback-muted">Loading feedback...</p>
                ) : null}

                {error ? <p className="feedback-error">{error}</p> : null}

                {!loading && !error && ratings.length === 0 ? (
                    <p className="feedback-muted">No feedback yet for this product.</p>
                ) : null}

                {!loading && !error && ratings.length > 0 ? (
                    <div className="feedback-list">
                        {ratings.map((rating) => (
                            <article className="feedback-item" key={rating.id}>
                                <div className="feedback-item-header">
                                    <strong>{rating.user?.username || "Customer"}</strong>
                                    <span className="feedback-stars">
                                        {renderStars(rating.rating)}
                                    </span>
                                </div>
                                <p>{rating.comment || "No comment provided."}</p>
                                <span className="feedback-date">
                                    {new Date(rating.created_at).toLocaleDateString()}
                                </span>
                            </article>
                        ))}
                    </div>
                ) : null}
            </div>
        </div>
    );
}
