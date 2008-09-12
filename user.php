<?php

require_once 'base.php';

class User extends ActiveRecord::Base {
  protected static $class = __CLASS__;
  
  public static $associations = array(
    'has_many' => array(
      'photos' => array(
        'foreign_key_field' => 'user_id',
        'table_name' => 'photos',
        'model' => 'Photo'
      )
    )
  );
};

