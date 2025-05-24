import axios from '../api/axios';

export const getFitbitProfile = () => axios.get('/fitbit/profile');
export const getFitbitSteps = () => axios.get('/fitbit/steps');
export const getFitbitSleep = () => axios.get('/fitbit/sleep');
export const getFitbitHeartRate = () => axios.get('/fitbit/heartrate');
export const getFitbitCalories = () => axios.get('/fitbit/calories');

// Optional: Initiate OAuth
export const redirectToFitbitAuth = () => {
  window.location.href = 'http://localhost:8000/api/fitbit/redirect';
};
