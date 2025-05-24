// src/components/CalendarPage.js
import React, { useState, useEffect, useCallback } from 'react';
import { Calendar, momentLocalizer } from 'react-big-calendar';
import 'react-big-calendar/lib/css/react-big-calendar.css';
import moment from 'moment';
import axios from 'axios';

const localizer = momentLocalizer(moment);

const CalendarPage = () => {
    const [events, setEvents] = useState([]);
    const [selectedEvent, setSelectedEvent] = useState(null);
    const [showModal, setShowModal] = useState(false);
    const [formState, setFormState] = useState({
        summary: '',
        description: '',
        location: '',
        start_datetime: '',
        end_datetime: '',
    });
    const [isEditing, setIsEditing] = useState(false);

    const API_BASE_URL = 'http://localhost:8000/api'; // Your Laravel API base URL

    const fetchEvents = useCallback(async () => {
        const token = localStorage.getItem('authToken');
        console.log('1. Token from localStorage:', token); // <-- Add this log

        if (!token) {
            console.warn('No authentication token found in localStorage. User may not be logged in.');
            // Optionally: alert('Please log in first.');
            // return; // Consider returning here to prevent immediate failed request
        }
        
        try {
            // You'll need to send an authorization header if your Laravel API is protected
            const token = localStorage.getItem('authToken'); // Example: get token from local storage
            const response = await axios.get(`${API_BASE_URL}/calendar/events`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
            const formattedEvents = response.data.map(event => ({
                id: event.id,
                title: event.summary,
                start: new Date(event.start.dateTime || event.start.date),
                end: new Date(event.end.dateTime || event.end.date),
                description: event.description,
                location: event.location,
            }));
            setEvents(formattedEvents);
        } catch (error) {
            console.error('Error fetching events:', error);
            // Handle unauthorized or other errors (e.g., redirect to login)
            if (error.response && error.response.status === 403) {
                alert('Please connect your Google Calendar account first.');
            }
        }
    }, []);

    useEffect(() => {
        fetchEvents();
    }, [fetchEvents]);

    const handleSelectSlot = ({ start, end }) => {
        setIsEditing(false);
        setSelectedEvent(null);
        setFormState({
            summary: '',
            description: '',
            location: '',
            start_datetime: moment(start).format('YYYY-MM-DDTHH:mm'),
            end_datetime: moment(end).format('YYYY-MM-DDTHH:mm'),
        });
        setShowModal(true);
    };

    const handleSelectEvent = (event) => {
        setIsEditing(true);
        setSelectedEvent(event);
        setFormState({
            summary: event.title,
            description: event.description,
            location: event.location,
            start_datetime: moment(event.start).format('YYYY-MM-DDTHH:mm'),
            end_datetime: moment(event.end).format('YYYY-MM-DDTHH:mm'),
        });
        setShowModal(true);
    };

    const handleChange = (e) => {
        setFormState({ ...formState, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const token = localStorage.getItem('authToken');
            const config = {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            };

            if (isEditing) {
                await axios.put(`${API_BASE_URL}/calendar/events/${selectedEvent.id}`, {
                    summary: formState.summary,
                    description: formState.description,
                    location: formState.location,
                    start_datetime: formState.start_datetime,
                    end_datetime: formState.end_datetime,
                }, config);
            } else {
                await axios.post(`${API_BASE_URL}/calendar/events`, {
                    summary: formState.summary,
                    description: formState.description,
                    location: formState.location,
                    start_datetime: formState.start_datetime,
                    end_datetime: formState.end_datetime,
                }, config);
            }
            setShowModal(false);
            fetchEvents(); // Refresh events
        } catch (error) {
            console.error('Error saving event:', error.response ? error.response.data : error.message);
            alert('Error saving event: ' + (error.response ? error.response.data.message || error.response.data.error : error.message));
        }
    };

    const handleDelete = async () => {
        if (window.confirm('Are you sure you want to delete this event?')) {
            try {
                const token = localStorage.getItem('authToken');
                await axios.delete(`${API_BASE_URL}/calendar/events/${selectedEvent.id}`, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                setShowModal(false);
                fetchEvents(); // Refresh events
            } catch (error) {
                console.error('Error deleting event:', error);
                alert('Error deleting event: ' + (error.response ? error.response.data.message || error.response.data.error : error.message));
            }
        }
    };

    const handleGoogleConnect = async () => {
        try {
            const token = localStorage.getItem('authToken');
            const response = await axios.get(`${API_BASE_URL}/auth/google`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
            window.location.href = response.data.auth_url;
        } catch (error) {
            console.error('Error redirecting to Google auth:', error);
            alert('Error connecting to Google: ' + (error.response ? error.response.data.message || error.response.data.error : error.message));
        }
    };

    // This useEffect handles the Google OAuth callback after redirect
    useEffect(() => {
        const urlParams = new URLSearchParams(window.location.search);
        const code = urlParams.get('code');
        const state = urlParams.get('state'); // If you implement state for CSRF protection

        if (code) {
            const connectGoogleCalendar = async () => {
                try {
                    const token = localStorage.getItem('authToken');
                    await axios.get(`${API_BASE_URL}/auth/google/callback?code=${code}`, {
                        headers: {
                            Authorization: `Bearer ${token}`
                        }
                    });
                    alert('Google Calendar connected successfully!');
                    window.history.pushState({}, document.title, window.location.pathname); // Clean URL
                    fetchEvents();
                } catch (error) {
                    console.error('Error handling Google callback:', error.response ? error.response.data : error.message);
                    alert('Failed to connect Google Calendar: ' + (error.response ? error.response.data.message || error.response.data.error : error.message));
                }
            };
            connectGoogleCalendar();
        }
    }, [fetchEvents]);


    return (
        <div style={{ height: '700px', margin: '50px' }}>
            <h1>My Google Calendar</h1>
            <button onClick={handleGoogleConnect} style={{ marginBottom: '20px', padding: '10px 15px', backgroundColor: '#4285F4', color: 'white', border: 'none', borderRadius: '5px', cursor: 'pointer' }}>
                Connect Google Calendar
            </button>
            <Calendar
                localizer={localizer}
                events={events}
                startAccessor="start"
                endAccessor="end"
                selectable
                onSelectSlot={handleSelectSlot}
                onSelectEvent={handleSelectEvent}
                defaultView="month"
                views={['month', 'week', 'day', 'agenda']}
                style={{ height: '100%' }}
            />

            {showModal && (
                <div style={{
                    position: 'fixed', top: 0, left: 0, right: 0, bottom: 0,
                    backgroundColor: 'rgba(0,0,0,0.5)', display: 'flex',
                    justifyContent: 'center', alignItems: 'center'
                }}>
                    <div style={{ backgroundColor: 'white', padding: '20px', borderRadius: '8px', width: '400px' }}>
                        <h2>{isEditing ? 'Edit Event' : 'Create New Event'}</h2>
                        <form onSubmit={handleSubmit}>
                            <div style={{ marginBottom: '15px' }}>
                                <label>Summary:</label>
                                <input type="text" name="summary" value={formState.summary} onChange={handleChange} required
                                    style={{ width: '100%', padding: '8px', marginTop: '5px', borderRadius: '4px', border: '1px solid #ccc' }} />
                            </div>
                            <div style={{ marginBottom: '15px' }}>
                                <label>Description:</label>
                                <textarea name="description" value={formState.description} onChange={handleChange}
                                    style={{ width: '100%', padding: '8px', marginTop: '5px', borderRadius: '4px', border: '1px solid #ccc' }} />
                            </div>
                            <div style={{ marginBottom: '15px' }}>
                                <label>Location:</label>
                                <input type="text" name="location" value={formState.location} onChange={handleChange}
                                    style={{ width: '100%', padding: '8px', marginTop: '5px', borderRadius: '4px', border: '1px solid #ccc' }} />
                            </div>
                            <div style={{ marginBottom: '15px' }}>
                                <label>Start:</label>
                                <input type="datetime-local" name="start_datetime" value={formState.start_datetime} onChange={handleChange} required
                                    style={{ width: '100%', padding: '8px', marginTop: '5px', borderRadius: '4px', border: '1px solid #ccc' }} />
                            </div>
                            <div style={{ marginBottom: '15px' }}>
                                <label>End:</label>
                                <input type="datetime-local" name="end_datetime" value={formState.end_datetime} onChange={handleChange} required
                                    style={{ width: '100%', padding: '8px', marginTop: '5px', borderRadius: '4px', border: '1px solid #ccc' }} />
                            </div>
                            <div style={{ display: 'flex', justifyContent: 'space-between' }}>
                                <button type="submit"
                                    style={{ padding: '10px 15px', backgroundColor: '#28a745', color: 'white', border: 'none', borderRadius: '5px', cursor: 'pointer' }}>
                                    {isEditing ? 'Update Event' : 'Add Event'}
                                </button>
                                {isEditing && (
                                    <button type="button" onClick={handleDelete}
                                        style={{ padding: '10px 15px', backgroundColor: '#dc3545', color: 'white', border: 'none', borderRadius: '5px', cursor: 'pointer' }}>
                                        Delete Event
                                    </button>
                                )}
                                <button type="button" onClick={() => setShowModal(false)}
                                    style={{ padding: '10px 15px', backgroundColor: '#6c757d', color: 'white', border: 'none', borderRadius: '5px', cursor: 'pointer' }}>
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
};

export default CalendarPage;