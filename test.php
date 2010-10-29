<?php

require_once 'user.php';
require_once 'photo.php';

ActiveRecord\Base::establish_connection(array(
  'host' => '127.0.0.1',
  'database' => 'test',
  'username' => 'root',
  'password' => ''
));

$users = User::find_all();
$user = $users[0];
$user->user_name_set("Love");
$user->save();
print_r($user->photos());
print_r($user);