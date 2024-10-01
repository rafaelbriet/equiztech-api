<?php

function create_connection() {
    $hostname = $_ENV['DB_HOSTNAME'];
    $username = $_ENV['DB_USERNAME'];
    $password = $_ENV['DB_PASSWORD'];
    $database = $_ENV['DB_NAME'];

    return new mysqli($hostname, $username, $password, $database);
}