<?php

namespace a15lam\PhpIot\Contracts;

interface ClientInterface
{
    public function info($url);

    public function request($controlUrl, $service = null, $method = null, $arguments = []);
}