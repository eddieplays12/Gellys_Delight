export default function Hero() {
    return (
        <section className="hero">
            <div className="hero-shell">
                <div className="hero-image-panel">
                    <img
                        src="/images/gellys-delights-logo.png"
                        alt="Gelly's Delights"
                        className="hero-logo-image"
                    />
                </div>
                <div className="hero-content">
                    <h1>Welcome to Gelly's Delights</h1>
                    <p>Experience the finest coffee and pastries in town</p>
                    <button
                        className="btn-primary btn-large"
                        onClick={() =>
                            document
                                .getElementById("menu")
                                ?.scrollIntoView({ behavior: "smooth" })
                        }
                    >
                        Order Now
                    </button>
                </div>
            </div>
        </section>
    );
}
