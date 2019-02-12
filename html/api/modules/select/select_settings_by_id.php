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
function SelectSettingsById($Id) {
  if (!is_int($Id)) {
    ThrowError(21);
  }
  $db = DB::Instance();
  $result = $db->ExecutePreparedStatement('select_settings_by_id', $Id);
  if ($result !== NULL && isset($result[0])) {
    return $result[0];
  }
  return NULL;
}


?>
