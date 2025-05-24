<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Carbon\Carbon;

class GoogleCalendarService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $this->client->addScope(Calendar::CALENDAR); // Full access to calendar
        $this->client->setAccessType('offline'); // Get refresh token
        $this->client->setPrompt('consent'); // Force consent screen to get refresh token
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setAccessToken($token)
    {
        $this->client->setAccessToken($token);
    }

    public function getEvents($calendarId = 'primary')
    {
        $service = new Calendar($this->client);
        $optParams = [
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'), // Only show upcoming events
        ];
        $results = $service->events->listEvents($calendarId, $optParams);
        return $results->getItems();
    }

    public function createEvent(array $eventData, $calendarId = 'primary')
    {
        $service = new Calendar($this->client);
        $event = new Event($eventData);
        $createdEvent = $service->events->insert($calendarId, $event);
        return $createdEvent;
    }

    public function updateEvent($eventId, array $eventData, $calendarId = 'primary')
    {
        $service = new Calendar($this->client);
        $event = $service->events->get($calendarId, $eventId);
        $updatedEvent = new Event($eventData);
        $service->events->update($calendarId, $eventId, $updatedEvent);
        return $updatedEvent;
    }

    public function deleteEvent($eventId, $calendarId = 'primary')
    {
        $service = new Calendar($this->client);
        $service->events->delete($calendarId, $eventId);
        return true;
    }

    public function refreshToken($refreshToken)
    {
        $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
        return $this->client->getAccessToken();
    }
}