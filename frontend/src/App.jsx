import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, useNavigate } from 'react-router-dom'
import MainPage from './pages/Main/Main_page'
import SpoonacularPage from './pages/Spoonacular/Spoonacular_page'
import FitbitPage from './pages/Fitbit/Fitbit_page'
import OpenWeather from './pages/OpenWeatherMap/OpenWeather_page'
import LoginPage from './components/LoginPage'
import ProtectedRoute from './routes/ProtectedRoute';

function App() {
  return (
    <>
    <Router>
      <Routes>
        <Route path='/' element={<LoginPage />} />
        <Route path='/Mainpage' element={<ProtectedRoute><MainPage /></ProtectedRoute>} />
        <Route path='/Spoonacular' element={<ProtectedRoute><SpoonacularPage /></ProtectedRoute>} />
        <Route path='/Fitbit' element={<ProtectedRoute><FitbitPage /></ProtectedRoute>} />
        <Route path='/OpenWeather' element={<ProtectedRoute><OpenWeather /></ProtectedRoute>} />
      </Routes>
    </Router>
    </>
   )
}

export default App
