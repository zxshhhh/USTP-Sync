import React, { useState, useEffect } from 'react';
import GoogleLoginButton from './components/GoogleLoginButton';

function GoogleLoginPage() {
    const [user, setUser] = useState(null);
    const [loginMessage, setLoginMessage] = useState('');

    useEffect(() => {
        // Check if an auth token exists on component mount
        const storedToken = localStorage.getItem('authToken');
        if (storedToken) {
            // In a real app, you'd likely verify this token with your backend
            // For simplicity, we'll just set a dummy user or fetch user info
            console.log("Existing token found:", storedToken);
            setLoginMessage("You are already logged in (via stored token).");
            // You might want to make an API call to /api/user to fetch user details
            // axios.get('/api/user', { headers: { Authorization: `Bearer ${storedToken}` } })
            //    .then(res => setUser(res.data))
            //    .catch(err => console.error("Failed to fetch user:", err));
        }
    }, []);

    const handleLoginSuccess = (userData) => {
        setUser(userData);
        setLoginMessage('Login successful! Welcome, ' + userData.name);
    };

    const handleLoginFailure = (errorMessage) => {
        setLoginMessage('Login failed: ' + errorMessage);
        setUser(null);
    };

    const handleLogout = () => {
        localStorage.removeItem('authToken');
        setUser(null);
        setLoginMessage('Logged out successfully.');
    };

    return (
        <div style={{ padding: '20px', fontFamily: 'Arial, sans-serif' }}>
            <h1>Google Login Integration</h1>

            {user ? (
                <div>
                    <h2>Welcome, {user.name}!</h2>
                    <p>Your email: {user.email}</p>
                    <button onClick={handleLogout}>Logout</button>
                </div>
            ) : (
                <div>
                    <p>{loginMessage}</p>
                    <GoogleLoginButton
                        onLoginSuccess={handleLoginSuccess}
                        onLoginFailure={handleLoginFailure}
                    />
                </div>
            )}
        </div>
    );
}

export default GoogleLoginPage