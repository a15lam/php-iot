<?php

namespace a15lam\PhpIot;

use a15lam\PhpIot\Devices\PTSocket;
use a15lam\PhpIot\Devices\SBoxSwitch;
use a15lam\PhpIot\Devices\SonoffSwitch;
use a15lam\PhpIot\Workspace as WS;
use a15lam\Workspace\Utility\ArrayFunc;
use Clue\React\Ssdp\Client;
use React\EventLoop\Factory;


class Discovery extends \a15lam\PhpWemo\Discovery
{
    protected static function findAllDevices()
    {
        parent::findAllDevices();
        static::findSbox();
        static::findSonoff();
        static::findPTSocket();
    }

    protected static function findSbox()
    {
        $loop = Factory::create();
        $client = new Client($loop);
        $client->search('urn:SBOX:device:Basic:1', 2)->then(
            function (){
                if (WS::config()->get('debug') === true) {
                    echo 'SBox Search completed' . PHP_EOL;
                }
            },
            function ($e){
                throw new \Exception('SBox Device discovery failed: ' . $e);
            },
            function ($progress){
                if (WS::config()->get('debug') === true) {
                    echo "found one SBox!" . PHP_EOL;
                }
                static::$output[] = $progress;
            }
        );
        $loop->run();
    }

    protected static function findSonoff()
    {
        $loop = Factory::create();
        $client = new Client($loop);
        $client->search('urn:sonoff:device:Basic:1', 2)->then(
            function (){
                if (WS::config()->get('debug') === true) {
                    echo 'Sonoff Search completed' . PHP_EOL;
                }
            },
            function ($e){
                throw new \Exception('Sonoff Device discovery failed: ' . $e);
            },
            function ($progress){
                if (WS::config()->get('debug') === true) {
                    echo "found one sonoff!" . PHP_EOL;
                }
                static::$output[] = $progress;
            }
        );
        $loop->run();
    }

    protected static function findPTSocket()
    {
        $loop = Factory::create();
        $client = new Client($loop);
        $client->search('urn:peach:device:Basic:1', 2)->then(
            function (){
                if (WS::config()->get('debug') === true) {
                    echo 'PT device Search completed' . PHP_EOL;
                }
            },
            function ($e){
                throw new \Exception('PT Device discovery failed: ' . $e);
            },
            function ($progress){
                if (WS::config()->get('debug') === true) {
                    echo "found one PTD!" . PHP_EOL;
                }
                static::$output[] = $progress;
            }
        );
        $loop->run();
    }

    protected static function getClientByDevice($device)
    {
        if(static::isSbox($device)){
            $ip = static::getIpFromDevice($device);
            $port = static::getPortFromDevice($device);
            return new SBoxClient($ip, $port);
        }
        if(static::isSonoff($device)){
            $ip = static::getIpFromDevice($device);
            $port = static::getPortFromDevice($device);
            return new SonoffClient($ip, $port);
        }
        if(static::isPTDevice($device)){
            $ip = static::getIpFromDevice($device);
            $port = static::getPortFromDevice($device);
            return new PTClient($ip, $port);
        }
        return parent::getClientByDevice($device);
    }

    protected static function isSbox($device)
    {
        $deviceType = ArrayFunc::get($device, 'deviceType');
        if($deviceType === 'urn:SBOX:device:Basic:1'){
            return true;
        }
        $data = ArrayFunc::get($device, 'data', '');
        if(strpos($data, 'urn:SBOX:device:Basic:1') !== false){
            return true;
        }

        return false;
    }

    protected static function isSonoff($device)
    {
        $deviceType = ArrayFunc::get($device, 'deviceType');
        if($deviceType === 'urn:sonoff:device:Basic:1'){
            return true;
        }
        $data = ArrayFunc::get($device, 'data', '');
        if(strpos($data, 'urn:sonoff:device:Basic:1') !== false){
            return true;
        }

        return false;
    }

    protected static function isPTDevice($device)
    {
        $deviceType = ArrayFunc::get($device, 'deviceType');
        if($deviceType === 'urn:peach:device:Basic:1'){
            return true;
        }
        $data = ArrayFunc::get($device, 'data', '');
        if(strpos($data, 'urn:peach:device:Basic:1') !== false){
            return true;
        }

        return false;
    }

    protected static function getClientInfo($client)
    {
        if($client instanceof SonoffClient){
            return $client->info('description.xml');
        } elseif ($client instanceof SBoxClient){
            return $client->info('description.xml');
        }
        return parent::getClientInfo($client);
    }

    protected static function resolveOtherDevices(& $data, $info, $device)
    {
        if(static::isSbox($device)){
            $data['class_name'] = SBoxSwitch::class;
        }
        if(static::isSonoff($device)){
            $data['class_name'] = SonoffSwitch::class;
        }
        if(static::isPTDevice($device)){
            $data['class_name'] = PTSocket::class;
        }
    }
}