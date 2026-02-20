<?php 
    class Division extends User implements REmployee, RIncomingMail, ROutgoingMail {
        # ============================================================================================================== Division ==== #
        public function __construct(int $user_id) {
            parent::__construct($user_id);      
        }

        public function get(string $field) : string {
            if (in_array($field, $this -> user_columns_name)) {
                return parent::get($field);
            } else {
                $stmt = ($this -> db_connection) -> prepare("SELECT $field FROM \"Division\" WHERE user_id = ?"); 
                $stmt -> execute([parent::get("id")]);
                return $stmt -> fetchColumn();
            }
        }
        
        public function set(string $field, string $value) : void {
            if (in_array($field, $this -> user_columns_name)) {
                parent::set($field, $value);
            } else {
                $stmt = ($this -> db_connection) -> prepare("UPDATE \"Division\" SET $field = ? WHERE user_id = ?");
                $stmt -> execute([$value, parent::get("id")]);
            }
        }

        public static function get_with_self_id(int $division_id) : Division {
            $db_connection = Database::connect();
            $stmt = $db_connection -> prepare("SELECT user_id FROM \"Division\" WHERE id = ?"); 
            $stmt -> execute([$division_id]);
            return new Division(user_id: $stmt -> fetchColumn());
        }
        # ========================================================================================================= Notifications ==== #
        public function get_notifications() : array {
            $notifications = array();
            $stmt = ($this -> db_connection) -> prepare("SELECT * FROM \"Notification\" where LOWER(receiver) = LOWER(?)"); 
            $stmt -> execute([$this -> get("name")]);
            while ($notification = $stmt -> fetch(PDO::FETCH_OBJ)) {
                array_push($notifications, $notification);
            }
            return $notifications;
        }

        public function clear_notifications() : void {
            $stmt = ($this -> db_connection) -> prepare("DELETE FROM \"Notification\" where receiver = LOWER(?)"); 
            $stmt -> execute([$this -> get("name")]);
        }
        # ================================================================================================== Dashboard Statistics ==== #
        function get_mails_statistics_between(string $start_year, string $end_year) : array {
            $years_interval = range($start_year, $end_year);
            $division_name = ($this -> db_connection) -> quote($this -> get("name"));
            $statistics = array(
                "years_interval"          => $years_interval,
                "outgoing_mails_interval" => array(),
                "incoming_mails_interval" => array()
            );
            foreach ($years_interval as $year) {
                $stmt = "SELECT COUNT(*) FROM \"OutgoingMail\" 
                WHERE CAST(EXTRACT(YEAR FROM transmission_date) AS INTEGER) = $year AND LOWER(transmitter) = LOWER($division_name)";
                array_push($statistics["outgoing_mails_interval"], ($this -> db_connection) -> query($stmt) -> fetchColumn());
            }
    
            foreach ($years_interval as $year) {
                $stmt = "SELECT COUNT(*) FROM \"IncomingMail\" 
                WHERE CAST(EXTRACT(YEAR FROM transmission_date) AS INTEGER) = $year AND LOWER(receiver) = LOWER($division_name)";
                array_push($statistics["incoming_mails_interval"], ($this -> db_connection) -> query($stmt) -> fetchColumn());
            }
            return $statistics;
        }
        # ==========================================            INTERFACES          ================================================== #
        # ============================================================================================================== Employee ==== #
        public function create_employee(
            string   $firstname,  string   $lastname,     string   $birth_date, 
            string   $gender,     string   $photo_name,   string   $email,
            string   $password,   string   $phone_number
        ) : void 
        {   
            try {
                ($this -> db_connection) -> beginTransaction();
                
                $stmt = ($this -> db_connection) -> prepare(
                    "INSERT INTO \"User\" (email, password, phone_number, role) VALUES (?, ?, ?, ?)"
                );
                $stmt -> execute([$email, $password, $phone_number, 'employee']);

                $stmt = ($this->db_connection) -> prepare(
                    "INSERT INTO \"Employee\" (firstname, lastname, birth_date, gender, photo_name, user_id) 
                    VALUES (?, ?, ?, ?, ?, ?)"
                );
                $stmt -> execute(
                    [$firstname, $lastname, $birth_date, $gender, $photo_name, ($this -> db_connection) -> lastInsertId()]
                );
                ($this -> db_connection) -> commit();
            } catch (PDOException $e) {
                ($this -> db_connection) -> rollBack();
                die($e -> getMessage());
            }

        }

        public function get_employee
        (int  $user_id) : Employee {
            return new Employee(user_id: $user_id);
        }

        public function delete_employee
        (int $user_id) : void {
            try {
                ($this -> db_connection) -> beginTransaction();
                $stmt = ($this->db_connection) -> prepare("DELETE FROM \"Employee\" WHERE user_id = ? AND division_id = ?");
                $stmt -> execute([$user_id, $this -> get("id")]);
                $stmt = ($this->db_connection) -> prepare("DELETE FROM \"User\" WHERE id = ?");
                $stmt -> execute([$user_id]);
                ($this -> db_connection) -> commit();
            } catch (PDOException $e) {
                ($this -> db_connection) -> rollBack();
            }
        }
        
        public function update_employee(
            Employee $employee,   string   $firstname,   string   $lastname, 
            string   $birth_date, string   $gender,      string   $photo_name,
            string   $email,      string   $password,    string   $phone_number
        ) : void 
        {
            try {
                ($this -> db_connection) -> beginTransaction();
                $stmt = ($this -> db_connection) -> prepare(
                    "UPDATE \"User\" SET email = ?,  password = ?,  phone_number = ? WHERE id = ?"
                );
                $stmt -> execute([$email, $password, $phone_number, $employee -> get("user_id")]);
                $stmt = ($this -> db_connection) -> prepare(
                    "UPDATE \"Employee\" SET firstname = ?, lastname = ?, birth_date = ?, gender = ?, photo_name = ? 
                    WHERE user_id = ? AND division_id = ?" 
                );
                $stmt -> execute(
                    [$firstname, $lastname, $birth_date, $gender, $photo_name, $employee -> get("user_id"), $this -> get("id")]
                );
                ($this -> db_connection) -> commit();
            } catch (PDOException $e) {
                ($this -> db_connection) -> rollBack();
                die($e -> getMessage());
            }
        }
        
        public function get_employees() : array {
            $stmt = ($this -> db_connection) -> prepare("SELECT * FROM \"Employee\" WHERE division_id = ?");
            $stmt -> execute([$this -> get("id")]);
            $employees = array();
            while ($employee = $stmt -> fetch(PDO::FETCH_OBJ)) {       
                array_push($employees, new Employee(user_id: $employee -> user_id));
            }
            return $employees;
        }
        # ========================================================================================================= Outgoing Mail ==== #
        public function get_outgoing_mails(): array {
            $stmt = ($this -> db_connection) -> prepare("SELECT * FROM \"OutgoingMail\" WHERE transmitter = ?");
            $stmt -> execute([$this -> get("name")]);
            $outgoing_mails = array();
            while ($outgoing_mail = $stmt -> fetch(PDO::FETCH_OBJ)) {       
                array_push($outgoing_mails, new OutgoingMail(outgoing_mail_id: $outgoing_mail -> id));
            }
            return $outgoing_mails;
        }
        # ========================================================================================================= Incoming Mail ==== #
        public function get_incoming_mails(): array {
            $stmt = ($this -> db_connection) -> prepare("SELECT * FROM \"IncomingMail\" WHERE receiver = ?");
            $stmt -> execute([$this -> get("name")]);
            $incoming_mails = array();
            while ($incoming_mail = $stmt -> fetch(PDO::FETCH_OBJ)) {       
                array_push($incoming_mails, new IncomingMail(incoming_mail_id: $incoming_mail -> id));
            }
            return $incoming_mails;
        }
    }
?>