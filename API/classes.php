<?php
include_once('./class/Class.php');

$requestMethod = $_SERVER["REQUEST_METHOD"];

$class = new Classe();

switch($requestMethod)
{
    case 'GET':
        if(isset($_GET["id"]))
        {
            $class->_id = $_GET["id"];
        }
        echo json_encode($class->get());

    case 'POST'://Ok

        $class->_name = $_POST["year"];
        $class->_surname = $_POST["section"];

        $data = $class->insert();

        echo json_encode($_POST);
        break;

    case 'DELETE':
        if(isset($_GET["id"]))
        {
            $class->_id = $_GET["id"];
        }
        else
        {
            header("HTTP/1.0 400 Bad Request");
        }
        $class->delete();
        break;
    case 'PATCH':
        
        if(isset($_GET["id"]))
        {
            $class->_id = $_GET["id"];
        }
        else
        {
            header("HTTP/1.0 400 Bad Request");
        }

        if(isset($_GET["year"]))
        {
            $class->_year = $_GET["year"];
        }
        if(isset($_GET["section"]))
        {
            $class->_section = $_GET["section"];
        }

        $class->update();
    break;
    case 'PUT':
        
        if(isset($_GET["id"]))
        {
            $class->_id = $_GET["id"];
        }
        else
        {
            header("HTTP/1.0 400 Bad Request");
        }

        if(isset($_GET["year"]))
        {
            $class->_year = $_GET["year"];
        }
        else
        {
            $class->_year = null;
        }
        if(isset($_GET["section"]))
        {
            $class->_section = $_GET["section"];
        }
        
        $class->update();
    break;
    default:
	    header("HTTP/1.0 405 Method Not Allowed");
	    break;
}
?>	