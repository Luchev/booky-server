<?php
/**
 *  Object responsible for receiving the JSON message from the APP and
 *  parsing it to an object, later to expose that object to the rest of the API
 *
 * @category      Classes
 * @author        Luchev <luchevz@gmail.com>
 * @version       1.0
 * @since         0.1.0
 */

class Request {
  private $string;
  private $object;

  public function __construct($String) {
    if (is_string($String)) {
      $this->string = $String;
    }
    else {
      ThrowError(2); // ERROR-2
    }

    if ($this->string != '') {
      $this->object = json_decode($this->string, TRUE);
    } else {
      $this->object = array();
    }

    if ($this->object === NULL) {
      ThrowError(3); // ERROR-3
    }
  }

  function GetValue($Key) {
    if (isset($this->object[$Key])) {
      return $this->object[$Key];
    }
    return NULL;
  }

  function SetValue($Key, $Value) {
    $this->object[$Key] = $Value;
  }

  function GetObject() {
    return $this->object;
  }

  function GetString() {
    return $this->string;
  }

  function GetMessageType() {
    if (isset($this->object['MT'])) {
      return $this->object['MT'];
    }
    return 0;
  }

  function GetMessageCode() {
    if (isset($this->object['MC'])) {
      return $this->object['MC'];
    }
    return 0;
  }

  function GetPreferredLanguage() {
    if (isset($this->object['LANG'])) {
      if (is_int($this->object['LANG'])) {
        require_once ROOT_FOLDER.'/modules/select/select_translation_by_item_id.php';
        $result = SelectTranslationByItemId(3, $this->object['LANG']);
        if ($result !== NULL) {
          $this->object['LANG'] = $result['special'];
          return $this->object['LANG'];
        }
        return NULL;
      }
      else if (is_string($this->object['LANG'])) {
        return $this->object['LANG'];
      }
      return NULL;
    }
    return NULL;
  }

  function GetUserId() {
    // TODO: add more cases
    return 0;
  }

}

?>
