<?php

require_once('./vendor/autoload.php');
require_once('./db.php');
require_once('./se.php');

$sqsdb = new sqsModel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\RedirectResponse;

$request = Request::createFromGlobals();
$response = new Response();
$session = new Session(new NativeSessionStorage(), new AttributeBag());

$response->headers->set('Content-Type', 'application/json');
$response->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
$response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
$response->headers->set('Access-Control-Allow-Origin', 'http://localhost/');
$response->headers->set('Access-Control-Allow-Credentials', 'true');

$session->start();

if(!$session->has('sessionObj')) {
    $session->set('sessionObj', new sqsSession);
}

if(empty($request->query->all())) {
    $response->setStatusCode(400);
} elseif($request->cookies->has('PHPSESSID')) {
    if($session->get('sessionObj')->is_rate_limited()) {
        $response->setStatusCode(429);
    }
    if($request->getMethod() == 'POST') {                 ////// Post methods
        if($request->query->get('action') == 'register') { // 1: registration for customers or admins
            if($request->request->has('Username') and
                $request->request->has('Rol') and
                $request->request->has('Email') and
                $request->request->has('Phone') and
                $request->request->has('Pass') ) {
                $res = $session->get('sessionObj')->register(
                    $request->request->get('Username'),
                    $request->request->get('Rol'),                    
                    $request->request->get('Email'),
                    $request->request->get('Phone'),
                    $request->request->get('Pass')    
//                    $request->request->get('csrf')
                );
                if($res === true) {
                    $response->setStatusCode(201);
                } elseif($res === false) {
                    $response->setStatusCode(403);
                } elseif($res === 0) {
                    $response->setStatusCode(500);
                }
            }   
            else {
                $response->setStatusCode(400);
            }
        } 
        
        
        
        elseif($request->query->get('action') == 'roomregister') {  //2: registration for rooms
            if(
                $request->request->has('RoomPic') and
                $request->request->has('RoomDes') and
                $request->request->has('RoomPrice') and                
                $request->request->has('RoomNumber') ) {
                $res = $session->get('sessionObj')->roomregister(
                    $request->request->get('RoomPic'),
                    $request->request->get('RoomDes'),
                    $request->request->get('RoomPrice'),    
                    $request->request->get('RoomNumber')
                );
                if($res === true) {
                    $response->setStatusCode(201);
                } elseif($res === false) {
                    $response->setStatusCode(403);
                } elseif($res === 0) {
                    $response->setStatusCode(500);
                }
            }   
            else {
                $response->setStatusCode(400);
            }
        }        
        
        
        

        elseif($request->query->get('action') == 'orderregister') { //3: registration for orders
            if(
                $request->request->has('CustomerID') and
                $request->request->has('id') and
                $request->request->has('DateStart') and                
                $request->request->has('DateFinish') and
                $request->request->has('OrderStatus') and
                $request->request->has('TotalAmount')                 
                ) {
                $res = $session->get('sessionObj')->orderregister(
                    $request->request->get('CustomerID'),
                    $request->request->get('id'),
                    $request->request->get('DateStart'),    
                    $request->request->get('DateFinish'),
                    $request->request->get('OrderStatus'),
                    $request->request->get('TotalAmount')                    
                );
                if($res === true) {
                    $response->setStatusCode(201);
                } elseif($res === false) {
                    $response->setStatusCode(403);
                } elseif($res === 0) {
                    $response->setStatusCode(500);
                }
            }   
            else {
                $response->setStatusCode(400);
            }
        }                



        elseif($request->query->get('action') == 'orderedit') {   //4: Edit for orders
            if(
                $request->request->has('CustomerID') and
                $request->request->has('id') and
                $request->request->has('DateStart') and                
                $request->request->has('DateFinish') and
                $request->request->has('OrderStatus') and
                $request->request->has('TotalAmount')                 
                ) {
                $res = $session->get('sessionObj')->orderedit(
                    $request->request->get('CustomerID'),
                    $request->request->get('id'),
                    $request->request->get('DateStart'),    
                    $request->request->get('DateFinish'),
                    $request->request->get('OrderStatus'),
                    $request->request->get('TotalAmount')                    
                );
                if($res === true) {
                    $response->setStatusCode(201);
                } elseif($res === false) {
                    $response->setStatusCode(403);
                } elseif($res === 0) {
                    $response->setStatusCode(500);
                }
            }   
            else {
                $response->setStatusCode(400);
            }
        } 
        
        

        elseif($request->query->get('action') == 'roomedit') {  //2: edit for rooms
            if(
                $request->request->has('RoomPic') and
                $request->request->has('RoomDes') and
                $request->request->has('RoomPrice') and                
                $request->request->has('RoomNumber') ) {
                $res = $session->get('sessionObj')->roomedit(
                    $request->request->get('RoomPic'),
                    $request->request->get('RoomDes'),
                    $request->request->get('RoomPrice'),    
                    $request->request->get('RoomNumber')
                );
                if($res === true) {
                    $response->setStatusCode(201);
                } elseif($res === false) {
                    $response->setStatusCode(403);
                } elseif($res === 0) {
                    $response->setStatusCode(500);
                }
            }   
            else {
                $response->setStatusCode(400);
            }
        }      

        
        
        
        elseif($request->query->getAlpha('action') == 'login') {    //5: Login for customers or admins
            if($request->request->has('Username') and $request->request->has('Pass'))
             {
                $res = $session->get('sessionObj')->login($request->request->get('Username'),
                    $request->request->get('Pass'));
                if ($res === false) {
                    $response->setContent(json_encode($request->request));                     
                    $response->setStatusCode(401);
                } elseif(count($res) == 1) {
                    $response->setStatusCode(203);
                    $response->setContent(json_encode($res));
                } elseif(count($res) > 1) {
                    $response->setStatusCode(200);
                    $response->setContent(json_encode($res));
                }
            } else {
                $response->setContent(json_encode($request));                
                $response->setStatusCode(404);
            }
        } 
        elseif($request->query->getAlpha('action') == 'roomdisplay') {    // 4: To display all rooms
            $res = $session->get('sessionObj')->roomdisplay();
            return $res;
            $response->setStatusCode(400);
        }
        
    }







    elseif($request->getMethod() == 'GET') {              // GET methods
        if($request->query->getAlpha('action') == 'accountexists') {    // 1: To check if account exists
            if($request->query->has('Username')) {
                $res = $sqsdb->userExists($request->query->get('Username'));
                if($res) {
                    $response->setStatusCode(400);
                } else {
                    $response->setStatusCode(204);
                }
            }
        } elseif($request->query->getAlpha('action') == 'isloggedin') {   // 2: To check if custmers or admins login in
            $res = $session->get('sessionObj')->isLoggedIn();
            if($res == false) {
                $response->setStatusCode(403);
            } elseif(count($res) == 1) {
                $response->setStatusCode(200);
                $response->setContent(json_encode($res));
            }
        } elseif($request->query->getAlpha('action') == 'logout') {   // 3: To log out for customers or admins
            $session->get('sessionObj')->logout();
            $response->setStatusCode(200);
        } 




//        elseif($request->query->getAlpha('action') == 'roomdisplay') {    // 4: To display all rooms
//            $res = $session->get('sessionObj')->roomdisplay();
//            return $res;
//            if($res == true) {
//                $response->setStatusCode(200);
//                $response->setContent(json_encode($res));
//            } else {
//                $response->setStatusCode(403);             
//            }
//      
//        } 
        
        elseif($request->query->getAlpha('action') == 'fetch_all') {    // 4: To display all rooms
            $res = $session->get('sessionObj')->roomdisplay();  	//$data = $sqsdb->fetch_all();
            return $res;
            if($res == true) {
                $response->setStatusCode(200);
                $response->setContent(json_encode($res));
            } else {
                $response->setStatusCode(403);             
            }
      
        } 


        
        else {
            $response->setStatusCode(499);
        }
    }





    elseif($request->getMethod() == 'DELETE') {           // To delete orders
        if($request->query->getAlpha('action') == 'orderDelete') {
            if($request->query->has('BookingID')) {
                $res = $sqsdb->userExists($request->query->get('BookingID'));
                if($res) {
                    $response->setStatusCode(400);
                } else {
                    $response->setStatusCode(204);
                }
            }
        }

    }
    elseif($request->getMethod() == 'PUT') {              // enqueue, add comment
        $response->setStatusCode(400);
    }
} else {
    $redirect = new RedirectResponse($_SERVER['REQUEST_URI']);
}

// Do logging just before sending response?



$response->send();


if($_GET["action"] == 'fetch_all')
{
	$data = $sqsdb->fetch_all();
}

if($_GET["action"] == 'insert')
{
	$data = $sqsdb->insert();
}

if($_GET["action"] == 'fetch_single')
{
	$data = $sqsdb->fetch_single($_GET["id"]);
}

if($_GET["action"] == 'update')
{
	$data = $sqsdb->update();
}

if($_GET["action"] == 'delete')
{
	$data = $sqsdb->delete($_GET["id"]);
}

echo json_encode($data);

?>
