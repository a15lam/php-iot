# php-iot
PHP library to control Wemo devices and Sonoff with custom firmware found [here](https://github.com/a15lam/PT-IoT/blob/master/Socket-Sonoff-AI/Socket-Sonoff-AI.ino).

Currently supports...

1. Wemo light bulb - on/off/dimming/status
2. Wemo light switch - on/off/status
3. Wemo switch (socket) - on/off/status
4. Wemo insight switch - on/off/params/status
5. Sonoff switch (custom firmware) - on/off/status
6. Grouped devices under wemo bridge
7. Device discovery


## Getting started:

<pre>
git clone https://github.com/a15lam/php-iot.git
cd php-iot
composer install
php example/console.php   // An example command line app to control your wemo devices.
</pre>

## Usage:

<pre>
$lightSwitch = \a15lam\PhpIot\Discovery::getDeviceByName('Bed Room Light'); // Use your wemo device name as they show on your wemo app. Supports grouped devices
$lightSwitch->On();
sleep(2); // Allow a moment to see the light turning on.
$lightSwitch->Off();
// Get switch status
echo $lightSwitch->status();
</pre>

Check the example directory for more usage. Run example/console.php from command line to control your devices.
