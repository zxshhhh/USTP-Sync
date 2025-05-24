// src/components/FitbitConnect.jsx
import React from 'react';

const FitbitConnect = () => {
  const connect = () => {
    window.location.href = 'http://localhost:8000/api/fitbit/redirect';
  };

  return (
    <div>
      <button onClick={connect}>Connect Fitbit</button>
    </div>
  );
};

export default FitbitConnect;
