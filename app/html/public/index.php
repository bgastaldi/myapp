<?php

namespace WPPConnect;

require '../vendor/autoload.php';

use WPPConnect\Http\Request;
use WPPConnect\Helpers\Util;

if($_GET):
    $wppconnect = new Request([
        'base_url' => 'https://app.eot.ai',
        'secret_key' => 'My53cr3tKY',
        'session' => 'mySessionPHP',
        'token' => (isset($_GET['token'])) ? $_GET['token'] : null
    ]);
    $util = new Util();

    if(isset($_GET['generate-token'])):
        # Function: Generated Token
        # /api/:session/generate-token
        $response = $wppconnect->generateToken();
        $response = $util->toArray($response);
        if (isset($response['status']) and $response['status'] == 'success') :
            $wppconnect->options['token'] = $response['token'];
        endif;

        #debug
        $util->debug($response);
    endif;
    

    if(isset($_GET['start-session'])):
        # Function: Start Session
        # /api/:session/start-session
        $response = $wppconnect->startSession([
            'webhook' => 'https://app.eot.ai/php/webhook/index.php',
            'waitQrCode' => true
        ]);
        $response = $util->toArray($response);

        #debug
        $util->debug($response);
    endif;

    if(isset($_GET['send-message'])):
        # Function: Send Message
        # /api/:session/send-message
        $response = $wppconnect->sendMessage([
            'phone' => '5514997943471',
            'message' => 'Opa, funciona mesmo!',
            'isGroup' => false
        ]);
        $response = $util->toArray($response);

        #debug
        $util->debug($response);
    endif;

endif;