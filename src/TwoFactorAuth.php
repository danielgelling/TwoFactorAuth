<?php

namespace DanielGelling;

use Carbon\Carbon;
use MessageBird\Objects\Message;
use MessageBird\Client as MessageBird;
use Illuminate\Database\Capsule\Manager as Capsule;

class TwoFactorAuth
{
    /**
     * Create a new TwoFactorAuth instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->apiKey = MESSAGEBIRD_API_KEY;
        $this->expires_in = TWO_FACTOR_EXPIRES_IN;
        $this->authTable = Capsule::table(TWO_FACTOR_AUTH_TABLE);
        $this->usersTable = Capsule::table('wp_users');
    }

    /**
     * Store and send code to the user.
     *
     * @param (object) $user
     * @return object
     */
    public function sendCode($user)
    {
        $this->authTable->where('user_id', $user->ID)->delete();

        $code = rand(100000, 999999);

        $authRecordId = $this->authTable->insertGetId([
            'user_id' => $user->ID,
            'auth_code' => $code,
            'requested_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addDays($this->expires_in)
        ]);

        $authRecord = $this->authTable->find($authRecordId);

        $messagebird = new MessageBird(MESSAGEBIRD_API_KEY);

        $message             = new Message();
        $message->originator = CODE_TEXT_MESSAGE_SENDER;
        $message->recipients = array($user->phone_number);
        $message->body       = CODE_TEXT_MESSAGE_CONTENT . $code;

        $messagebird->messages->create($message);

        return $authRecord;
    }

     /**
     * Get the auth record from the Two Factor Auth table
     *
     * @param (object) $user, (string) $code
     * @return Illuminate\Database\Query\Builder
     */
    public function getAuthRecord($user, $code)
    {
        return $this->authTable
                    ->where('auth_code', $code)
                    ->where('user_id', $user->ID);
    }
    
    /**
     * Get the form which displays the authentication code form.
     *
     * @return string
     */
    public function getForm($username, $password, $invalidCode = false)
    {
        $form = str_replace(
            '{{ password }}',
            base64_encode($password),
            str_replace(
                '{{ username }}',
                $username,
                file_get_contents(
                    dirname(__FILE__) . '../../views/twofactor_form.php'
                )
            )
        );

        if($invalidCode)
            $form = str_replace(
                '<div class="loginErrorContainer"></div>',
                '<div id="login_error"> 
                    <strong>' . CODE_INVALID_MESSAGE . '</strong>: <br />
                    ' . CODE_INVALID_MESSAGE_DESCRIPTION . '
                </div>',
                $form
            );

        return $form;
    }

    /**
     * Check the user's credentials and log them in if correct.
     *
     * @return bool
     */
    public function login($username, $password)
    {
        $credentials = [
            'user_login' => $username,
            'user_password' => $password
        ];

        if ( '' === $secure_cookie )
            $secure_cookie = is_ssl();

        $secure_cookie = apply_filters(
            'secure_signon_cookie',
            $secure_cookie,
            $credentials
        );

        // XXX ugly hack to pass this to wp_authenticate_cookie => made by WP
        global $auth_secure_cookie;
        $auth_secure_cookie = $secure_cookie;

        $user = wp_authenticate(
            $credentials['user_login'],
            $credentials['user_password']
        );

        if(!empty($user->errors))
            return false;

        wp_set_auth_cookie($user->ID, $credentials['remember'], $secure_cookie);

        return true;
    }
}
