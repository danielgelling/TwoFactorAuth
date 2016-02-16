<?php

/*
Plugin Name: WP Admin Two Factor Auth
Plugin URI: http://www.deaannemers.nl
Description: This plugin adds Two Factor Authentication to your wp-admin environment
Author: Daniel Gelling
Author e-mail: daniel@deaannemers.nl
Author URI: http://www.deaannemers.nl
Version: 1.0
*/

require_once 'vendor/autoload.php';
require_once 'src/UserProfile.php';

if(dirname(__FILE__) . '/../../../wp-includes/pluggable.php')
    require_once dirname(__FILE__) . '/../../../wp-includes/pluggable.php';

use Carbon\Carbon;
use DanielGelling\TwoFactorAuth;
use Illuminate\Database\Capsule\Manager as Capsule;

if(
    (isset($_POST['log']) && isset($_POST['pwd'])) ||
    (
        isset($_POST['code']) &&
        isset($_POST['username']) &&
        isset($_POST['password']))
)
{
    /**
    * Set up Eloquent database connection.
    */

    $capsule = new Capsule();

    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => DB_HOST,
        'database'  => DB_NAME,
        'username'  => DB_USER,
        'password'  => DB_PASSWORD,
        'charset'   => DB_CHARSET,
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ]);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    /**
    * Create a new TwoFactorAuth instance.
    */
    $twofactor = new TwoFactorAuth();
}

/**
* Check if a login is posted.
*/
if(isset($_POST['log']) && isset($_POST['pwd']))
{
    $user = wp_authenticate($_POST['log'], $_POST['pwd']);

    if(!empty($user->errors))
        return;

    /**
    * If the user has logged in with Two Factor Auth within the TWO_FACTOR_AUT
    * period, he doesn't have to authenticate with text again.
    */
    if(isset($_COOKIE['twofactorauth']) && TWO_FACTOR_EXPIRES_IN !== 0)
    {
        $authRecord = $capsule->table(TWO_FACTOR_AUTH_TABLE)
                ->where('auth_code', base64_decode($_COOKIE['twofactorauth']))
                ->first();

        /**
        * Check if the auth_code is still valid.
        */
        if(
            Carbon::now()->diffInDays(
                Carbon::parse($authRecord->expires_at)
            )
            != 0
        )
            if($twofactor->login($_POST['log'], $_POST['pwd']))
            {
                header('Location: /wp-admin');
                return;
            }
            else
                return;
    }

    /**
    * Create and send the auth_code to the user.
    */
    $authRecord = $twofactor->sendCode($user);

    /**
    * Display the form for auth_code input.
    */
    echo $twofactor->getForm($_POST['log'], $_POST['pwd']);

    exit;
}
elseif(
    isset($_POST['code']) &&
    isset($_POST['username']) &&
    isset($_POST['password']))
{
    $code = $_POST['code'];
    $username = $_POST['username'];
    $password = base64_decode($_POST['password']);

    $user = $capsule->table('wp_users')
                    ->where('user_login', $username)
                    ->first();

    $authRecord = $twofactor->getAuthRecord($user, $code);

    if($authRecord->count() == 0)
    {
        /**
        * Display the form for auth_code input.
        */
        echo $twofactor->getForm($username, $password, true);
        exit;
    }
    else
    {
        $authRecord = $authRecord->first();
        $username = $_POST['username'];
        $password = base64_decode($_POST['password']);

        /**
        * If the user has logged in with Two Factor Auth and the auth_code expire
        * date isnot set to 0, we will create a cookie for the user.
        */
        if($twofactor->login($username, $password) && TWO_FACTOR_EXPIRES_IN !== 0)
        {
            setcookie('twofactorauth',
                base64_encode(
                    $authRecord->auth_code
                ),
                time() + (TWO_FACTOR_EXPIRES_IN * 24 * 60 * 60),
                '/'
            );

            header('Location: /wp-admin');
            return;
        }

        header('Location: /wp-login.php');

        exit;
    }
}
else
    return;

?>
