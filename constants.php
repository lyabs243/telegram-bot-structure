<?php

const APP_NAME = 'APP_NAME';
const BASE_URL = "BASE_URL";

const TELEGRAM_TOKEN = 'YOUR_BOT_TOKEN';

const BOT_USERNAME = 'YOUR_BOT_USERNAME';

const BOT_IMAGE_URL = 'IMAGE_URL';

function welcomeMessage($fullName) {
    return "Welcome $fullName to " . APP_NAME . "";
}