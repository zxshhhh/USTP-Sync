import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import GoogleLoginButton from '../components/Google/GoogleLoginButton';

const LoginPage = ({ onLoginSuccess, user }) => {
    const navigate = useNavigate();
    const [loginMessage, setLoginMessage] = useState(''); // State for messages

    // Effect to check if user is already logged in or if login was successful
    useEffect(() => {
        if (user) {
            // If user object exists (meaning logged in), redirect to home page
            navigate('/');
        }
    }, [user, navigate]); // Rerun when 'user' or 'navigate' changes

    const handleGoogleLoginSuccess = (userData) => {
        onLoginSuccess(userData); // Update the user state in App.js
        setLoginMessage('Login successful! Redirecting...');
        // The useEffect above will handle the redirection after setUser
    };

    const handleGoogleLoginFailure = (errorMessage) => {
        setLoginMessage('Login failed: ' + errorMessage);
    };

    return (
        <div style={{ padding: '20px', textAlign: 'center' }}>
            <h1>Welcome to Your System</h1>
            <p>{loginMessage}</p>
            {!user ? ( // Only show button if user is not logged in
                <GoogleLoginButton
                    onLoginSuccess={handleGoogleLoginSuccess}
                    onLoginFailure={handleGoogleLoginFailure}
                />
            ) : (
                <p>You are already logged in. Redirecting...</p>
            )}
        </div>
    );
};

export default LoginPage;