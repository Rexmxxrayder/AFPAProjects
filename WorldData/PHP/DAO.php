<?php
    class DAO
    {
		private $host="127.0.0.1";
		private $user="root";
		private $password="";
		private $database="pays";
		private $charset="utf8";
		
		private $bdd;
		
		private $error;

        public function __construct()
        {
        }

        public function Connect()
        {
            try {
                $this->bdd = new PDO('mysql:host='.$this->host.';dbname='.$this->database.';charset='.$this->charset, $this->user, $this->password);
                $this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
        }

		public function Disconnect() {
			$this->bdd=null;
		}
		
		public function GetLastError() {
			return $this->error;
		}

		public function Query($query) {
			$stmt = $this->bdd->query($query);
			
			if (!$stmt) {
				$this->error=$this->bdd->errorInfo();
				return false;
			} else {
				return $stmt->fetchAll();
			}
			
		}
	}
?>

	
