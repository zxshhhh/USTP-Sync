import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, useNavigate } from 'react-router-dom'
import MainPage from './pages/Main/Main_page'
import SpoonacularPage from './pages/Spoonacular/Spoonacular_page'
import FitbitPage from './pages/Fitbit/Fitbit_page'
import OpenWeather from './pages/OpenWeatherMap/OpenWeather_page'
import LoginPage from './components/LoginPage'

function App() {
    // This state will hold the user object if logged in
    const [user, setUser] = useState(null);

    useEffect(() => {
        // On app load, check if an auth token exists in localStorage
        const storedToken = localStorage.getItem('authToken');
        if (storedToken) {
            // In a real app, you'd verify this token with your backend
            // For now, we'll assume it's valid and set a placeholder user
            // You might want to add a /api/user endpoint to fetch user details
            // axios.get('/api/user', { headers: { Authorization: `Bearer ${storedToken}` } })
            //    .then(res => setUser(res.data))
            //    .catch(err => {
            //        console.error("Failed to fetch user with stored token:", err);
            //        localStorage.removeItem('authToken'); // Invalidate if token is bad
            //    });
            console.log("Existing token found. User is considered logged in.");
            // Placeholder: In a real app, you'd fetch the actual user data here
            setUser({ name: "Logged In User", email: "user@example.com" });
        }
    }, []);

    // Function to handle logout
    const handleLogout = () => {
        localStorage.removeItem('authToken');
        setUser(null);
        // Navigate to login page or home page after logout
        // This navigation will be handled by the LoginPage component's logic if it exists
    };

  return (
    <>
    <Router>
      <Routes>
        <Route path='/login' element={<LoginPage onLoginSuccess={(userData) => setUser(userData)} user={user} />} />
        <Route path="/" element={<MainPage user={user} onLogout={handleLogout} />} />
        <Route path="/Spoonacular" element={<SpoonacularPage />} />
        <Route path='/Fitbit' element={<FitbitPage />}/>
        <Route path='/OpenWeather' element={<OpenWeather />}/>
      </Routes>
    </Router>
    </>
   )
}

export default App
