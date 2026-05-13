import { useMemo, useState } from "react";
import { apiFetch } from "../lib/apiClient";

function buildStars(value) {
    return Array.from({ length: 5 }, (_, index) => index + 1 <= value);
}

export default function RatingModal({
    isOpen,
    onClose,
    user,
    product,
    onRatingSaved,
}) {
    const [selectedRating, setSelectedRating] = useState(0);
    const [comment, setComment] = useState("");
    const [saving, setSaving] = useState(false);

    const stars = useMemo(() => buildStars(selectedRating), [selectedRating]);

    if (!isOpen || !product) {
        return null;
    }

    async function handleSubmit(e) {
        e.preventDefault();

        if (!user) {
            alert("Please login first.");
            return;
        }

        if (!selectedRating) {
            alert("Please choose a star rating first.");
            return;
        }

        try {
            setSaving(true);

            const response = await apiFetch("/ratings", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    product_id: product.id,
                    rating: selectedRating,
                    comment,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                alert(data.message || "Unable to save rating.");
                return;
            }

            alert("Thank you for your rating!");
            setSelectedRating(0);
            setComment("");
            onRatingSaved?.();
            onClose();
        } catch (error) {
            console.error("Rating save error:", error);
            alert("Unable to save your rating right now.");
        } finally {
            setSaving(false);
        }
    }

    return (
        <div className="modal-overlay active">
            <div className="modal-content">
                <button className="close-btn" onClick={onClose} type="button">
                    x
                </button>

                <h3 style={{ marginBottom: "1rem", color: "#ff1493" }}>
                    Rate {product.name}
                </h3>

                <form onSubmit={handleSubmit}>
                    <div className="star-picker">
                        {stars.map((filled, index) => (
                            <button
                                key={index}
                                type="button"
                                className={`star-btn ${filled ? "filled" : ""}`}
                                onClick={() => setSelectedRating(index + 1)}
                            >
                                {String.fromCharCode(9733)}
                            </button>
                        ))}
                    </div>

                    <div className="form-group">
                        <label htmlFor="rating-comment">Comment (Optional)</label>
                        <textarea
                            id="rating-comment"
                            value={comment}
                            onChange={(e) => setComment(e.target.value)}
                            placeholder="Tell us what you liked about this product..."
                            rows="4"
                        />
                    </div>

                    <button type="submit" className="btn-primary" disabled={saving}>
                        {saving ? "Saving..." : "Submit Rating"}
                    </button>
                </form>
            </div>
        </div>
    );
}
