<?php

require_once 'base.php';

class Photo extends ActiveRecord\Base {
  protected static $class = __CLASS__;
  public static $associations = array(
    'belongs_to' => array(
      'user' => array(
          'foreign_key_field' => 'id'
      )
    )
  );

  function  __construct() {
      $this->association_key = 'belongs_to';
    }
  
};