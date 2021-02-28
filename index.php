<?php

include_once 'constants.php';
include_once 'TelegramBotMessage.php';

if (isset($_GET['token']) && $_GET['token'] == TELEGRAM_TOKEN) {

    $update = json_decode(file_get_contents("php://input"), TRUE);
    //file_put_contents('test.txt', json_encode($update));
    if (isset($update)) {
        
        $sendMessage = new TelegramBotMessage($_GET['token']);
        if (isset($update)) {
            if (isset($update['callback_query'])) {
                $message = $update['callback_query']["message"];
                $userChatId = $update['callback_query']['from']['id'];
                $userFullName = $update['callback_query']['from']['first_name'] . ' ' . strval($update['callback_query']['from']['last_name']);
            }
            else {
                $message = $update['message'];
                $userChatId = $message['from']['id'];
                $userFullName = $message['from']['first_name'] . ' ' . strval($message['from']['last_name']);
            }

            $chatId = $message['chat']['id'];
            $messageId = $message['message_id'];
            $messageText = $message['text'];

            if (isset($update['callback_query'])) {
                $callBack = $update['callback_query']['data'];
                $callBackId = $update['callback_query']['id'];

                $sendMessage->answerTocallBack($callBackId, '');
            }
            else {
                if ($messageText == '/start') {
                    include 'operations/commands/start.php';
                }
                else {
                    
                }
            }
        }
    }

}
