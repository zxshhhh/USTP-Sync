import WeatherApp from '../../components/OpenWeatherMap/OpenWeather'
import React, { useEffect, useState } from 'react';
import axios from 'axios';

function OpenWeatherPage() {
  return (
    <>
      <WeatherApp />
    </>
  )
}

export default OpenWeatherPage;
