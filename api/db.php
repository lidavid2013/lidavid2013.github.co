<?php

    class sqsModel {

        private $dbconn;

        public function __construct() {
//            $dbURI = 'mysql:host=' .$_ENV['HOST'] . ';port=3306;dbname='.$_ENV['DBASE'];
//            $this->dbconn = new PDO($dbURI, $_ENV['USER'], $_ENV['PASS']);

            $dbURI = 'mysql:host=' . 'localhost'.';port=3306;dbname=' . 'project0315';
            $this->dbconn = new PDO($dbURI, 'root', '');
            $this->dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        function checkLogin($u, $p) {
            // Return uid if user/password tendered are correct otherwise 0
            $sql = "SELECT * FROM customer WHERE Username = :Username";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->bindParam(':Username', $u, PDO::PARAM_STR);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                $retVal = $stmt->fetch(PDO::FETCH_ASSOC);
                if(strlen($retVal['Pass']) > 0) {
                    if($retVal['Pass'] == $p ) { // encrypt & decrypt
                        return Array('Username'=>$retVal['Username'],
                                   'Rol'=>$retVal['Rol'],
                                  'Email'=>$retVal['Email'],
                                   'Phone'=>$retVal['Phone']);
                    } else {
                        return false;
                    }
                } else {
                    return Array('Username'=>$retVal['Username']);
                }
            } else {
                return false;
            }
        }
        function userExists($u) {
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



        function registerUser($Username, $Rol, $Pass,$Email, $Phone) {
            // Retister user into system, assume validation has happened.
            // return UID created or false if fail
//            $sql = "UPDATE customer SET Username = :Username, Pass = :Pass, Email = :Email, Phone = :Phone=1 WHERE CustomerID = :CustomerID";

//            $lastCustID = $this->dbconn->lastInsertID();

//            $sql = "INSERT INTO customer(CustomerID,Username,Pass,Email,Phone)  VALUES (:CustomerID,:Username,:Pass,:Email, :Phone)";
            $sql = "INSERT INTO customer(Username,Rol,Pass,Email,Phone)  VALUES (:Username,:Rol,:Pass,:Email, :Phone);";
            $stmt = $this->dbconn->prepare($sql);
//            $stmt->bindParam(':CustomerID', $lastCustID, PDO::PARAM_INT);
            $stmt->bindParam(':Username', $Username, PDO::PARAM_STR);
            $stmt->bindParam(':Rol', $Rol, PDO::PARAM_STR);
            $stmt->bindParam(':Pass', $Pass, PDO::PARAM_STR);
            $stmt->bindParam(':Email', $Email, PDO::PARAM_STR);
            $stmt->bindParam(':Phone', $Phone, PDO::PARAM_STR);            

            $result = $stmt->execute();
            if($result === true) {
                return true;
            } else {
                return false;
            }
        }
        function logEvent($CustomerID, $url, $resp_code, $source_ip) {
            $sql = "INSERT INTO logtable (url, CustomerID, response_code, ip_addr) 
                VALUES (:url, :CustomerID, :resp_code, :ip);";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->bindParam(':CustomerID', $CustomerID, PDO::PARAM_INT);
            $stmt->bindParam(':url', $url, PDO::PARAM_STR);
            $stmt->bindParam(':resp_code', $resp_code, PDO::PARAM_INT);
            $stmt->bindParam(':ip', $source_ip, PDO::PARAM_STR);
            $result = $stmt->execute();
            if($result === true) {
                return true;
            } else {
                return false;
            }
        }


        function roomadd($RoomPic, $RoomDes, $RoomPrice, $RoomNumber) {

            $sql = "INSERT INTO room(RoomPic, RoomDes, RoomPrice, RoomNumber)  VALUES (:RoomPic, :RoomDes, :RoomPrice, :RoomNumber);";
            $stmt = $this->dbconn->prepare($sql);

            $stmt->bindParam(':RoomPic', $RoomPic, PDO::PARAM_STR);
            $stmt->bindParam(':RoomDes', $RoomDes, PDO::PARAM_STR);
            $stmt->bindParam(':RoomPrice', $RoomPrice, PDO::PARAM_STR);            
            $stmt->bindParam(':RoomNumber', $RoomNumber, PDO::PARAM_INT);  
            $result = $stmt->execute();
            if($result === true) {
                return true;
            } else {
                return false;
            }
        }


        function editroom($RoomPic, $RoomDes, $RoomPrice, $RoomNumber) {

            $sql = "UPDATE room SET RoomPic=:RoomPic, RoomDes=:RoomDes, RoomPrice=:RoomPrice, RoomNumber=:RoomNumber;";
            $stmt = $this->dbconn->prepare($sql);

            $stmt->bindParam(':RoomPic', $RoomPic, PDO::PARAM_STR);
            $stmt->bindParam(':RoomDes', $RoomDes, PDO::PARAM_STR);
            $stmt->bindParam(':RoomPrice', $RoomPrice, PDO::PARAM_STR);            
            $stmt->bindParam(':RoomNumber', $RoomNumber, PDO::PARAM_INT);  
            $result = $stmt->execute();
            if($result === true) {
                return true;
            } else {
                return false;
            }
        }



        function orderadd($CustomerID, $id, $DateStart, $DateFinish, $OrderStatus, $TotalAmount) {

            $sql = "INSERT INTO booking(CustomerID, id, DateStart, DateFinish, OrderStatus, TotalAmount)  VALUES (:CustomerID, :id, :DateStart, :DateFinish, :OrderStatus, :TotalAmount);";
            $stmt = $this->dbconn->prepare($sql);

            $stmt->bindParam(':CustomerID', $CustomerID, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->bindParam(':DateStart', $DateStart, PDO::PARAM_STR);            
            $stmt->bindParam(':DateFinish', $DateFinish, PDO::PARAM_STR);  
            $stmt->bindParam(':OrderStatus', $OrderStatus, PDO::PARAM_STR);            
            $stmt->bindParam(':TotalAmount', $TotalAmount, PDO::PARAM_INT);  
            $result = $stmt->execute();
            if($result === true) {
                return true;
            } else {
                return false;
            }
        }


        function orderedit($CustomerID, $id, $DateStart, $DateFinish, $OrderStatus, $TotalAmount) {

            $sql = "UPDATE booking SET CustomerID=:CustomerID, id=:id, DateStart=:DateStart, DateFinish=:DateFinish, OrderStatus=:OrderStatus, TotalAmount=:TotalAmount;";
            $stmt = $this->dbconn->prepare($sql);

            $stmt->bindParam(':CustomerID', $CustomerID, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->bindParam(':DateStart', $DateStart, PDO::PARAM_STR);            
            $stmt->bindParam(':DateFinish', $DateFinish, PDO::PARAM_STR);  
            $stmt->bindParam(':OrderStatus', $OrderStatus, PDO::PARAM_STR);            
            $stmt->bindParam(':TotalAmount', $TotalAmount, PDO::PARAM_INT);  
            $result = $stmt->execute();
            if($result === true) {
                return true;
            } else {
                return false;
            }
        }



        function orderDelete($id) {
            $sql = "DELETE FROM booking WHERE BookingID = :BookingID";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->bindParam(':BookingID', $id, PDO::PARAM_INT);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        }


        function roomshow() {
            $sql = "SELECT * FROM room";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            exit(json_encode($result));
        }


//        function roomfetchall()
        function fetch_all()
        {
            $sql = "SELECT * FROM room ORDER BY id";
            $stmt = $this->dbconn->prepare($sql);
            if($stmt->execute())
            {
                while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                {
                    $data[] = $row;
                }
                return $data;
            }
        }
    
        function insert()
        {
            if(isset($_POST["RoomPic"]))
            {
                $form_data = array(
                    ':RoomPic'		=>	$_POST["RoomPic"],
                    ':RoomDes'		=>	$_POST["RoomDes"],
                    ':RoomPrice'		=>	$_POST["RoomPrice"],
                    ':RoomNumber'		=>	$_POST["RoomNumber"]
                );
                $sql = "
                INSERT INTO room 
                (RoomPic, RoomDes, RoomPrice, RoomNumber) VALUES 
                (:RoomPic, :RoomDes, :RoomPrice, :RoomNumber)
                ";
                $stmt = $this->dbconn->prepare($sql);
                if($stmt->execute($form_data))
                {
                    $data[] = array(
                        'success'	=>	'1'
                    );
                }
                else
                {
                    $data[] = array(
                        'success'	=>	'0'
                    );
                }
            }
            else
            {
                $data[] = array(
                    'success'	=>	'0'
                );
            }
            return $data;
        }
    
        function fetch_single($id)
        {
            $sql = "SELECT * FROM room WHERE id='".$id."'";
            $stmt = $this->dbconn->prepare($sql);
            if($stmt->execute())
            {
                foreach($stmt->fetchAll() as $row)
                {
                    $data['RoomPic'] = $row['RoomPic'];
                    $data['RoomDes'] = $row['RoomDes'];
                    $data['RoomPrice'] = $row['RoomPrice'];
                    $data['RoomNumber'] = $row['RoomNumber'];
                }
                return $data;
            }
        }
    
        function update()
        {
            if(isset($_POST["RoomPic"]))
            {
                $form_data = array(
                    ':RoomPic'	=>	$_POST['RoomPic'],
                    ':RoomDes'	=>	$_POST['RoomDes'],
                    ':RoomPrice'	=>	$_POST['RoomPrice'],
                    ':RoomNumber'	=>	$_POST['RoomNumber'],
                    ':id'			=>	$_POST['id']
                );
                $sql = "
                UPDATE room 
                SET RoomPic = :RoomPic, RoomDes = :RoomDes,  RoomPrice = :RoomPrice, RoomNumber = :RoomNumber
                WHERE id = :id
                ";
                $stmt = $this->dbconn->prepare($sql);
                if($stmt->execute($form_data))
                {
                    $data[] = array(
                        'success'	=>	'1'
                    );
                }
                else
                {
                    $data[] = array(
                        'success'	=>	'0'
                    );
                }
            }
            else
            {
                $data[] = array(
                    'success'	=>	'0'
                );
            }
            return $data;
        }
        function delete($id)
        {
            $sql = "DELETE FROM room WHERE id = '".$id."'";
            $stmt = $this->dbconn->prepare($sql);
            if($stmt->execute())
            {
                $data[] = array(
                    'success'	=>	'1'
                );
            }
            else
            {
                $data[] = array(
                    'success'	=>	'0'
                );
            }
            return $data;
        }

    }
?>