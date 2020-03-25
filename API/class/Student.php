<?php
/**
 * @package Meucci 4 Africa
 *
 * @author 
 *   
 */
 
include("DBConnection.php");
class Studente 
{
    protected $db;
    public $_id;
    public $_nome;
	public $_cognome;
	public $_sidi_code;
	public $_tax_code;
	
 
	public function __construct()
	{
        $this->db = new DBConnection();
        $this->db = $this->db->returnConnection();
    }
 
    //insert
	public function insert()
	{
		/*
		Nella prima parte esegue l' aggiunta del nuovo student
		*/
		try
		{
    		$sql = 'INSERT INTO student (name, surname, sidi_code, tax_code)  VALUES (:name, :surname, :sidi_code, :tax_code)';
    		$data = [
			    'name' => $this->_name,
			    'surname' => $this->_surname,
			    'sidi_code' => $this->_sidi_code,
				'tax_code' => $this->_tax_code,
			];
	    	$stmt = $this->db->prepare($sql);
	    	$stmt->execute($data);
			$status = $stmt->rowCount();
 
		}
		catch (Exception $e)
		{
			header("HTTP/1.0 400 Bad request");
			echo $e;
		}
	}	
	public function get()
	{
		/*
		Nella prima parte esegue l' aggiunta del nuovo student
		*/
		if(isset( $this->_id ))
		{
			try
			{
				$sql = 'select * from student';
				$stmt = $this->db->prepare($sql);
				$stmt->execute();
				$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
				return $result;			
	
			}
			catch (Exception $e)
			{
				header("HTTP/1.0 500 Internal server error");
				echo $e;
			}
		}
		else
		{
			try
			{
				$sql = 'select * from student where id = :id';
				$data = [
					'id' => $this->_id,

				];
				$stmt = $this->db->prepare($sql);
				$stmt->execute($data);
				$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
				return $result;			
	
			}
			catch (Exception $e)
			{
				header("HTTP/1.0 400 Bad request");
				echo $e;
			}
		}
	}	

	public function delete()
	{
		try
		{
			$sql = 'delete from student_class where id_student = :id';
			$data = [
				'id' => $this->_id,
			];
			$stmt = $this->db->prepare($sql);
			$stmt->execute($data);

			$sql = 'delete from student where id = :id';
			$data = [
				'id' => $this->_id,
			];
			$stmt = $this->db->prepare($sql);
			$stmt->execute($data);		

		}
		catch (Exception $e)
		{
			header("HTTP/1.0 400 Bad request");
			echo $e;
		}
	}

	public function update()
	{
		/*
		Nella prima parte esegue l' aggiunta del nuovo student
		*/
		try
		{
			$sql = 'UPDATE student SET ';

			if(isset($this->_name))
				$sql.=' name = :name,';

			if(isset($this->_surname))
				$sql.=' surname = :surname,';
			
			if(isset($this->_sidi_code))
				$sql.=' sidi_code = :sidi_code,';

			if(isset($this->_tax_code))
				$sql.=' tax_code = :tax_code';
			else
				$sql = substr_replace($sql, '', -1);
			
			$sql.=' WHERE id = :id';

    		$data = [
			    'id' => $this->_id,
			    'name' => $this->_name,
			    'surname' => $this->_surname,
			    'sidi_code' => $this->_sidi_code,
				'tax_code' => $this->_tax_code,
			];

	    	$stmt = $this->db->prepare($sql);
	    	$stmt->execute($data);
			$status = $stmt->rowCount();
		}
		catch (Exception $e)
		{
			header("HTTP/1.0 400 Bad request");
			echo $e;
		}
	}
}
?>