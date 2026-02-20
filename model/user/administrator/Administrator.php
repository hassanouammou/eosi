<?php 
    class Administrator extends User implements CRUDDivision, CRUDService, CRUDOutgoingMail, CRUDIncomingMail, CRUDEmployee {
        # ========================================================================================================= Administrator ==== #
        public DateTime $datetime;

        public function __construct(int $user_id) {
            parent::__construct($user_id);
            $this -> datetime = new DateTime("Africa/Casablanca");
        }

        public function get(string $field) : string {
            try {
                if (in_array($field, $this -> user_columns_name)) {
                    return parent::get($field);
                } else {
                    $stmt = ($this -> db_connection) -> prepare("SELECT $field FROM \"Administrator\" WHERE user_id = ?"); 
                    $stmt -> execute([parent::get("id")]);
                    return $stmt -> fetchColumn();
                }
            } catch (PDOException $e) {
                die($e -> getMessage());
            }
        }
        
        public function set(string $field, string $value) : void {
            try {
                if (in_array($field, $this -> user_columns_name)) {
                    parent::set($field, $value);
                } else {
                    $stmt = ($this -> db_connection) -> prepare("UPDATE \"Administrator\" SET $field = ? WHERE user_id = ?");
                    $stmt -> execute([$value, parent::get("id")]);
                }
            } catch (PDOException $e) {
                die($e -> getMessage());
            }
        } 
        # ========================================================================================================= Notifications ==== #
        public function get_notifications() : array {
            $notifications = array();
            $stmt = ($this -> db_connection) -> query(
                "SELECT * FROM \"Notification\" where receiver = 'administrator'"); 
            while ($notification = $stmt -> fetch(PDO::FETCH_OBJ)) {
                array_push($notifications, $notification);
            }
            return $notifications;
        }

        public function clear_notifications() : void {
            $stmt = ($this -> db_connection) -> query("DELETE FROM \"Notification\" where receiver = 'administrator'"); 
        }
        # ================================================================================================== Dashboard Statistics ==== #
        function get_mails_statistics_between(string $start_year, string $end_year) : array {
            $years_interval = range($start_year, $end_year);
            $statistics = array(
                "years_interval"          => $years_interval,
                "outgoing_mails_interval" => array(),
                "incoming_mails_interval" => array()
            );
            foreach ($years_interval as $year) {
                $stmt = "SELECT COUNT(*) FROM \"OutgoingMail\" 
                WHERE CAST(EXTRACT(YEAR FROM transmission_date) AS INTEGER) = $year";
                array_push($statistics["outgoing_mails_interval"], ($this -> db_connection) -> query($stmt) -> fetchColumn());
            }
    
            foreach ($years_interval as $year) {
                $stmt = "SELECT COUNT(*) FROM \"IncomingMail\" 
                WHERE CAST(EXTRACT(YEAR FROM transmission_date) AS INTEGER) = $year";
                array_push($statistics["incoming_mails_interval"], ($this -> db_connection) -> query($stmt) -> fetchColumn());
            }
            return $statistics;
        }

        # ==========================================            INTERFACES          ================================================== #
        # ============================================================================================================== Division ==== #
        public function create_division 
        (string $name, string $email, string $password, string $phone_number) : void {
            try {
                ($this -> db_connection) -> beginTransaction();
                $stmt = ($this -> db_connection) -> prepare(
                    "INSERT INTO \"User\" (email, password, phone_number, role) VALUES (?, ?, ?, ?)"
                );
                $stmt -> execute([$email, $password, $phone_number, 'division']); 
                $stmt = ($this -> db_connection) -> prepare("INSERT INTO \"Division\" (name, user_id) VALUES (?, ?)");
                $stmt -> execute([$name, ($this -> db_connection) -> lastInsertId()]);
                ($this -> db_connection) -> commit();
            } catch (PDOException $e) {
                ($this -> db_connection) -> rollBack();
                die($e -> getMessage());
            }
        }

        public function get_division
        (int  $user_id) : Division {
            return new Division(user_id: $user_id);
        }
        
        public function update_division 
        (Division $division, string $name, string $email, string $password, string $phone_number) : void {
            try {
                ($this -> db_connection) -> beginTransaction();
                $stmt = ($this -> db_connection) -> prepare(
                    "UPDATE \"User\" SET email = ?,  password = ?,  phone_number = ? WHERE id = ?"
                );
                $stmt -> execute([$email, $password, $phone_number, $division -> get("user_id")]);
                $stmt = ($this -> db_connection) -> prepare("UPDATE \"Division\" SET name = ? WHERE user_id = ?");
                $stmt -> execute([$name, $division -> get("user_id")]);
                ($this -> db_connection) -> commit();
            } catch (PDOException $e) {
                ($this -> db_connection) -> rollBack();
                die($e -> getMessage());
            }
        }

        public function delete_division 
        (int $user_id) : void {
            try {
                ($this -> db_connection) -> beginTransaction();
                $stmt = ($this -> db_connection) -> prepare("DELETE FROM \"Division\" WHERE user_id = ?");
                $stmt -> execute([$user_id]);
                $stmt = ($this -> db_connection) -> prepare("DELETE FROM \"UserAuth\" WHERE user_id = ?");
                $stmt -> execute([$user_id]);
                $stmt = ($this -> db_connection) -> prepare("DELETE FROM \"User\" WHERE id = ?");
                $stmt -> execute([$user_id]);
                ($this -> db_connection) -> commit();
            } catch (PDOException $e) {
                ($this -> db_connection) -> rollBack();
            }
        }

        public function get_divisions() : array {
            $stmt = ($this -> db_connection) -> query("SELECT * FROM \"Division\" ORDER BY name");
            $divisions = array();
            while ($division = $stmt -> fetch(PDO::FETCH_OBJ)) {
                array_push($divisions, new Division(user_id: $division -> user_id));
            }
            return $divisions;
        } 
        # =============================================================================================================== Service ==== #
        public function get_service
        (int  $service_id) : Service {
            return new Service(service_id: $service_id);
        }

        public function get_services() : array {
            $stmt = ($this -> db_connection) -> query("SELECT * FROM \"Service\"");
            $services = array();
            while ($service = $stmt -> fetch(PDO::FETCH_OBJ)) {
                array_push($services, new Service(service_id: $service -> id));
            }
            return $services;
        }
        # ============================================================================================================== Employee ==== #
        public function create_employee
        (
            string  $firstname,  string  $lastname  , string  $birth_date , 
            string  $gender   ,  string  $photo_name, string  $division_id,
            string  $email    ,  string  $password  , string  $phone_number
        ) : void 
        {   
            try {
                ($this -> db_connection) -> beginTransaction();
                $stmt = ($this -> db_connection) -> prepare(
                    "INSERT INTO \"User\" (email, password, phone_number, role) VALUES (?, ?, ?, ?)"
                );
                $stmt -> execute([$email, $password, $phone_number, 'employee']);
                $user_id = ($this -> db_connection) -> lastInsertId();
                $stmt = ($this->db_connection) -> prepare(
                    "INSERT INTO \"Employee\" (firstname, lastname, birth_date, gender, photo_name, division_id, user_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)"
                );
                $stmt -> execute(
                    [$firstname, $lastname, $birth_date, $gender, $photo_name, $division_id, $user_id]
                );
                $stmt = ($this -> db_connection) -> prepare(
                    "INSERT INTO \"Notification\" (transmitter, receiver, topic, subject, topic_id, created_at) 
                    VALUES(?, LOWER(?), ?, ?, ?, ?)"
                );
                $division = Division::get_with_self_id(division_id: $division_id);
                $stmt -> execute(
                    [
                        'administrator', $division -> get("name"), 'employee', 'create', 
                        $user_id, ($this -> datetime) -> format("Y-m-d H:i:s")
                    ]
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
                $stmt = ($this->db_connection) -> prepare("DELETE FROM \"Employee\" WHERE user_id = ?");
                $stmt -> execute([$user_id]);
                $stmt = ($this->db_connection) -> prepare("DELETE FROM \"UserAuth\" WHERE user_id = ?");
                $stmt -> execute([$user_id]);
                $stmt = ($this->db_connection) -> prepare("DELETE FROM \"User\" WHERE id = ?");
                $stmt -> execute([$user_id]);
                $stmt = ($this -> db_connection) -> prepare("DELETE FROM \"Notification\" WHERE topic = ? AND topic_id = ?");
                $stmt -> execute(["employee", $user_id]);
                ($this -> db_connection) -> commit();
            } catch (PDOException $e) {
                ($this -> db_connection) -> rollBack();
            }
        }
        
        public function update_employee
        (
            Employee $employee   , string   $firstname, string   $lastname  , 
            string   $birth_date , string   $gender   , string   $photo_name, 
            string   $division_id, string   $email    , string   $password  ,   
            string   $phone_number
        ) : void 
        {
            try {
                ($this -> db_connection) -> beginTransaction();
                $stmt = ($this -> db_connection) -> prepare(
                    "UPDATE \"User\" SET email = ?,  password = ?,  phone_number = ? WHERE id = ?"
                );
                $stmt -> execute([$email, $password, $phone_number, $employee -> get("user_id")]);
                $stmt = ($this -> db_connection) -> prepare(
                    "UPDATE \"Employee\" SET firstname = ?, lastname = ?, birth_date = ?, gender = ?, photo_name = ?, division_id = ? 
                    WHERE user_id = ?"
                );
                $stmt -> execute([$firstname, $lastname, $birth_date, $gender, $photo_name, $division_id, $employee -> get("user_id")]);
                ($this -> db_connection) -> commit();
            } catch (PDOException $e) {
                ($this -> db_connection) -> rollBack();
                die($e -> getMessage());
            }
        }
        
        public function get_employees() : array {
            $stmt = ($this -> db_connection) -> query("SELECT * FROM \"Employee\"");
            $employees = array();
            while ($employee = $stmt -> fetch(PDO::FETCH_OBJ)) {       
                array_push($employees, new Employee(user_id: $employee -> user_id));
            }
            return $employees;
        }
        # ========================================================================================================= Outgoing Mail ==== #
        public function create_outgoing_mail
        (
            string  $transmitter, string  $receiver         , string  $number, 
            string  $subject    , string  $transmission_date, string  $electronic_mail_name
        ) : void 
        {
            try {
                ($this -> db_connection) -> beginTransaction();
                $stmt = ($this -> db_connection) -> prepare(
                    "INSERT INTO \"OutgoingMail\" (transmitter, receiver, number, subject, transmission_date, electronic_mail_name) 
                    VALUES (?, ?, ?, ?, ?, ?)"
                );
                $stmt -> execute([$transmitter, $receiver, $number, $subject, $transmission_date, $electronic_mail_name]);
                
                $stmt = ($this -> db_connection) -> prepare(
                    "INSERT INTO \"Notification\" (transmitter, receiver, topic, subject, topic_id, created_at) 
                    VALUES(?, ?, ?, ?, ?, ?)"
                );
                $stmt -> execute(
                    [
                        'administrator', 'administrator', 'outgoing mail', 
                        'create', ($this -> db_connection) -> lastInsertId(), 
                        ($this -> datetime) -> format("Y-m-d H:i:s")
                    ]
                );
                ($this -> db_connection) -> commit();
            } catch (PDOException $e) {
                ($this -> db_connection) -> rollBack();
                die($e -> getMessage());
            }
        }

        public function get_outgoing_mail
        (int $outgoing_mail_id) : OutgoingMail {
            return new OutgoingMail(outgoing_mail_id: $outgoing_mail_id);
        }

        public function delete_outgoing_mail
        (int $outgoing_mail_id) : void {
            $outgoing_mail = new OutgoingMail(outgoing_mail_id: $outgoing_mail_id);       
            unlink("C:/XAMPP/htdocs/eosi/upload/electronic-mail/outgoing-mail/{$outgoing_mail->get('electronic_mail_name')}");
            $stmt = ($this -> db_connection) -> prepare("DELETE FROM \"OutgoingMail\" WHERE id = ?");
            $stmt -> execute([$outgoing_mail_id]);
            $stmt = ($this -> db_connection) -> prepare("DELETE FROM \"Notification\" WHERE topic = ? AND topic_id = ?");
            $stmt -> execute(["outgoing mail", $outgoing_mail_id]);
        }

        public function update_outgoing_mail
        (
            OutgoingMail $outgoing_mail, string  $transmitter, string  $receiver         , 
            string       $number       , string  $subject    , string  $transmission_date, 
            string       $electronic_mail_name
        ) :  void {
            $stmt = ($this -> db_connection) -> prepare("
                UPDATE \"OutgoingMail\" SET transmitter = ?, receiver = ?, number = ?, subject = ?, transmission_date = ?, electronic_mail_name = ? 
                WHERE id = ?
            ");
            $stmt -> execute(
                [$transmitter, $receiver, $number, $subject, $transmission_date, $electronic_mail_name, $outgoing_mail -> get("id")]
            );
        }

        public function get_outgoing_mails() : array {
            $stmt = ($this -> db_connection) -> query(
                "SELECT * FROM \"OutgoingMail\" ORDER BY transmission_date DESC"
            );
            $outgoing_mails = array();
            while ($outgoing_mail = $stmt -> fetch(PDO::FETCH_OBJ)) {
                array_push($outgoing_mails, new OutgoingMail(outgoing_mail_id: $outgoing_mail -> id));
            }
            return $outgoing_mails;
        }
        # ========================================================================================================= Incoming Mail ==== #
        public function create_incoming_mail
        (
            string  $transmitter, string  $receiver         , string  $number, 
            string  $subject    , string  $transmission_date, string  $electronic_mail_name
        ) : void {
            try {
                ($this -> db_connection) -> beginTransaction();
                $stmt = ($this -> db_connection) -> prepare(
                    "INSERT INTO \"IncomingMail\" (transmitter, receiver, number, subject, transmission_date, electronic_mail_name) 
                    VALUES (?, ?, ?, ?, ?, ?)"
                );
                $stmt -> execute([$transmitter, $receiver, $number, $subject, $transmission_date, $electronic_mail_name]);
                $stmt = ($this -> db_connection) -> prepare(
                    "INSERT INTO \"Notification\" (transmitter, receiver, topic, subject, topic_id, created_at) 
                    VALUES(?, LOWER(?), ?, ?, ?, ?)"
                );
                $stmt -> execute(
                    [
                        "administrator", $receiver, "incoming mail", "create", 
                        ($this->db_connection) -> lastInsertId(), ($this -> datetime) -> format("Y-m-d H:i:s")
                    ]
                );
                ($this -> db_connection) -> commit();
            } catch (PDOException $e) {
                ($this -> db_connection) -> rollBack();
                die($e -> getMessage());
            }
        }

        public function get_incoming_mail
        (int $incoming_mail_id) : IncomingMail {
            return new IncomingMail(incoming_mail_id: $incoming_mail_id);
        }

        public function delete_incoming_mail
        (int $incoming_mail_id) : void {
            $incoming_mail = new IncomingMail(incoming_mail_id: $incoming_mail_id);
            unlink("C:/XAMPP/htdocs/eosi/upload/electronic-mail/incoming-mail/{$incoming_mail->get('electronic_mail_name')}");
            $stmt = ($this -> db_connection) -> prepare("DELETE FROM \"IncomingMail\" WHERE id = ?");
            $stmt -> execute([$incoming_mail_id]);
            $stmt = ($this -> db_connection) -> prepare("DELETE FROM \"Notification\" WHERE topic = ? AND topic_id = ?");
            $stmt -> execute(["incoming mail", $outgoing_mail_id]);
        }

        public function update_incoming_mail
        (   
            IncomingMail $incoming_mail, string  $transmitter, string  $receiver, 
            string       $number       , string  $subject    , string  $transmission_date, 
            string       $electronic_mail_name
        ) :  void {
            $stmt = ($this -> db_connection) -> prepare("
                UPDATE \"IncomingMail\" SET transmitter = ?, receiver = ?, number = ?, subject = ?, transmission_date = ?, electronic_mail_name = ? 
                WHERE id = ?
            ");
            $stmt -> execute(
                [$transmitter, $receiver, $number, $subject, $transmission_date, $electronic_mail_name, $incoming_mail -> get("id")]
            );
        }

        public function get_incoming_mails() : array {
            $stmt = ($this -> db_connection) -> query("SELECT * FROM \"IncomingMail\" ORDER BY transmission_date DESC");
            $incoming_mails = array();
            while ($incoming_mail = $stmt -> fetch(PDO::FETCH_OBJ)) {
                array_push($incoming_mails, new IncomingMail(incoming_mail_id: $incoming_mail -> id));
            }
            return $incoming_mails;
        }
    }
?>