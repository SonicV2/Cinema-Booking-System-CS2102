<?php
	class TicketDB
	{
		// single instance of self shared among all instances
		private static $instance = null;

		// db connection config vars
		private $user = 'A0094762';
		private $pass = 'crse1410';
		private $dbName = "ticketlist";
		private $dbHost = sid3;
		private $dbh = null;
		
		//This method must be static, and must return an instance of the object if the object
		//does not already exist.
		public static function getInstance()
		{
		  if (!self::$instance instanceof self)
		  {
			self::$instance = new self;
		  }
		  return self::$instance;
		}

		// The clone and wakeup methods prevents external instantiation of copies of the Singleton class,
		// thus eliminating the possibility of duplicate objects.
		public function __clone()
		{
		  trigger_error('Clone is not allowed.', E_USER_ERROR);
		}
		public function __wakeup()
		{
		  trigger_error('Deserializing is not allowed.', E_USER_ERROR);
		}
		
		// private constructor
		private function __construct()
		{
			putenv('ORACLE_HOME=/oraclient');
			$this->dbh = oci_connect($this->user, $this->pass, $this->dbHost);
			if (!$this->dbh) {
				$m = oci_error();
				echo $m['message'], "\n";
				exit;
			}
		}
		
		public function get_customer_email($email)
		{
			$sql = "SELECT email FROM Customer WHERE email = :user_bv";
			$stid = oci_parse($this->dbh, $sql);
			oci_bind_by_name($stid, ':user_bv', $email);
			oci_execute($stid);
			//Because user email is a unique value I only expect one row
			$row = oci_fetch_array($stid, OCI_ASSOC);
			if ($row) 
				return $row["EMAIL"];
			else
				return null;
		}
		
		public function get_tickets_of_customer($customerEmail)
		{
			$sql = "SELECT DISTINCT M.title, V.digital, V.threeD, V.language, V.subtitles,
							   MT.cinema, MT.mDate, MT.mTime, MT.hall, MT.seat, MT.ticketID
					FROM Movie M, Version V, MovieTicket MT
					WHERE M.movieID = V.movieID AND
						  V.movieID = MT.movieID AND
						  V.versionID = MT.versionID AND
						  MT.email = :email_bv
					ORDER BY M.title ASC, MT.cinema ASC, MT.mDate ASC, MT.mTime ASC, MT.ticketID ASC";
			$stid = oci_parse($this->dbh, $sql);
			oci_bind_by_name($stid, ":email_bv", $customerEmail);
			oci_execute($stid);
			return $stid;
		}
		
		public function get_all_customer_emails()
		{
			$sql = "SELECT C.email
					FROM Customer C
					ORDER BY C.email ASC";
			$stid = oci_parse($this->dbh, $sql);
			oci_execute($stid);
			return $stid;
		}
		
		public function create_customer($email, $password, $ccnum)
		{
			$sql = "INSERT INTO Customer (email, password, ccnum)
					VALUES (:user_bv, :pwd_bv, :ccnum_bv)";
			$stid = oci_parse($this->dbh, $sql);
			oci_bind_by_name($stid, ':user_bv', $email);
			oci_bind_by_name($stid, ':pwd_bv', $password);
			oci_bind_by_name($stid, ':ccnum_bv', $ccnum);
			oci_execute($stid);
		}
		
		public function verify_customer_credentials($email, $password)
		{
			$sql = "SELECT 1 FROM Customer WHERE email = :email_bv AND password = :pwd_bv";
			$stid = oci_parse($this->dbh, $sql);
			oci_bind_by_name($stid, ':email_bv', $email);
			oci_bind_by_name($stid, ':pwd_bv', $password);
			oci_execute($stid);
			//Because name is a unique value I only expect one row
			$row = oci_fetch_array($stid, OCI_ASSOC);
			if ($row) 
				return true;
			else
				return false;
		}
		
		public function delete_booking($ticketID)
		{
			$sql = "UPDATE MovieTicket SET email = null WHERE ticketID = :ticket_id_bv";
			$stid = oci_parse($this->dbh, $sql);
			oci_bind_by_name($stid, ':ticket_id_bv', $ticketID);
			oci_execute($stid);
			oci_free_statement($stid);
		}
		
		public function edit_booking($ticketID, $email)
		{
			$sql = "UPDATE MovieTicket SET email = :email_bv WHERE ticketID = :ticket_id_bv";
			$stid = oci_parse($this->dbh, $sql);
			oci_bind_by_name($stid, ':email_bv', $email);
			oci_bind_by_name($stid, ':ticket_id_bv', $ticketID);
			oci_execute($stid);
			oci_free_statement($stid);
		}
	}
?>
