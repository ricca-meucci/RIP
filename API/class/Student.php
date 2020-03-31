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
    public $_name;
	public $_surname;
	public $_sidi_code;
	public $_tax_code;
	public $_class;
	
 
	public function __construct()
	{
        $this->db = new DBConnection();
        $this->db = $this->db->returnConnection();
    }
 
    //insert
	public function insert($class)
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
			
			$student = $this->get();

    		$sql = 'INSERT INTO student_class (id_student, id_class)  VALUES (:s, :c)';
    		$data = [
			    's' => $student[0]["id"],
			    'c' => $class,
			];
	    	$stmt = $this->db->prepare($sql);
			$stmt->execute($data);

			return $student; 
		}
		catch (Exception $e)
		{
			header("HTTP/1.0 400 Bad request");
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
				$sql = 'select * from student where id = :id';
				$stmt = $this->db->prepare($sql);
				$data = [
					'id' => $this->_id
				];
				$stmt->execute($data);
				$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
				return $result;			
	
			}
			catch (Exception $e)
			{
				header("HTTP/1.0 500 Internal server error");
				echo $e;
			}
		}
		else if(isset( $this->_tax_code ))
		{
			try
			{
				$sql = 'select * from student where tax_code = :tax';
				$stmt = $this->db->prepare($sql);
				$data = [
					'tax' => $this->_tax_code
				];
				$stmt->execute($data);
				$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
				return $result;		
			}
			catch (Exception $e)
			{
				header("HTTP/1.0 500 Internal server error");
			}
		}
		else
		{
			try
			{
				$sql = 'select s.*, c.section
				from student s
				inner join student_class sc
				on sc.id_student = s.id
				inner join class c
				on sc.id_class = c.id
				order by c.section, s.surname, s.name';
				$stmt = $this->db->prepare($sql);
				$stmt->execute();
				$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
				return $result;			
	
			}
			catch (Exception $e)
			{
				header("HTTP/1.0 400 Bad request");
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
		}
	}

	public function update()
	{
		/*
		Nella prima parte esegue l' aggiunta del nuovo student
		*/
		try
		{
			$data["id"] = $this->_id;

			$sql = 'UPDATE student SET ';

			if(isset($this->_name))
			{
				$sql.=' name = :name,';
				$data["name"] = $this->_name;
			}

			if(isset($this->_surname))
			{
				$sql.=' surname = :surname,';
				$data["surname"] = $this->_surname;
			}
			
			if(isset($this->_sidi_code))
			{
				$sql.=' sidi_code = :sidi_code,';
				$data["sidi_code"] = $this->_sidi_code;
			}

			if(isset($this->_tax_code))
			{
				$sql.=' tax_code = :tax_code';
				$data["tax_code"] = $this->_tax_code;
			}
			else
				$sql = substr_replace($sql, '', -1);
			
			$sql.=' WHERE id = :id';

			echo $sql;

	    	$stmt = $this->db->prepare($sql);
	    	$stmt->execute($data);
			$status = $stmt->rowCount();

			if(isset($this->_class))
			{
				if($this->_class == "")
				{
					header("HTTP/1.0 400 Bad request");
				}
				else
				{
					$sql = 'UPDATE student_class SET id_class = :c WHERE id_student = :s';
					$data = [
						's' => $this->_id,
						'c' => $this->_class,
					];
					$stmt = $this->db->prepare($sql);
					$stmt->execute($data);

				}
			}
		}
		catch (Exception $e)
		{
			header("HTTP/1.0 400 Bad request");
			echo $e;
		}
	}
}
?>