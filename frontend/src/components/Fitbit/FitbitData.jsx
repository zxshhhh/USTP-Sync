// src/components/FitbitData.jsx
import React, { useEffect, useState } from 'react';
import axios from 'axios';

const FitbitData = ({ token, type }) => {
  const [data, setData] = useState(null);

  useEffect(() => {
    axios
      .get(`http://localhost:8000/api/fitbit/${type}`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      .then((res) => setData(res.data))
      .catch(console.error);
  }, [token, type]);

  return (
    <div>
      <h3> Data</h3>
      <pre>{JSON.stringify(data, null, 2)}</pre>
    </div>
  );
};

export default FitbitData;
