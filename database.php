<?php

const DB_HOST = 'DATABASE_HOST';
const DB_NAME = 'DATABASE_NAME';
const DB_USERNAME  = 'DATABASE_USERNAME';
const DB_PASSWORD = 'DATABASE_PASSWORD';

try {
    $dbConnection = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);
} catch (PDOException $e) {
    print "Error !: " . $e->getMessage() . "<br/>";
    die();
}