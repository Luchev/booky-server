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
 * @return array|null
 */
function SelectAllTags() {
  $db = DB::Instance();
  $result = $db->ExecutePreparedStatement('select_translation_by_type_id', 6);
  if ($result === NULL) {
    return NULL;
  }
  foreach ($result as &$item) {
    unset($item['type_id']);
  }
  return $result;
}


?>
