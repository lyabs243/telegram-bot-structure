<?php

class TelegramBotMessage {

    const TELEGRAM_API_PATH = "https://api.telegram.org/bot";
    const TELEGRAM_METHOD_SEND_MESSAGE = "/sendmessage?";
    const TELEGRAM_METHOD_EDIT_MESSAGE = "/editMessageText?";
    const TELEGRAM_METHOD_SEND_PHOTO = "/sendPhoto?";
    const TELEGRAM_METHOD_ANSWER_CALLBACK = "/answerCallbackQuery?";
    const TELEGRAM_METHOD_DELETE_MESSAGE = "/deleteMessage?";
    const TELEGRAM_METHOD_GET_CHAT_ADMINS = '/getChatAdministrators?';
    const TELEGRAM_METHOD_LEAVE_CHAT = '/leaveChat?';

    private $token;

    public function __construct($_token) {
	    $this->token = $_token;
	}

    public function sendTelegramMessage($chatId, $message, $urlPhoto='', $keyboards=array(), 
    $inlineKeyboard=array(), $parseMode='') {
        $url = TelegramBotMessage::TELEGRAM_API_PATH . $this->token;
        $data['chat_id'] = $chatId;
        if (! empty($parseMode)) {
            $data['parse_mode'] = $parseMode; 
        }
        if (strlen($urlPhoto) > 0) {
            $url .= TelegramBotMessage::TELEGRAM_METHOD_SEND_PHOTO;
            $data['caption'] = $message;
            $data['photo'] = $urlPhoto;
        }
        else {
            $url .= TelegramBotMessage::TELEGRAM_METHOD_SEND_MESSAGE;
            $data['text'] = $message;
        }

        if (count($keyboards) > 0) {
            $data['reply_markup']['keyboard'] = $keyboards;
            $data['reply_markup']['resize_keyboard'] = true;
            $data['reply_markup']['one_time_keyboard'] = false;
        }
        else if (count($inlineKeyboard) > 0) {
            $data['reply_markup']['inline_keyboard'] = $inlineKeyboard;
        }

        $response = $this->sendPostData($url, $data);

        return $response;
    }

    public function deleteMessage($chatId, $messageId) {
        $url = TelegramBotMessage::TELEGRAM_API_PATH . $this->token . 
        TelegramBotMessage::TELEGRAM_METHOD_DELETE_MESSAGE;
        
        $data['chat_id'] = $chatId;
        $data['message_id'] = $messageId;

        $response = $this->sendPostData($url, $data);

        return $response;
    }

    public function editMessage($chatId, $messageId, $message, $inlineKeyboard = array()) {
        $url = TelegramBotMessage::TELEGRAM_API_PATH . $this->token . 
        TelegramBotMessage::TELEGRAM_METHOD_EDIT_MESSAGE;
        
        $data['chat_id'] = $chatId;
        $data['message_id'] = $messageId;
        $data['text'] = $message;
        if (count($inlineKeyboard) > 0) {
            $data['reply_markup']['inline_keyboard'] = $inlineKeyboard;
        }

        $response = $this->sendPostData($url, $data);

        return $response;
    }

    public function answerTocallBack($callBackId, $message) {
        $url = TelegramBotMessage::TELEGRAM_API_PATH . $this->token;
        
        $url .= TelegramBotMessage::TELEGRAM_METHOD_ANSWER_CALLBACK;
        $data['text'] = $message;
        $data['callback_query_id'] = $callBackId;

        $response = $this->sendPostData($url, $data);

        return $response;
    }

    //get administrators list of a group
    public function getAdmins($groupId) {
        $url = TelegramBotMessage::TELEGRAM_API_PATH . $this->token;
        
        $url .= TelegramBotMessage::TELEGRAM_METHOD_GET_CHAT_ADMINS;
        $data['chat_id'] = $groupId;

        $response = $this->sendPostData($url, $data);

        return $response;
    }

    public function leaveChatGroup($groupId) {
        $url = TelegramBotMessage::TELEGRAM_API_PATH . $this->token;
        
        $url .= TelegramBotMessage::TELEGRAM_METHOD_LEAVE_CHAT;
        $data['chat_id'] = $groupId;

        $response = $this->sendPostData($url, $data);

        return $response;
    }

    public function sendPostData($url, $data) {
		$options = array(
			'http' => array(
				'method'  => 'POST',
				'content' => json_encode( $data ),
				'header'=>  "Content-Type: application/json\r\n" .
					"Accept: application/json\r\n"
			)
		);

		$context  = stream_context_create( $options );
		$result = file_get_contents( $url, false, $context );
		$response = json_decode( $result );
		return $response;
    }
    
    static function startCommandMessage($sendMessage, $chatId, $responseMessage, $urlPhoto, $mainMenu) {
        $response = $sendMessage->sendTelegramMessage($chatId, $responseMessage, $urlPhoto);
        $sendMessage->sendTelegramMessage($chatId, 'Select a menu option to begin:', '', array(), array());
        $sendMessage->sendTelegramMessage($chatId, $mainMenu, '', array(), array());

        $chatMessages = json_decode(file_get_contents('assets/data.json'));
        $chatCode = "$chatId";
        $chatMessages->$chatCode = array("current_menu" => 1);
        file_put_contents('assets/data.json', json_encode($chatMessages));
    }

    static function sendCallBackMessage($sendMessage, $update, $chatId, $question) {
        $callBackId = $update['callback_query']['id'];

        $chatCode = "$chatId";

        $sendMessage->answerTocallBack($callBackId, $question);
        $sendMessage->sendTelegramMessage($chatId, $question);
    }
}