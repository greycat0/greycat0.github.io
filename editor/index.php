<?php
require_once('helper.php');

session_start();
$response = handle_request();
return print($response);

function handle_request()
{
    $response = process_request();
    $response_type = gettype($response);
    if ($response_type == 'object' || $response_type == 'array') {
        header("Content-type: application/json");
        $response = json_encode($response);
    } else {
        $response = strval($response);
    }
    return $response;
}
function process_request()
{
    $doc = new DOMDocument();
    if (isset($_SESSION['auth']) && $_SESSION['auth']) {
        $doc->loadHTMLFile('../index.html');
        $script = '/editor/dist/main.js';
    } else {
        $doc->loadHTMLFile('page.html');
        $script = '/editor/dist/login.js';
    }
    $scriptNode = $doc->createElement("script");
    $scriptNode->setAttribute('src', $script);
    $bodyNode = $doc->getElementsByTagName('body')[0];
    $bodyNode->appendChild($scriptNode);
    return $doc->saveHTML();
}