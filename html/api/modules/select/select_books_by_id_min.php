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

require_once ROOT_FOLDER.'/utilities/limits.php';
require_once ROOT_FOLDER.'/modules/select/select_book_by_id_min.php';

/**
 * @param int $Ids
 * @return array|null
 */
function SelectBooksByIdMin($Ids) {
  $return = array();
  $counter = 0;
  foreach ($Ids as $item) {
    $return[] = SelectBookByIdMin($item);
    $counter++;
    if ($counter >= LIMITS::MAX_BOOK_IDS) {
      break;
    }
  }
  if ($counter === 1) {
    return NULL;
  }
  return $return;
}


?>
