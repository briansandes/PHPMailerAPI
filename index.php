<?php
/* PHP Mailing API */
/* written by Brian on his best days alive despite the COVID-19 outbreak */

define('VERSION', '0.1.1');
define('VERSION_DATE', '2020-07-07T19:30:00Z');

require 'config.php';
require 'functions.php';

$requestDomain = str_replace(['http://', 'https://'], null, $_SERVER['HTTP_ORIGIN']);

/* sanity check */
if($_SERVER['REQUEST_METHOD'] === 'GET') {
    output([
        'error' => 0,
        'message' => 'Sanity check OK.',
        'version' => VERSION,
        'version_date' => VERSION_DATE
    ]);
} else
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* all params present */
    $mandatory = ['key', 'to', 'subject', 'message'];

    foreach ($mandatory as $param) {
        if (!key_exists($param, $_POST)) {
            throwError('Missing "' . $param . '" parameter.');
        }
    }

    /* safe key */
    $key = decodeKey($_POST['key']);

    /* end of mandatory params */
    /* check key integrity */
    if (!key_exists($key, $config['keys'])) {
        throwError('The parsed auth key is not valid.');
    } else {
        if (!in_array($requestDomain, $config['keys'][$key]['domains'])) {
            throwError('The request origin domain "' . $requestDomain . '" isnt authorized to use this API.');
        } else
        if (checkLimit()['error'] === true) {
            output(checkLimit());
        } else {
            /* body validation */
            /* TODO check whether recipients are valid addresses */
            
            /* checks for body size */
            if(strlen($_POST['message']) > MESSAGE_MAX_SIZE) {
                $diff = strlen($_POST['message']) - MESSAGE_MAX_SIZE;
                throwError('The message body is '.$diff.' bytes longer than the allowed limit.');
            } else {
                $params = sanitizeParams([
                    'key' => $key,
                    'to' => $_POST['to'],
                    'subject' => '[TEST] ' . $_POST['subject'],
                    'message' => $_POST['message'],
                    'requestDomain' => $requestDomain,
                    'referer' => $_SERVER['HTTP_REFERER'],
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'ff' => key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '',
                    'debug' => key_exists('debug', $_POST) ? (bool)$_POST['debug'] : false
                ]);

                $result = sendMessage($params);

                responseOK('message sent');
            }
        }
    }
}