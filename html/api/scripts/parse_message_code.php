<?php
/**
 * @category      Scripts
 * @package       DB-Additions
 * @uses          DB
 * @author        Luchev <luchevz@gmail.com>
 * @version       1.0
 * @since         0.1.0
 * @example
 */

function ParseSelectBookByIdFull($Request) {
 $id = $Request->GetValue('ID');
 if ($id !== NULL) {
   require_once ROOT_FOLDER.'/modules/select/select_book_by_id_full.php';
   return SelectBookByIdFull($id);
 } else {
   ThrowError(19);
 }
}

function ParseSelectAuthorByIdFull($Request) {
 $id = $Request->GetValue('ID');
 if ($id !== NULL) {
   require_once ROOT_FOLDER.'/modules/select/select_author_by_id_full.php';
   return SelectAuthorByIdFull($id);
 } else {
   ThrowError(19);
 }
}

function ParseSelectAllTags() {
  require_once ROOT_FOLDER.'/modules/select/select_all_tags.php';
  return SelectAllTags();
}

function ParseSelectBooksByIdFull($Request) {
 $ids = $Request->GetValue('IDS');
 if ($ids !== NULL) {
   require_once ROOT_FOLDER.'/modules/select/select_books_by_id_full.php';
   return SelectBooksByIdFull($ids);
 } else {
   ThrowError(19);
 }
}

function ParseSelectBookByIdMin($Request) {
 $id = $Request->GetValue('ID');
 if ($id !== NULL) {
   require_once ROOT_FOLDER.'/modules/select/select_book_by_id_min.php';
   return SelectBookByIdMin($id);
 } else {
   ThrowError(19);
 }
}

function ParseSelectBooksByIdMin($Request) {
 $ids = $Request->GetValue('IDS');
 if ($ids !== NULL) {
   require_once ROOT_FOLDER.'/modules/select/select_books_by_id_min.php';
   return SelectBooksByIdMin($ids);
 } else {
   ThrowError(19);
 }
}

?>
