<?php

include_once 'constants.php';
include_once 'TelegramBotMessage.php';

if (isset($_GET['token']) && $_GET['token'] == TELEGRAM_TOKEN) {

    $update = json_decode(file_get_contents("php://input"), TRUE);
    if (isset($update)) {
        
        $sendMessage = new TelegramBotMessage($_GET['token']);
        if (isset($update)) {
            if (isset($update['callback_query'])) {
                $message = $update['callback_query']["message"];
            }
            else {
                $message = $update['message'];
            }

            $userChatId = $message['from']['id'];
            $userFullName = $message['from']['first_name'] . ' ' . strval($message['from']['last_name']);
            $chatId = $message['chat']['id'];
            $messageId = $message['message_id'];
            $messageText = $message['text'];
            
            if ($messageText == '/start') {

                $responseMessage = welcomeMessage($userFullName);
                $sendMessage->sendTelegramMessage($chatId, $responseMessage, BOT_IMAGE_URL);
            }
        }
    }

}
