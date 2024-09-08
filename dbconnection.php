<?php

function create_connection() {
    $hostname = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'equiztech_api';

    return new mysqli($hostname, $username, $password, $database);
}