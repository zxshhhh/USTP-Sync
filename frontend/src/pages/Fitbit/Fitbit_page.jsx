import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import {
  getFitbitProfile,
  getFitbitSteps,
  getFitbitSleep,
  getFitbitHeartRate,
  getFitbitCalories,
  redirectToFitbitAuth
} from '../../services/fitbitService';

function Fitbit_page() {
  const [profile, setProfile] = useState(null);
  const [steps, setSteps] = useState(null);
  const [sleep, setSleep] = useState(null);
  const [heartRate, setHeartRate] = useState(null);
  const [calories, setCalories] = useState(null);
  const [error, setError] = useState(null);

  const navigate = useNavigate();

  const handleClickMainPage = () => {
    navigate('/Mainpage');
  };

  const [activeSection, setActiveSection] = useState(null); // Tracks which button was clicked

  const loadData = async (section) => {
    try {
      setActiveSection(section);
      switch (section) {
        case 'profile':
          if (!profile) setProfile((await getFitbitProfile()).data);
          break;
        case 'steps':
          if (!steps) setSteps((await getFitbitSteps()).data);
          break;
        case 'sleep':
          if (!sleep) setSleep((await getFitbitSleep()).data);
          break;
        case 'heartRate':
          if (!heartRate) setHeartRate((await getFitbitHeartRate()).data);
          break;
        case 'calories':
          if (!calories) setCalories((await getFitbitCalories()).data);
          break;
        default:
          break;
      }
    } catch (err) {
      setError(err.response?.data || { message: 'Error fetching data' });
    }
  };

  return (
    <div className="p-6 space-y-4 max-w-xl mx-auto">
      <box-icon name='arrow-back' onClick={handleClickMainPage} className="arrow-icon"></box-icon>
      <h2 className="text-2xl font-bold">Fitbit Dashboard</h2>

      {error && (
        <div className="bg-red-100 text-red-600 p-4 rounded">
          <p>{error.message}</p>
          <button
            onClick={redirectToFitbitAuth}
            className="mt-2 px-4 py-2 bg-blue-600 text-white rounded"
          >
            Connect to Fitbit
          </button>
        </div>
      )}

      <div className="grid grid-cols-2 gap-4">
        <button
          className="px-4 py-2 bg-green-500 text-white rounded"
          onClick={() => loadData('profile')}
        >
          View Profile
        </button>
        <button
          className="px-4 py-2 bg-blue-500 text-white rounded"
          onClick={() => loadData('steps')}
        >
          View Steps
        </button>
        <button
          className="px-4 py-2 bg-purple-500 text-white rounded"
          onClick={() => loadData('sleep')}
        >
          View Sleep
        </button>
        <button
          className="px-4 py-2 bg-pink-500 text-white rounded"
          onClick={() => loadData('heartRate')}
        >
          View Heart Rate
        </button>
        <button
          className="px-4 py-2 bg-orange-500 text-white rounded"
          onClick={() => loadData('calories')}
        >
          View Calories
        </button>
      </div>

      <div className="mt-6">
        {activeSection === 'profile' && profile && (
          <div>
            <h3 className="text-lg font-semibold">User Profile</h3>
            <p>Name: {profile.user.fullName}</p>
            <p>Age: {profile.user.age}</p>
            <p>Gender: {profile.user.gender}</p>
          </div>
        )}

        {activeSection === 'steps' && steps && (
          <div>
            <h3 className="text-lg font-semibold">Steps (7 Days)</h3>
            <ul className="list-disc ml-5">
              {steps['activities-steps'].map((item, i) => (
                <li key={i}>{item.dateTime}: {item.value} steps</li>
              ))}
            </ul>
          </div>
        )}

        {activeSection === 'sleep' && sleep && (
          <div>
            <h3 className="text-lg font-semibold">Sleep</h3>
            <p>Total Sleep: {sleep.summary?.totalMinutesAsleep} minutes</p>
          </div>
        )}

        {activeSection === 'heartRate' && heartRate && (
          <div>
            <h3 className="text-lg font-semibold">Heart Rate</h3>
            {heartRate['activities-heart'].map((item, i) => (
              <div key={i} className="mb-2">
                <p>Date: {item.dateTime}</p>
                <p>Resting Heart Rate: {item.value.restingHeartRate ?? 'N/A'} bpm</p>
              </div>
            ))}
          </div>
        )}


        {activeSection === 'calories' && calories && (
          <div>
            <h3 className="text-lg font-semibold">Calories Burned</h3>
            <ul className="list-disc ml-5">
              {calories['activities-calories'].map((item, i) => (
                <li key={i}>
                  {item.dateTime}: {item.value} kcal
                </li>
              ))}
            </ul>
          </div>
        )}
      </div>
    </div>
  );
};

export default Fitbit_page
