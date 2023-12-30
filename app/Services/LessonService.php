<?php

namespace App\Services;

class LessonService
{
    private string $url = 'https://example-api.aknevrnky.dev/api/lectures';
    private \CurlHandle $ch;

    public function __construct(\CurlHandle $ch)
    {
        if(!isset($this->ch)) {
            $this->ch = $ch;
        }
    }

    public function find(int $id)
    {
        curl_setopt($this->ch, CURLOPT_URL, $this->url . '/' . $id);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Connection: keep-alive'
        ]);

        $result = curl_exec($this->ch);

        return json_decode($result, true);
    }
}
