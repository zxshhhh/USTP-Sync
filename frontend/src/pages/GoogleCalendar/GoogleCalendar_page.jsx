import React from 'react';
import CalendarManager from '../../components/CalendarManager'; // Import the manager

const GoogleCalendar_page = ({ user, authToken }) => {
    // This page is protected by PrivateRoute, so `user` and `authToken` should be available.
    if (!user || !authToken) {
        return <p>Authenticating for calendar access...</p>; // Should ideally not be seen due to PrivateRoute
    }

    return (
        <div style={{ padding: '20px' }}>
            <h1>My Google Calendar</h1>
            <p>Manage your events directly from here.</p>
            {/* Pass the authToken down to CalendarManager */}
            <CalendarManager authToken={authToken} />
        </div>
    );
};

export default GoogleCalendar_page