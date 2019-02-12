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
require_once ROOT_FOLDER.'/modules/select/select_translation_by_item_id.php';

/**
 * @param int $Id
 * @return array|null
 */
function SelectBookByIdMin($Id) {
  if (!is_int($Id)) {
    ThrowError(21);
  }
  $db = DB::Instance();
  $result = $db->ExecutePreparedStatement('select_book_by_id_min', $Id);
  $return = array();
  if ($result !== NULL && isset($result[0])) {
    $return['book'] = $result[0];
    $return['translation'] = SelectTranslationByItemId(7, $return['book']['id']);
    return $return;
  }
  return NULL;
}

?>
