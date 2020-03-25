<?php
include_once('./class/Student.php');

$requestMethod = $_SERVER["REQUEST_METHOD"];

$Student = new Student();

switch($requestMethod)
{
    case 'GET':
        if(isset($_GET["id"]))
        {
            $Student->_id = $_GET["id"];
        }
        echo json_encode($Student->get());

    case 'POST'://Ok

        $Student->_name = $_POST["nome"];
        $Student->_surname = $_POST["cognome"];
        $Student->_sidi_code = $_POST["email"];
        $Student->_tax_code = $_POST["classe"];

        $data = $Student->insert();

        echo json_encode($_POST);
        break;

    case 'DELETE':
        if(isset($_GET["id"]))
        {
            $Student->_id = $_GET["id"];
        }
        else
        {
            header("HTTP/1.0 400 Bad Request");
        }
        $Student->delete();
        break;
    case 'PATCH':
        
        if(isset($_GET["id"]))
        {
            $Student->_id = $_GET["id"];
        }
        else
        {
            header("HTTP/1.0 400 Bad Request");
        }

        if(isset($_GET["nome"]))
        {
            $Student->_nome = $_GET["nome"];
        }
        if(isset($_GET["cognome"]))
        {
            $Student->_cognome = $_GET["cognome"];
        }
        if(isset($_GET["sidi_code"]))
        {
            $Student->_sidi_code = $_GET["sidi_code"];
        }
        if(isset($_GET["tax_code"]))
        {
            $Student->_tax_code = $_GET["tax_code"];
        }

        $Student->update();
    break;
    case 'PUT':
        
        if(isset($_GET["id"]))
        {
            $Student->_id = $_GET["id"];
        }
        else
        {
            header("HTTP/1.0 400 Bad Request");
        }

        if(isset($_GET["nome"]))
        {
            $Student->_nome = $_GET["nome"];
        }
        else
        {
            $Student->_nome = null;
        }
        if(isset($_GET["cognome"]))
        {
            $Student->_cognome = $_GET["cognome"];
        }
        else
        {
            $Student->_cognome = null;
        }
        if(isset($_GET["sidi_code"]))
        {
            $Student->_sidi_code = $_GET["sidi_code"];
        }
        else
        {
            $Student->_sidi_code = null;
        }
        if(isset($_GET["tax_code"]))
        {
            $Student->_tax_code = $_GET["tax_code"];
        }
        else
        {
            $Student->_tax_code = null;
        }

        $Student->update();
    break;
    default:
	    header("HTTP/1.0 405 Method Not Allowed");
	    break;
}
?>	