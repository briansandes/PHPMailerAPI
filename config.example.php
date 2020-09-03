<?php
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(key_exists('dev_debug', $_POST)) {
        if((bool)$_POST['dev_debug'] === true) {
            define('DEV_DEBUG', true);
        }
    }
}

/* not debugging, default settings, no errors shown */
if(!defined('DEV_DEBUG')) {
    define('DEV_DEBUG', false);
    
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
} else {
    /* debugging, displaying all errors */
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}


/* time set to UTC 0 */
date_default_timezone_set('UTC');


define('DAILY_LIMIT', 200); // max emails per day
define('MESSAGE_COOLDOWN', 60); // seconds
define('USERS_PER_MESSAGE', 4); // max recipients per message
define('MESSAGE_MAX_SIZE', 200000); // around 200kb max size

/* SMTP CREDENTIALS AND SENDER INFO */
define('MAIL_HOST', 'smtp.example.com');
define('MAIL_PORT', 587);

/* MAIL_USERNAME, MAIL_SENDER & MAIL_FROM should all hold the same value to avoid spam */
define('MAIL_USERNAME', 'john.doe@example.com'); // for smtp auth
define('MAIL_PASSWORD', 'p4ssw0rd!'); // for smtp auth

define('MAIL_SENDER', 'john.doe@example.com');
define('MAIL_FROM', 'john.doe@example.com');
define('MAIL_SENDER_NAME', 'John Doe, from example.com');


/* your actual keys */
$config = [
    /* keys for web-clients accessing the API */
    'keys' => [
        'THIS_IS_KEY_NUMBER_1' => [
            'domains' => [
                'localhost',
                'example.co',
            ]
        ],
        'KEY_NUMBER_2' => [
            'domains' => [
                'localhost',
                'example.co',
            ]
        ]
    ]
];