import React, { useEffect, useRef } from 'react';
import axios from 'axios';

const GoogleLoginButton = ({ onLoginSuccess, onLoginFailure }) => {
    const googleButtonRef = useRef(null);

    useEffect(() => {
        // Initialize Google Sign-In
        window.google.accounts.id.initialize({
            client_id: import.meta.env.VITE_GOOGLE_CLIENT_ID, // Your Google Client ID
            callback: handleCredentialResponse,
        });

        // Render the Google Sign-In button
        window.google.accounts.id.renderButton(
            googleButtonRef.current,
            { theme: 'outline', size: 'large', text: 'signin_with' } // Customize button as needed
        );

        // Optional: Prompt the user to select a Google account automatically
        // window.google.accounts.id.prompt();
    }, []);

    const handleCredentialResponse = async (response) => {
        if (response.credential) {
            const idToken = response.credential;
            console.log('Google ID Token:', idToken);

            try {
                // Send the ID token to your Laravel backend
                const backendUrl = import.meta.env.VITE_BACKEND_URL || 'http://localhost:8000/api';
                const res = await axios.post(`${backendUrl}/auth/google/callback`, {
                    id_token: idToken,
                });

                console.log('Backend response:', res.data);

                if (res.data.token) {
                    // Store the API token (e.g., in localStorage)
                    localStorage.setItem('authToken', res.data.token);
                    if (onLoginSuccess) {
                        onLoginSuccess(res.data.user);
                    }
                } else {
                    if (onLoginFailure) {
                        onLoginFailure('Authentication failed: No token received.');
                    }
                }
            } catch (error) {
                console.error('Error sending ID token to backend:', error.response ? error.response.data : error.message);
                if (onLoginFailure) {
                    onLoginFailure(error.response ? error.response.data.message : 'Login failed. Please try again.');
                }
            }
        } else {
            console.error('No credential in Google response.');
            if (onLoginFailure) {
                onLoginFailure('Google login failed: No credential received.');
            }
        }
    };

    return (
        <div ref={googleButtonRef}></div>
    );
};

export default GoogleLoginButton