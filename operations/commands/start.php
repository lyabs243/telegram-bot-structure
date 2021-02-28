<?php 

    $inlineKeyboard = array();

    $responseMessage = welcomeMessage($userFullName);
    $sendMessage->sendTelegramMessage($chatId, $responseMessage, BOT_IMAGE_URL, array(), $inlineKeyboard);