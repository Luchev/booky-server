<?php

const ROOT_FOLDER = __DIR__; // The root of the API. Used for include and require

// TODO: On release uncomment
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

require_once ROOT_FOLDER.'/utilities/lib.php';
require_once ROOT_FOLDER.'/classes/request.php';
require_once ROOT_FOLDER.'/classes/response.php';
require_once ROOT_FOLDER.'/classes/db.php';

if (isset($_POST['request'])) {
  $Request = new Request($_POST['request']);
}
else {
  $Request = new Request('');
  ThrowError(1);
}
$Response = new Response();

// DEBUG
// $Request->SetValue('MC', 6);
// $Request->SetValue('IDS', [1, 2, 3]);
// $Request->SetValue('D', 1);

$messageCode = $Request->GetMessageCode();
require_once ROOT_FOLDER.'/scripts/parse_message_code.php';

switch ($messageCode) {
  case 1:
    $Response->SetValue('R', ParseSelectBookByIdFull($Request));
    break;
  case 2:
    $Response->SetValue('R', ParseSelectAuthorByIdFull($Request));
    break;
  case 3:
    $Response->SetValue('R', ParseSelectAllTags());
    break;
  case 4:
    $Response->SetValue('R', ParseSelectBooksByIdFull($Request));
    break;
  case 5:
    $Response->SetValue('R', ParseSelectBookByIdMin($Request));
    break;
  case 6:
    $Response->SetValue('R', ParseSelectBooksByIdMin($Request));
    break;
  default:
    ThrowError(20);
    break;
}

$Response->SetValue('S', 1);
Terminate();

?>
