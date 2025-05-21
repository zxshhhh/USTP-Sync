import React, { useEffect, useState } from 'react';
import axios from 'axios';
import './OpenWeather_page.css';

function OpenWeatherPage() {
  const [forecast, setForecast] = useState([]);
  const [location, setLocation] = useState(null);
  const API_KEY = '50fd0d5964573fa7f3ecbcd9b5d712b6'; // Replace with your OpenWeatherMap key

  // Get user's geolocation
  useEffect(() => {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const { latitude, longitude } = position.coords;
        setLocation({ lat: latitude, lon: longitude });
      },
      (error) => {
        console.error('Geolocation error:', error);
      }
    );
  }, []);

  // Fetch 5-day forecast
  useEffect(() => {
    if (!location) return;
    axios
      .get(`https://api.openweathermap.org/data/2.5/forecast?lat={lat}&lon={lon}&appid={API_KEY}&units=metric`, {
        params: {
          lat: location.lat,
          lon: location.lon,
          units: 'metric',
          appid: API_KEY,
        },
      })
      .then((res) => {
        // Get one forecast per day (at 12:00)
        const filtered = res.data.list.filter(item => item.dt_txt.includes("12:00:00"));
        setForecast(filtered);
      })
      .catch((err) => console.error('Weather fetch error:', err));
  }, [location]);

  return (
    <div className="weather-container">
      <h3>5-Day Forecast</h3>
      {forecast.length === 0 ? (
        <p>Loading forecast...</p>
      ) : (
        <div className="forecast-list">
          {forecast.map((day, index) => (
            <div key={index} className="forecast-item">
              <p><strong>{new Date(day.dt_txt).toLocaleDateString()}</strong></p>
              <img
                src={`https://openweathermap.org/img/wn/${day.weather[0].icon}@2x.png`}
                alt={day.weather[0].description}
              />
              <p>{day.weather[0].main}</p>
              <p>{day.main.temp.toFixed(1)}°C</p>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}

export default OpenWeatherPage;
