<?php

session_start();
require_once '../classes/vendor/autoload.php';
$cliente = new Google_Client();
$cliente->setApplicationName('correoDWES');
$cliente->setClientId('57979040865-683aq00432672sr9n91nphc2ff36hk9v.apps.googleusercontent.com');
$cliente->setClientSecret('gqC1Q8cFH5vifrU2jDRHWDdx');
$cliente->setRedirectUri('https://dwse-hisouten.c9users.io/proyecto/gmail/obtenercredenciales.php');
$cliente->setScopes('https://www.googleapis.com/auth/gmail.compose');
$cliente->setAccessType('offline');
if (!$cliente->getAccessToken()) {
    $auth = $cliente->createAuthUrl();
    header("Location: $auth");
}