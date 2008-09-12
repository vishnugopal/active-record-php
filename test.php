<?php

require_once 'user.php';
require_once 'photo.php';

ActiveRecord::Base::establish_connection(array(
  'host' => 'localhost',
  'database' => 'test',
  'username' => 'root',
  'password' => 'root'
));

$users = User::find_all();
$user = $users[0];
$user->user_name_set("Love");
$user->save();
print_r($user->photos());
print_r($user);