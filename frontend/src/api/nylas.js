import axios from 'axios';

const API = 'http://localhost:8000/api';

export const getAuthUrl = async () => {
  const res = await axios.get(`${API}/nylas/auth-url`);
  return res.data.url;
};

export const getCalendars = async (token) => {
  const res = await axios.get(`${API}/nylas/calendars`, {
    headers: { Authorization: `Bearer ${token}` },
  });
  return res.data;
};

export const createEvent = async (token, calendarId, eventData) => {
  const res = await axios.post(`${API}/nylas/calendar/${calendarId}/event`, eventData, {
    headers: { Authorization: `Bearer ${token}` },
  });
  return res.data;
};
