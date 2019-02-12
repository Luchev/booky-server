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
 * @param int $Id
 * @return array|null
 */
function SelectBookAuthorsByBookId($Id) {
  if (!is_int($Id)) {
    ThrowError(21);
  }
  $db = DB::Instance();
  $result = $db->ExecutePreparedStatement('select_book_authors_by_book_id', $Id);
  if ($result === NULL) {
    return NULL;
  }
  $return = array();
  foreach ($result as $item) {
    $return[] = $item['author_id'];
  }

  return $return;
}


?>
