<?php
include_once('./class/Student.php');

$requestMethod = $_SERVER["REQUEST_METHOD"];
$explodedURI = explode("/", $_SERVER['REQUEST_URI']);
$reqID = is_numeric(end($explodedURI)) ? end($explodedURI) : null;

$Student = new Studente();

switch($requestMethod)
{
    case 'GET':
        if($reqID != null)
        {
            $Student->_id = $reqID;
        }
        echo json_encode($Student->get());
    break;
    case 'POST'://Ok

        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);

        $Student->_name = $input["name"];
        $Student->_surname = $input["surname"];
        $Student->_sidi_code = $input["sidi"];
        $Student->_tax_code = $input["tax"];
        $Student->_class = $input["class"];

        $data = $Student->insert();

        echo json_encode($data);
        break;

    case 'DELETE':
        if($reqID != null)
        {
            $Student->_id = $reqID;
        }
        else
        {
            header("HTTP/1.0 400 Bad Request");
        }
        $Student->delete();
        break;
    case 'PATCH':

        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        
        if($reqID != null)
        {
            $Student->_id = $reqID;
        }
        else
        {
            header("HTTP/1.0 400 Bad Request");
        }

        if(isset($input["name"]))
        {
            $Student->_name = $input["name"];
        }
        if(isset($input["surname"]))
        {
            $Student->_surname = $input["surname"];
        }
        if(isset($input["sidi"]))
        {
            $Student->_sidi_code = $input["sidi"];
        }
        if(isset($input["tax"]))
        {
            $Student->_tax_code = $input["tax"];
        }
        if(isset($input["class"]))
        {
            $Student->_class = $input["class"];
        }

        $Student->update();
    break;
    case 'PUT':

        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        
        if($reqID != null)
        {
            $Student->_id = $reqID;
        }
        else
        {
            header("HTTP/1.0 400 Bad Request");
        }

        if(isset($input["name"]))
        {
            $Student->_name = $input["name"];
        }
        else
        {
            $Student->_name = "";
        }
        if(isset($input["surname"]))
        {
            $Student->_surname = $input["surname"];
        }
        else
        {
            $Student->_surname = "";
        }
        if(isset($input["sidi"]))
        {
            $Student->_sidi_code = $input["sidi"];
        }
        else
        {
            $Student->_sidi_code = "";
        }
        if(isset($input["tax"]))
        {
            $Student->_tax_code = $input["tax"];
        }
        else
        {
            $Student->_tax_code = "";
        }
        if(isset($input["class"]))
        {
            $Student->_class = $input["class"];
        }
        else
        {
            $Student->_class = "";
        }

        $Student->update();
    break;
    default:
	    header("HTTP/1.0 405 Method Not Allowed");
	    break;
}
?>	