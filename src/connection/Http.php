<?php

namespace DcoAi\PhpJina\connection;

class Http
{
    /**
     * Makes a CURL request to a specified URL with optional data payload.
     *
     * @param string $url The URL to send the request to.
     * @param string $method The HTTP method to use for the request.
     * @param mixed|null $data Optional data payload to include in the request.
     * @return mixed The response from the server, decoded as a JSON object.
     */
    public static function makeCurlRequest(string $url, string $method='GET', mixed $data = null)
    {
        $headers = [
            'Content-Type: application/json'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }
}