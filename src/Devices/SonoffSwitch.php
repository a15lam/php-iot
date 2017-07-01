<?php

namespace a15lam\PhpIot\Devices;

use a15lam\PhpWemo\Contracts\DeviceInterface;
use a15lam\PhpWemo\Devices\BaseDevice;

class SonoffSwitch extends BaseDevice implements DeviceInterface
{
    const MODEL_NAME = 'Sonoff Switch';

    /**
     * @param string $resource
     *
     * @return array|string
     */
    public function info($resource = 'description.xml')
    {
        return $this->client->info($resource);
    }

    public function On()
    {
        var_dump($this);
        $this->client->request('/switch/on');

        return true;
    }

    public function Off()
    {
        $this->client->request('/switch/off');

        return true;
    }

    public function state()
    {
        $rs = $this->client->request('/switch/state');

        if (is_array($rs) && isset($rs['switch'])) {
            return intval($rs['switch']);
        }

        return false;
    }

    /**
     * Indicates if device is dimmable or not.
     *
     * @return bool
     */
    public function isDimmable()
    {
        return false;
    }
}