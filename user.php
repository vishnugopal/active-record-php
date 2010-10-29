<?php

require_once 'base.php';

class User extends ActiveRecord\Base {
  protected static $class = __CLASS__;
  
  public static $associations = array(
    'has_many' => array(
      'photos'
    )
  );
  function  __construct() {
      $this->association_key = 'has_many';
    }

};
