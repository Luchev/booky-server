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
require_once ROOT_FOLDER.'/modules/select/select_author_by_id.php';
require_once ROOT_FOLDER.'/modules/select/select_translation_by_item_id.php';

/**
 * @param int $Id
 * @return array|null
 */
function SelectAuthorByIdFull($Id) {
  $return['author'] = SelectAuthorById($Id);
  $return['translation']['name'] = SelectTranslationByItemId(4, $Id);
  $return['translation']['pen_name'] = SelectTranslationByItemId(5, $Id);
  if (isset($return['author']['country_id']) && $return['author']['country_id'] != 0) {
    $return['country'] = SelectTranslationByItemId(2, $return['author']['country_id']);
  }

  return $return;
}


?>
