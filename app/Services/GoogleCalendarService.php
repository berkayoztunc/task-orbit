<?php

namespace App\Services;

use App\Models\User;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;

class GoogleCalendarService
{
    private Client $client;

    public function __construct(User $user)
    {
        $this->client = new Client;
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setAccessToken($user->google_token);

        if ($user->google_refresh_token) {
            $this->client->setAccessType('offline');
            if ($this->client->isAccessTokenExpired()) {
                $this->client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
                $user->update(['google_token' => $this->client->getAccessToken()['access_token']]);
            }
        }
    }

    public function createEvent(string $title, string $startDateTime, string $endDateTime, ?string $description = null): Event
    {
        $calendar = new Calendar($this->client);

        $event = new Event([
            'summary' => $title,
            'description' => $description,
            'start' => new EventDateTime(['dateTime' => $startDateTime, 'timeZone' => 'Europe/Istanbul']),
            'end' => new EventDateTime(['dateTime' => $endDateTime, 'timeZone' => 'Europe/Istanbul']),
        ]);

        return $calendar->events->insert('primary', $event);
    }

    public function listEvents(int $maxResults = 10): array
    {
        $calendar = new Calendar($this->client);

        $events = $calendar->events->listEvents('primary', [
            'maxResults' => $maxResults,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => now()->toRfc3339String(),
        ]);

        return $events->getItems();
    }

    public function deleteEvent(string $eventId): void
    {
        $calendar = new Calendar($this->client);
        $calendar->events->delete('primary', $eventId);
    }
}
