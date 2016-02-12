<?php
/*
|--------------------------------------------------------------------------
| Two Factor Authentication Plugin Configuration
|--------------------------------------------------------------------------
|
| This configuration file contains all configurable settings for the
| Two Factor Authentication plugin. This plugin will add an extra 
| layer of security to your Wordpress installation. It sends a
| text message via the MessageBird API.
|
| @package danielgelling/twofactorauth
| @author Daniel Gelling | daniel@deaannemers.nl
|
*/


/*
|--------------------------------------------------------------------------
| Plugin Configuration
|--------------------------------------------------------------------------
|
| Here you can add your personal settings for the twofactorauth
| plugin. Here you should add your MessageBird API Key and
| the table in which the auth codes should be stored.
|
*/

define('MESSAGEBIRD_API_KEY', 'live_Ptkbe1FgbEAfSNVXDkPFWaZQH');
define('TWO_FACTOR_EXPIRES_IN', 30);
define('TWO_FACTOR_AUTH_TABLE', 'two_factor_logins');


/*
|--------------------------------------------------------------------------
| Two Factor Authentication Text Message Settings
|--------------------------------------------------------------------------
|
| Below you will find the configuration for the text message that will be
| sent when an user logges into the wp-admin.
|
*/

define('CODE_TEXT_MESSAGE_SENDER', 'Psy-zo');
define('CODE_TEXT_MESSAGE_CONTENT', 'Je code is: ');


/*
|--------------------------------------------------------------------------
| Two Factor Authentication Login Screen Settings
|--------------------------------------------------------------------------
|
| The configuration below shows the title and description of the error
| message that will be thrown when an user inputs an incorrect code.
|
*/

define('CODE_INVALID_MESSAGE', 'Code ongeldig');
define(
    'CODE_INVALID_MESSAGE_DESCRIPTION',
    'Je hebt een ongeldige verificatiecode ingevuld.'
);
