<?php
    class Autoloader {
        private string $root_app = __DIR__;
        private string $for;

        private array  $user_classes_name = array(
            "administrator" => [
                'database/Database.php',
                'user/administrator/interface/CRUDDivision.php'    ,
                'user/administrator/interface/CRUDService.php'     ,
                'user/administrator/interface/CRUDIncomingMail.php',
                'user/administrator/interface/CRUDEmployee.php'    ,
                'user/administrator/interface/CRUDOutgoingMail.php',
                'Model.php', 'user/User.php'                       , 
                'user/administrator/Administrator.php'             ,
                'user/division/interface/ROutgoingMail.php'        ,
                'user/division/interface/RIncomingMail.php'        ,
                'user/division/interface/REmployee.php'            ,
                'user/division/Division.php'                       ,
                'user/administrator/handle/OutgoingMail.php'       ,
                'user/administrator/handle/Employee.php'           ,
                'interactive/IncomingMail.php'                     ,
                'interactive/Service.php'                          ,
            ],
            'division'      => [
                'database/Database.php',
                'user/division/interface/ROutgoingMail.php',
                'user/division/interface/RIncomingMail.php',
                'user/division/interface/REmployee.php'    ,
                'Model.php', 'user/User.php'               , 
                'user/division/Division.php'               ,
                'user/division/handle/OutgoingMail.php'    ,    
                'user/division/handle/Employee.php'        ,    
                'interactive/IncomingMail.php'             ,    
            ],
            'authentification' => [
                'database/Database.php',
                'Model.php', 'user/UserAuth.php',
                'user/User.php'
            ]
        );

        public function __construct(string $for) {
            $this -> for = $for;
        }

        public function run() {
            foreach ($this -> user_classes_name[$this -> for] as $class_name) {
                require_once "{$this -> root_app}/$class_name";
            }
        }

    }
?>