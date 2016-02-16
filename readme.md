
# Two Factor Authentication Wordpress Admin Plugin 

[![Total Downloads](https://poser.pugx.org/danielgelling/twofactor/d/total.svg)](https://packagist.org/packages/danielgelling/twofactor)
[![Latest Stable Version](https://poser.pugx.org/danielgelling/twofactor/v/stable.svg)](https://packagist.org/packages/danielgelling/twofactor)
[![Latest Unstable Version](https://poser.pugx.org/danielgelling/twofactor/v/unstable.svg)](https://packagist.org/packages/danielgelling/twofactor)
[![License](https://poser.pugx.org/danielgelling/twofactor/license.svg)](https://packagist.org/packages/danielgelling/twofactor)

TwoFactorAuth is a Wordpress plugin that will add an extra layer of security to your Wordpress installation. It sends a text message via the MessageBird API when someone tries to login to the Wordpress Admin.

## Installation

### Via composer

Installating the TwoFactorAuth plugin is a breeze with [composer](https://getcomposer.org/). You can get composer at [https://getcomposer.org/](https://getcomposer.org/).

    composer require danielgelling/twofactor:^1.0

### Via Github

Installing  the TwoFactorAuth plugin via Github can either be done by cloning the repository into the `wp-content/plugins` directory or downloading a zip file from the repo page.

    $ cd /path/to/project/wp-content/plugins
    $ git clone https://github.com/danielgelling/twofactorauth.git --branch noob --single-branch twofactorauth


Or unzip the zipped plugin file into the plugins directory.

## Configuration

### Turning on the lights

First we'll need to add the plugins configuration file to wp-config.php. We can do this by adding the following:
    
    /*
    |--------------------------------------------------------------------------
    | Two Factor Authentication Configuration
    |--------------------------------------------------------------------------
    |
    | Here we require the configuration file for the twofactorauth plugin.
    |
    */
    
    require_once dirname(__FILE__) . '/wp-content/plugins/twofactorauth/config.php';


Then we need to copy the example config file by executing the following command in the shell (assuming we are in the wp-content/plugins/twofactorauth directory):
    
    $ cp config.example.php config.php

Having done this, we can add our [MessageBird](https://www.messagebird.com) API key, the amount of days after which we want to prompt the user to authenticate himself by a text message (so how long an auth_code will be valid) we do this in days and the database table we want to use to store our auth_codes):


    define('MESSAGEBIRD_API_KEY', 'live_S0m3Rand0MapiKeY');
    define('TWO_FACTOR_EXPIRES_IN', 30);
    define('TWO_FACTOR_AUTH_TABLE', 'two_factor_logins');
    
Then we want to define the sender and the content of the text message:

    define('CODE_TEXT_MESSAGE_SENDER', 'Your company\'s name');
    define('CODE_TEXT_MESSAGE_CONTENT', 'Your code is: ');

Least but not last, we need to define our invalid code error message:

    define('CODE_INVALID_MESSAGE', 'Invalid code');
    define(
        'CODE_INVALID_MESSAGE_DESCRIPTION',
        'You provided an invalid authentication code.'
    );

## Activating the plugin

The last thing we need to do is activate the plugin in the wp-admin. This will be the last time you sign in without Two Factor Authentication, at least for the amount of days you specify to prompt for a new authentication.

So, the only thing we need to do now is click activate and our Wordpress Admin is more secure. Awesome!

![](https://i.imgur.com/NJY4Gbz.png)
