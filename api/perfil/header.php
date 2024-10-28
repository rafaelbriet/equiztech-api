<?php

require('../autenticacao/functions.php');

only_logged_users();

require_once('../../dbconnection.php');
require 'ProfileRepository.php';