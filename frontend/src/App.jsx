import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import MainPage from './pages/Main/Main_page'
import SpoonacularPage from './pages/Spoonacular/Spoonacular_page'
import FitbitPage from './pages/Fitbit/Fitbit_page'
import CalendarPage from './components/CalendarPage';
import OpenWeather from './pages/OpenWeatherMap/OpenWeather_page'

function App() {

  return (
    <>
    <Router>
      <Routes>
        <Route path="/" element={<MainPage />} />
        <Route path="/Spoonacular" element={<SpoonacularPage />} />
        <Route path='/Fitbit' element={<FitbitPage />}/>
        <Route path='/Google' element={<CalendarPage />}/>
        <Route path='/OpenWeather' element={<OpenWeather />}/>
      </Routes>
    </Router>
    </>
   )
}

export default App
