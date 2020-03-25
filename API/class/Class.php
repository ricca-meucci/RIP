<?php
/**
 * @package Meucci 4 Africa
 *
 * @author 
 *   
 */
 
include("DBConnection.php");
class Classe
{
    protected $db;
    public $_id;
    public $_year;
	public $_section;
 
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
    		$sql = 'INSERT INTO class (year, section)  VALUES (:year, :section)';
    		$data = [
			    'year' => $this->_year,
			    'section' => $this->_section,
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
				$sql = 'select * from class';
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
				$sql = 'select * from class where id = :id';
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
			$sql = 'delete from student_class where id_class = :id';
			$data = [
				'id' => $this->_id,
			];
			$stmt = $this->db->prepare($sql);
			$stmt->execute($data);

			$sql = 'delete from class where id = :id';
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
			$sql = 'UPDATE class SET ';

			if(isset($this->_year))
				$sql.=' year = :year,';

			if(isset($this->_section))
				$sql.=' section = :section';
			else
				$sql = substr_replace($sql, '', -1);
			
			$sql.=' WHERE id = :id';

    		$data = [
			    'id' => $this->_id,
			    'year' => $this->_year,
			    'section' => $this->_section,
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