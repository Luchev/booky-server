<?php

require_once ROOT_FOLDER.'/classes/db.php';
require_once ROOT_FOLDER.'/classes/request.php';
require_once ROOT_FOLDER.'/classes/response.php';

function Terminate() {
  if (GetGlobalRequest()->GetValue('D') == 1) { // DEBUG
    echo '<pre>';
    echo json_encode(GetGlobalResponse()->GetObject(), JSON_PRETTY_PRINT);
    echo '</pre>';
  }
  else {
    echo(GetGlobalResponse()->GetString());
  }
  die();
}

function GetClientIp() {
    return $_SERVER['REMOTE_ADDR'];
}

function ThrowError($NewError = 0, $ErrorMessage = '') {
  $db = DB::Instance();
  $db->LogError($NewError, $ErrorMessage, GetGlobalRequest());

  $errorResponse = new Response();
  $errorResponse->SetValue('E', $NewError);
  $errorResponse->SetValue('S', 0);

  $GLOBALS['Response'] = $errorResponse;
  Terminate();
}

function ThrowErrorSafe($NewError = 0, $ErrorMessage = '') {
  $errorResponse = new Response();
  $errorResponse->SetValue('E', $NewError);
  $errorResponse->SetValue('S', 0);
  if ($ErrorMessage != '') {
    $errorResponse->SetValue('Error Message', $ErrorMessage);
  }

  $GLOBALS['Response'] = $errorResponse;
  Terminate();
}

function &GetGlobalResponse() {
  if (!isset($GLOBALS['Response'])) {
    $GLOBALS['Response'] = new Response();
  }
  return $GLOBALS['Response'];
}

function &GetGlobalRequest() {
  if (!isset($GLOBALS['Request'])) {
    $GLOBALS['Request'] = new Request('');
  }
  return $GLOBALS['Request'];
}

// TODO: Make use of \/
// Set the errors to be silent (to not be displayed to the client) unless specified so in the sent message
function SetCustomErrorHandling() {
  set_error_handler(function($errno, $errstr, $errfile, $errline, $errcontext) {
    $e['errno'] = $errno;
    $e['errstr'] = $errstr;
    $e['errfile'] = $errfile;
    $e['errline'] = $errline;
    $e['$errcontext'] = $errcontext;
    ThrowError(4, $e); // ERROR-4
  });
}

?>
