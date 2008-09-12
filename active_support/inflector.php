<?php
namespace ActiveSupport;

class Inflector {
  
  public static function singularize($value) {
    return strtolower(substr($value, 0, strlen($value) - 1));
  }
  
  public static function pluralize($value) {
    return strtolower($value . 's');
  }
  
}
