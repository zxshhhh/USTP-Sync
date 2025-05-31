import React, { useState, useEffect} from 'react';
import { useNavigate } from 'react-router-dom';
import { getCurrentWeather, getForecast } from '../../api/weather'
import api from '../../api/axios'

function OpenWeather() {
  const [city, setCity] = useState('');
  const [currentWeather, setCurrentWeather] = useState(null);
  const [forecast, setForecast] = useState(null);
  const navigate = useNavigate();

  const handleClickMainPage = () => {
    navigate('/Mainpage');
  };

  const fetchWeather = async () => {
    const current = await getCurrentWeather(city);
    const forecastData = await getForecast(city);

    setCurrentWeather(current.data);
    setForecast(forecastData.data);
  };

  return (
    <div className="p-4">
      <box-icon name='arrow-back' onClick={handleClickMainPage} className="arrow-icon"></box-icon>
      <input
        value={city}
        onChange={(e) => setCity(e.target.value)}
        placeholder="Enter city"
        className="border p-2"
      />
      <button onClick={fetchWeather} className="ml-2 p-2 bg-blue-500 text-white">Get Weather</button>

      {currentWeather && (
        <div className="mt-4">
          <h2>Current Weather in {currentWeather.name}</h2>
          <p>{currentWeather.weather[0].description}</p>
          <p>Temperature: {currentWeather.main.temp} °C</p>
        </div>
      )}

      {forecast && (
        <div className="mt-4">
          <h2>Forecast</h2>
          {forecast.list.slice(0, 5).map((item, index) => (
            <div key={index}>
              <p>{item.dt_txt} - {item.main.temp} °C - {item.weather[0].description}</p>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}

export default OpenWeather
