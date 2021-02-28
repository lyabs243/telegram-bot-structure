<?php

$userJsonFile = "data/users/$chatId.json";

//if user json file doesn't exist, create it
if (! file_exists($userJsonFile)) {
    $userData = array(
        'current_state' => 0
    );
    file_put_contents($userJsonFile, json_encode($userData));
}

$data = json_decode(file_get_contents($userJsonFile));
                    