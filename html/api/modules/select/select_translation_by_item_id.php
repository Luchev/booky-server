<?php
/**
 * @category      Modules
 * @package       DB-Additions
 * @subpackage    Select
 * @uses          DB
 * @author        Luchev <luchevz@gmail.com>
 * @version       1.0
 * @since         0.1.0
 * @example
 */

require_once ROOT_FOLDER.'/classes/db.php';
require_once ROOT_FOLDER.'/utilities/lib.php';

/**
 * @param int $typeId
 * @param int $itemId
 * @return array|null
 */
function SelectTranslationByItemId($typeId, $itemId) {
  if (!is_int($typeId) || !is_int($itemId)) {
    ThrowError(21);
  }
  $db = DB::Instance();
  $result = $db->ExecutePreparedStatement('select_translation_by_item_id',
    array($typeId, $itemId));
  if ($result !== NULL && isset($result[0])) {
    unset($result[0]['type_id']);
    // unset($result[0]['item_id']);
    return $result[0];
  }
  return NULL;
}

?>
