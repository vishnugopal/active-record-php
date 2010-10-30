<?php

require_once 'base.php';

class Tag extends ActiveRecord\Base {
  protected static $class = __CLASS__;
  public static $associations = array(
    'belongs_to' => array(
      'photo' => array(
          'foreign_key_field' => 'id'
      )
    )
  );

};