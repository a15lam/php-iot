<?php

namespace a15lam\PhpIot;

use a15lam\PhpIot\Contracts\ClientInterface;
use a15lam\PhpWemo\WemoClient;
use a15lam\PhpWemo\Workspace as WS;

class SonoffClient extends WemoClient implements ClientInterface
{
    public function request($controlUrl, $service = null, $method = null, $arguments = [])
    {
        $url = 'http://' . $this->ip . '/' . ltrim($controlUrl, '/');
        $options = [
            CURLOPT_URL            => $url,
            CURLOPT_PORT           => $this->port,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_VERBOSE        => WS::config()->get('debug', false)
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);

        if (static::FORMAT_ARRAY === $this->output) {
            $response = json_decode($response, true);
        }

        return $response;
    }
}