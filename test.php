<?php

require_once 'user.php';
require_once 'photo.php';

ActiveRecord::Base::establish_connection(array(
  'host' => 'localhost',
  'database' => 'test',
  'username' => 'root',
  'password' => 'root'
));

$user = User::find_all();
print_r($user[0]->photos());