<?php

    class sqsSession {
        // attributes will be stored in session, but always test incognito
        private $last_visit = 0;
        private $last_visits = Array();

//        private $user_id = 0;
//        private $user_Username;
//        private $user_Email;
//        private $user_Phone;
//        private $user_token;


        private $CustomerID=0;
        private $Username;
        private $Rol;
        private $Email;
        private $Phone;
        private $user_token;

//        private $origin;

        public function __construct() {
            $this->origin = 'http://localhost/';
        }
        public function is_rate_limited() {
            if($this->last_visit == 0) {
                $this->last_visit = time();
                return false;
            }
            if($this->last_visit == time()) {
                return true;
            }
            return false;
        }
        public function login($Username, $Pass) {
            global $sqsdb;

            $res = $sqsdb->checkLogin($Username, $Pass);
            if($res === false) {
                return false;
            } elseif(count($res) > 1) {
                $this->CustomerID = $res['CustomerID'];

                $this->user_token = md5(json_encode($res));
                return Array('Username'=>$res['Username'],
                'Rol'=>$res['Rol'],
                'Email'=>$res['Email'],
                'Phone'=>$res['Phone'],
                'Hash'=>$this->user_token);
            } elseif(count($res) == 1) {
                $this->CustomerID = $res['CustomerID'];
                $this->user_token = md5(json_encode($res));
                return Array('Hash'=>$this->user_token);
            }
        }
        public function register($Username, $Rol, $Email, $Phone, $Pass) {
            global $sqsdb;
//            if($csrf == $this->user_token) {
                if($sqsdb->registerUser($Username, $Rol, $Pass, $Email, $Phone)) {
                    return true;
                } else {
                    return false;
                }
//            } else {
//                return false;
//            }
            // call the dbobject for SQL
        }
        public function isLoggedIn() {
            echo $this->CustomerID; die();
            if($this->CustomerID === 0) {
                return false;
            } else {
    //            return true;
                return Array('Hash'=>$this->user_token);
            }
        }

//        public function roomdisplay($RoomPic, $RoomDes, $RoomPrice, $RoomNumber) {
        public function roomdisplay() {
            global $sqsdb;
            $sqsdb->roomshow();
            return $sqsdb;
        }


        public function logout() {
            $this->CustomerID = 0;
//            $this->user_privilege = 0;
        }
        public function validate($type, $dirty_string) {
        }
        public function logEvent() {
        }


        public function roomregister($RoomPic, $RoomDes, $RoomPrice, $RoomNumber) {
            global $sqsdb;

                if($sqsdb->roomadd($RoomPic, $RoomDes, $RoomPrice, $RoomNumber)) {
                    return true;
                } else {
                    return 0;
                }
        }

        public function roomedit($RoomPic, $RoomDes, $RoomPrice, $RoomNumber) {
            global $sqsdb;

                if($sqsdb->editroom($RoomPic, $RoomDes, $RoomPrice, $RoomNumber)) {
                    return true;
                } else {
                    return 0;
                }
        }



        public function orderregister($CustomerID, $RoomID, $DateStart, $DateFinish, $OrderStatus, $TotalAmount) {
            global $sqsdb;

                if($sqsdb->orderadd($CustomerID, $RoomID, $DateStart, $DateFinish, $OrderStatus, $TotalAmount)) {
                    return true;
                } else {
                    return 0;
                }
        }



        public function orderedit($CustomerID, $RoomID, $DateStart, $DateFinish, $OrderStatus, $TotalAmount) {
            global $sqsdb;

                if($sqsdb->orderedit($CustomerID, $RoomID, $DateStart, $DateFinish, $OrderStatus, $TotalAmount)) {
                    return true;
                } else {
                    return 0;
                }
        }

        public function userExists($u) {
            $sql = "SELECT * FROM customer WHERE Username = :Username";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->bindParam(':Username', $u, PDO::PARAM_STR);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        }

    }
?>
