<?php
/**
 * Function for adding new authors to the DB
 *
 * @category      Modules
 * @package       DB-Additions
 * @subpackage    Select
 * @author        Luchev <luchevz@gmail.com>
 * @version       1.0
 * @since         0.1.0
 */

// require_once ROOT_FOLDER.'\classes\db.php';
// require_once ROOT_FOLDER.'\modules\add_translation.php';
//
// /**
//  * @param   Array $author which is key-value array where the keys are the column names from the DB
//  * @return  TRUE|FALSE on success|failure
// */
// function AddAuthor($author) {
//   $db = DB::Instance();
//
//   if (isset($author['name'])) {
//     $name = $author['name'];
//   }
//   if (isset($author['pen_name']))  {
//     $pen_name = $author['pen_name'];
//   }
//   unset($author['name']);
//   unset($author['pen_name']);
//
//   if (!isset($author['born_year'])) {
//     $author['born_year'] = 0;
//   }
//
//   if (!$db->Insert('author', $author)) {
//     return FALSE;
//   }
//
//   $lastId = $db->GetMax('author', 'id');
//
//   $name['type_id'] = 4;
//   $name['item_id'] = $lastId;
//   AddTranslation($name);
//
//   $pen_name['type_id'] = 5;
//   $pen_name['item_id'] = $lastId;
//   AddTranslation($pen_name);
//
//   return 1;
// }


?>
