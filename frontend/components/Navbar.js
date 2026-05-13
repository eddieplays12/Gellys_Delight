export default function Navbar({ onLoginClick, onLogout, user, cartCount }) {
    return (
        <nav className="navbar">
            <div className="nav-brand">
                <span>Coffee</span>
                <span>Gelly's Delights</span>
            </div>
            <div className="nav-links">
                <a href="/" className="nav-link">
                    Home
                </a>
                <a href="/products" className="nav-link">
                    Menu
                </a>
                <a href="/cart" className="nav-link">
                    Cart (<span>{cartCount}</span>)
                </a>
                <a href="/orders" className="nav-link">
                    Orders
                </a>
                <button className="login-btn" onClick={onLoginClick} type="button">
                    <span>Account</span>
                    <span>{user ? user.username : "Login"}</span>
                </button>
                {user ? (
                    <button className="logout-btn" onClick={onLogout} type="button">
                        Logout
                    </button>
                ) : null}
            </div>
        </nav>
    );
}
