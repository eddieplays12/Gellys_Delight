import { useState } from "react";
import { apiFetch } from "../lib/apiClient";

export default function AuthModal({ isOpen, onClose, onLogin }) {
    const [activeTab, setActiveTab] = useState("login");
    const [loginData, setLoginData] = useState({
        username: "",
        password: "",
    });
    const [registerData, setRegisterData] = useState({
        username: "",
        email: "",
        password: "",
        confirmPassword: "",
    });

    async function handleLogin(e) {
        e.preventDefault();

        try {
            const response = await apiFetch("/users/login", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(loginData),
            });

            const data = await response.json();

            if (!response.ok) {
                alert(data.message || "Login failed");
                return;
            }

            setLoginData({ username: "", password: "" });
            onLogin(data.user);
            onClose();
        } catch (error) {
            console.error("Login error:", error);
            alert("Unable to login right now.");
        }
    }

    async function handleRegister(e) {
        e.preventDefault();

        if (registerData.password !== registerData.confirmPassword) {
            alert("Passwords do not match");
            return;
        }

        try {
            const response = await apiFetch("/users/register", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    username: registerData.username,
                    email: registerData.email,
                    password: registerData.password,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                alert(data.message || "Registration failed");
                return;
            }

            setRegisterData({
                username: "",
                email: "",
                password: "",
                confirmPassword: "",
            });

            onLogin(data.user);
            onClose();
        } catch (error) {
            console.error("Registration error:", error);
            alert("Unable to register right now.");
        }
    }

    return (
        <div className={`modal-overlay ${isOpen ? "active" : ""}`}>
            <div className="modal-content">
                <button className="close-btn" onClick={onClose} type="button">
                    x
                </button>

                <div className="login-tabs">
                    <button
                        className={`tab-btn ${
                            activeTab === "login" ? "active" : ""
                        }`}
                        onClick={() => setActiveTab("login")}
                        type="button"
                    >
                        Login
                    </button>
                    <button
                        className={`tab-btn ${
                            activeTab === "register" ? "active" : ""
                        }`}
                        onClick={() => setActiveTab("register")}
                        type="button"
                    >
                        Register
                    </button>
                </div>

                {activeTab === "login" && (
                    <form onSubmit={handleLogin}>
                        <div className="form-group">
                            <label htmlFor="username">Username</label>
                            <input
                                type="text"
                                id="username"
                                value={loginData.username}
                                onChange={(e) =>
                                    setLoginData({
                                        ...loginData,
                                        username: e.target.value,
                                    })
                                }
                                required
                            />
                        </div>
                        <div className="form-group">
                            <label htmlFor="password">Password</label>
                            <input
                                type="password"
                                id="password"
                                value={loginData.password}
                                onChange={(e) =>
                                    setLoginData({
                                        ...loginData,
                                        password: e.target.value,
                                    })
                                }
                                required
                            />
                        </div>
                        <button type="submit" className="btn-primary">
                            Login
                        </button>
                    </form>
                )}

                {activeTab === "register" && (
                    <form onSubmit={handleRegister}>
                        <div className="form-group">
                            <label htmlFor="regUsername">Username</label>
                            <input
                                type="text"
                                id="regUsername"
                                value={registerData.username}
                                onChange={(e) =>
                                    setRegisterData({
                                        ...registerData,
                                        username: e.target.value,
                                    })
                                }
                                required
                            />
                        </div>
                        <div className="form-group">
                            <label htmlFor="regEmail">Email</label>
                            <input
                                type="email"
                                id="regEmail"
                                value={registerData.email}
                                onChange={(e) =>
                                    setRegisterData({
                                        ...registerData,
                                        email: e.target.value,
                                    })
                                }
                                required
                            />
                        </div>
                        <div className="form-group">
                            <label htmlFor="regPassword">Password</label>
                            <input
                                type="password"
                                id="regPassword"
                                value={registerData.password}
                                onChange={(e) =>
                                    setRegisterData({
                                        ...registerData,
                                        password: e.target.value,
                                    })
                                }
                                required
                            />
                        </div>
                        <div className="form-group">
                            <label htmlFor="regConfirmPassword">
                                Confirm Password
                            </label>
                            <input
                                type="password"
                                id="regConfirmPassword"
                                value={registerData.confirmPassword}
                                onChange={(e) =>
                                    setRegisterData({
                                        ...registerData,
                                        confirmPassword: e.target.value,
                                    })
                                }
                                required
                            />
                        </div>
                        <button type="submit" className="btn-primary">
                            Register
                        </button>
                    </form>
                )}
            </div>
        </div>
    );
}
