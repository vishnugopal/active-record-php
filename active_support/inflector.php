<?php
namespace ActiveSupport;

class Inflector {
  
  public static function singularize($value) {
      if("s" == substr($value, -1)) {
          return strtolower(substr($value, 0, strlen($value) - 1));
      }else {
          return $value;
      }

  }
  
  public static function pluralize($value) {
    if("s" != substr($value, -1)) {
          return strtolower($value . 's');
      }else {
          return $value;
      }
  }
  
}
