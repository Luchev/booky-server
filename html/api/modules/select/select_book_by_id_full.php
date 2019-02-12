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
require_once ROOT_FOLDER.'/modules/select/select_author_by_id_full.php';
require_once ROOT_FOLDER.'/modules/select/select_book_by_id.php';
require_once ROOT_FOLDER.'/modules/select/select_book_authors_by_book_id.php';
require_once ROOT_FOLDER.'/modules/select/select_book_tags_by_book_id.php';

/**
 * @param int $Id
 * @return array|null
 */
function SelectBookByIdFull($Id) {
  $return['book'] = SelectBookById($Id);
  $authors = SelectBookAuthorsByBookId($Id);
  if (isset($authors)) {
    foreach ($authors as $item) {
      $return['authors'][] = SelectAuthorByIdFull($item);
    }
  }
  $tags = SelectBookTagsById($Id);
  if (isset($tags)) {
    foreach ($tags as $item) {
      $return['tags'][] = SelectTranslationByItemId(6, $item);
    }
  }
  if ($return['book']['series_id'] != 0) {
    $return['series'] = SelectTranslationByItemId(8, $return['book']['series_id']);
  }
  $return['translation'] = SelectTranslationByItemId(7, $return['book']['id']);
  if ($return['book']['language_id'] != 0) {
    $return['language'] = SelectTranslationByItemId(3, $return['book']['language_id']);
  }
  if ($return['book']['country_id'] != 0) {
    $return['country'] = SelectTranslationByItemId(2, $return['book']['country_id']);
  }

  return $return;
}


?>
