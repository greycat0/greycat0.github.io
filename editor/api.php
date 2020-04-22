<?php
require_once('helper.php');

session_start();
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : false;
$response = handle_request($action);
return print($response);

function handle_request($action)
{
    $response = process_request($action);
    $response_type = gettype($response);
    if ($response_type == 'object' || $response_type == 'array') {
        header("Content-type: application/json");
        $response = json_encode($response);
    } else {
        $response = strval($response);
    }
    return $response;
}
function process_request($action)
{
    if ($action == "auth") {
        $password = $_REQUEST['password'];
        if (getdotenv('PASSWORD') == hash('sha256', $password)) {
            $_SESSION['auth'] = true;
            return (object) ["status" => 1];
        } else {
            return (object) ["status" => -1];
        }
    } else if ($action == 'update-node') {
        try {
            $doc = new DOMDocument();
            $doc->loadHTMLFile('../index.html');
            $xpath = new DOMXpath($doc);
            foreach ($_REQUEST['changes'] as $change) {
                if ($change[0][0] == ':') {
                    $element = $doc->getElementById(ltrim($change[0], ':'))->firstChild;
                } else {
                    $element = $xpath->query($change[0])[0]->firstChild;
                }

                $value = $change[1];
                $element->data = $value;
            }
            file_put_contents('../index.html', html_entity_decode($doc->saveHTML()));
            return (object) ["status" => 1];
        } catch (Exception $e) {
            return (object) ["status" => -1];
        }
    }
}
