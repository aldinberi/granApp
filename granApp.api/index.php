<?php

$config = include('config.php');

require 'lib/database.php';

require 'vendor/autoload.php';

use \Firebase\JWT\JWT;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Bearer-Jwt, Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS, PATCH');
header('Access-Control-Expose-Headers: Content-Range');

/* Handle the OPTIONS request */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    /* A 200 OK response to preflight with empty body */
    header('HTTP/1.0 200');
    die();
}

Flight::set('JWT_SECRET', 'TjPeGT5NfHG9tf7kIGIX');
Flight::route('/', function(){
  echo "Hello world";
});

Flight::before('start', function(&$params, &$output){
  // print_r(getallheaders()); die;
  if (Flight::request()->url != '/login' && strpos(Flight::request()->url, 'register') === false){
    $headers = getallheaders();
    // print_r($headers['Bearer-Jwt']); die;
    try {
      $decoded = (array)JWT::decode($headers['Bearer-Jwt'], Flight::get('JWT_SECRET'), array('HS256'));
      Flight::set('user', $decoded);
    } catch (\Exception $e) {
      print_r($e->getMessage()); //die;
      Flight::halt(403, json_encode(['msg' => "token ne valja"]));
      die;
    }
  }
});

Flight::route('POST /login', function(){
  $login_data = Flight::request()->data->getData();
  $database = new Database();
  //$login_data['password'] = password_hash($login_data['password'], PASSWORD_DEFAULT);
  $sth = $database->handler->prepare("SELECT password FROM user WHERE :email = email");
  $sth->execute(['email' => $login_data['username']]);
  $password= $sth->fetchColumn();
  if(password_verify($login_data['password'], $password)){
  $vendors = "SELECT s.user_id, s.name, s.lastname, s.email, s.user_type_id
              FROM user as s
              WHERE s.email = :email";
  $statment = $database->handler->prepare($vendors);
  $statment->execute(['email' => $login_data['username']]);
  $vendors = $statment->fetchAll();
  if(count($vendors) == 1){
    $user =$vendors[0];
    $jwt = JWT::encode($user, Flight::get('JWT_SECRET'));
    Flight::json(["token" => $jwt]);
  }else{
    Flight::halt(404, json_encode(['message'=>'No user']));
  }
}else{
    Flight::halt(404, json_encode(['message'=>'No user']));
  }
});

Flight::route('GET /productlowprices', function(){
    $database = new Database();
    $products = "SELECT p.name, p.grammage, p.unit as unit, pv.price as lowest_price, v.company as company, p.date_added as date_added
                FROM `product-vendor` as pv
                INNER JOIN vendor as v ON pv.vendor_id = v.vendor_id
                INNER JOIN product as p ON p.product_id = pv.product_id
                WHERE pv.price IN (
                	SELECT MIN(pv.price) as lowest_price FROM `product-vendor` as pv
                    JOIN product as p ON p.product_id = pv.product_id GROUP BY p.name
                )";
    $statment = $database->handler->prepare($products);
    $statment->execute();
    $products = $statment->fetchAll();
    Flight::halt(200, json_encode($products));
});

Flight::route('GET /products', function(){
    $database = new Database();
    $products = "SELECT p.product_id as id, p.name, p.grammage, p.unit as unit, pv.price as price, v.user_id as user_id, p.date_added as date_added
                FROM `product-vendor` as pv
                INNER JOIN vendor as v ON pv.vendor_id = v.vendor_id
                INNER JOIN product as p ON p.product_id = pv.product_id";
    $statment = $database->handler->prepare($products);
    $statment->execute();
    $products = $statment->fetchAll();
    Flight::halt(200, json_encode($products));
});

Flight::route('GET /vendorProducts/@userId', function($userId){
  $database = new Database();
  $products = "SELECT p.product_id as id, p.name as name, p.grammage as grammage, p.unit as unit, pv.price as price, p.date_added as date_added
               FROM `product-vendor` as pv
               INNER JOIN product as p ON p.product_id = pv.product_id
               JOIN vendor v on v.vendor_id = pv.vendor_id
               WHERE v.user_id = :userId";
  $statment = $database->handler->prepare($products);
  //$statment->execute(['userId' => Flight::get('user')['user_id']]);
  $statment->execute(['userId' => $userId]);
  $products = $statment->fetchAll();
  Flight::json($products);
});

Flight::route('GET /vendorProducts/@user_id/@product_id', function($user_id, $product_id){
  $database = new Database();
  $products = "SELECT p.product_id as id, p.name as name, p.grammage as grammage, p.unit as unit, pv.price as price, p.date_added as date_added
               FROM `product-vendor` as pv
               INNER JOIN product as p ON p.product_id = pv.product_id
               JOIN vendor v on v.vendor_id = pv.vendor_id
               WHERE v.user_id = :user_id AND p.product_id = :product_id";
  $statment = $database->handler->prepare($products);
  //$statment->execute(['userId' => Flight::get('user')['user_id']]);
  $statment->execute(['user_id' => $user_id, 'product_id' => $product_id]);
  $products = $statment->fetchAll();
  Flight::json($products);
});

Flight::route('GET /vendors', function(){
    $database = new Database();
    $vendors = "SELECT s.user_id as id, s.name as name, s.lastname as lastname, s.address as address, s.email as email, v.company as company
                FROM user as s
                INNER JOIN vendor as v ON s.user_id = v.user_id";
    $statment = $database->handler->prepare($vendors);
    $statment->execute();
    $vendors = $statment->fetchAll();
    Flight::json($vendors);
});

Flight::route('GET /records', function(){
    $database = new Database();
    $record = "SELECT record_id, record_string FROM record";
    $statment = $database->handler->prepare($record);
    $statment->execute();
    $record = $statment->fetchAll();
    Flight::json($record);
});

Flight::route('GET /vendors/@userId', function($userId){
    $database = new Database();
    $vendors = "SELECT s.name as name, s.lastname as lastname, s.address as address, s.email as email, s.user_type_id as type, v.company as company
                FROM user as s
                INNER JOIN vendor as v ON s.user_id = v.user_id
                WHERE s.user_id = :userId";
    $statment = $database->handler->prepare($vendors);
    $statment->execute(['userId' => $userId]);
    $vendors = $statment->fetchAll();
    Flight::json($vendors);
});

Flight::route('GET /customers', function(){
    $database = new Database();
    $customer = "SELECT s.user_id as id, s.name as name, s.lastname as lastname, s.address as address, s.email as email
                FROM user as s
                INNER JOIN customer as c ON s.user_id = c.user_id";
    $statment = $database->handler->prepare($customer);
    $statment->execute();
    $customer = $statment->fetchAll();
    Flight::json($customer);
});

Flight::route('GET /user/@user_id', function($user_id){
    $database = new Database();
    $customer = "SELECT s.user_id as id, s.name as name, s.lastname as lastname, s.address as address, s.email as email, s.user_type_id as type
                FROM user as s
                WHERE user_id = :user_id";
    $statment = $database->handler->prepare($customer);
    $statment->execute(['user_id' => $user_id]);
    $customer = $statment->fetchAll();
    Flight::halt(200, json_encode($customer));
});

Flight::route('GET /cupons', function(){
    $database = new Database();
    $vendors = "SELECT p.name as name, pv.price as price, c.cupon_id as id, c.new_price as new_price, c.cupon_code as cupon_code, (SELECT company FROM vendor WHERE pv.vendor_id = vendor_id) as company, (SELECT user_id FROM vendor WHERE pv.vendor_id = vendor_id) as user_id
                FROM cupon as c
                INNER JOIN `product-vendor` as pv ON c.pv_id = pv.pv_id
                INNER JOIN product as p ON p.product_id = pv.product_id";
    $statment = $database->handler->prepare($vendors);
    $statment->execute();
    $vendors = $statment->fetchAll();
    Flight::halt(200, json_encode($vendors));
});

Flight::route('GET /vendorCupons/@userId/@cupon_id', function($userId, $cupon_id){
    $database = new Database();
    $vendors = "SELECT c.cupon_id as id, p.product_id as product_id, c.pv_id as pv_id, p.name as name, pv.price as price, c.new_price as new_price, c.cupon_code as cupon_code
                FROM cupon as c
                INNER JOIN `product-vendor` as pv ON c.pv_id = pv.pv_id
                INNER JOIN product as p ON p.product_id = pv.product_id
                JOIN vendor v on v.vendor_id = pv.vendor_id
                WHERE v.user_id = :userId AND c.cupon_id =:cupon_id";
    $statment = $database->handler->prepare($vendors);
    $statment->execute(['userId' => $userId, 'cupon_id' => $cupon_id]);
    $vendors = $statment->fetchAll();
    Flight::json($vendors);
});


Flight::route('GET /vendorCupons/@userId', function($userId){
    $database = new Database();
    $vendors = "SELECT c.cupon_id as id, c.pv_id as pv_id, p.name as name, pv.price as price, c.new_price as new_price, c.cupon_code as cupon_code
                FROM cupon as c
                INNER JOIN `product-vendor` as pv ON c.pv_id = pv.pv_id
                INNER JOIN product as p ON p.product_id = pv.product_id
                JOIN vendor v on v.vendor_id = pv.vendor_id
                WHERE v.user_id = :userId";
    $statment = $database->handler->prepare($vendors);
    $statment->execute(['userId' => $userId]);
    $vendors = $statment->fetchAll();
    Flight::json($vendors);
});



Flight::route('POST /product/@user_Id', function($user_id){
    $database = new Database();
    $request = Flight::request();
    $intert_data = $request->data->getData();
    $product['name']= $intert_data['name'];
    $product['grammage']= $intert_data['grammage'];
    $product['unit']= $intert_data['unit'];
    $product['date_added']= $intert_data['date_added'];
    $product_vendor['price'] = $intert_data['price'];
    $product_vendor['user_id'] = $user_id;
    $product_vendor['name']= $intert_data['name'];
    $product_vendor['grammage']= $intert_data['grammage'];
    $product_vendor['date_added']= $intert_data['date_added'];
    $q1 = "INSERT INTO `product` (`name`, `grammage` ,  `unit`, `date_added`)
           VALUES (:name, :grammage, :unit ,:date_added)";
    $q2= "INSERT INTO `product-vendor` (`product_id`, `price` , `vendor_id`)
          VALUES ((SELECT product_id FROM product WHERE :name = name AND :grammage = grammage AND :date_added = date_added), :price, (SELECT vendor_id FROM vendor WHERE user_id = :user_id))";
    $statment = $database->handler->prepare($q1);
    $statment->execute($product);
    $statment = $database->handler->prepare($q2);
    $statment->execute($product_vendor);

    $sth = $database->handler->prepare("SELECT product_id FROM product
                                        WHERE :name = name AND :grammage = grammage AND :date_added = date_added AND unit = :unit");
    $sth->execute($product);
    $product_id= $sth->fetchColumn();
    $update = "Vendor with user id $user_id has added product with id $product_id";
    $q = "INSERT INTO `record` (`record_string`)
          VALUES (:record_string)";
    $statment = $database->handler->prepare($q);
    $statment->execute(['record_string' => $update]);

    Flight::halt(200);
});

Flight::route('PUT /product/@user_id/@product_id', function($user_id, $product_id){
    $database = new Database();
    $request = Flight::request();
    $intert_data = $request->data->getData();
    $product['name']= $intert_data['name'];
    $product['grammage']= $intert_data['grammage'];
    $product['date_added']= $intert_data['date_added'];
    $product['unit']= $intert_data['unit'];
    $product['product_id']= $product_id;
    $product_vendor['product_id']= $product_id;
    $product_vendor['price'] = $intert_data['price'];
    $product_vendor['user_id'] = $user_id;
    $q1 = "UPDATE `product`
           SET name = :name, grammage = :grammage, unit = :unit, date_added = :date_added
           WHERE product_id = :product_id";
    $q2= "UPDATE `product-vendor`
          SET  price = :price
          WHERE vendor_id = (SELECT vendor_id FROM vendor WHERE user_id = :user_id) AND product_id = :product_id";
    $statment = $database->handler->prepare($q1);
    $statment->execute($product);
    $statment = $database->handler->prepare($q2);
    $statment->execute($product_vendor);


    $update = " Vendor with user id $user_id has updated product with id $product_id";
    $q = "INSERT INTO `record` (`record_string`)
          VALUES (:record_string)";
    $statment = $database->handler->prepare($q);
    $statment->execute(['record_string' => $update]);
    Flight::halt(200);
});




Flight::route('POST /cupon/@user_id', function($user_id){
    $database = new Database();
    $request = Flight::request();
    $intert_data = $request->data->getData();
    $cupon['user_id'] = $user_id;
    $cupon['product_id'] = $intert_data['product_id'];
    $cupon['cupon_code'] = $intert_data['cupon_code'];
    $cupon['new_price'] = $intert_data['new_price'];
    $q = "INSERT INTO `cupon` (`pv_id`, `cupon_code`, `new_price`)
          VALUES ((SELECT pv_id FROM `product-vendor` WHERE (SELECT vendor_id FROM vendor WHERE :user_id = user_id ) AND :product_id = product_id), :cupon_code, :new_price)";
    $statment = $database->handler->prepare($q);
    $statment->execute($cupon);

    $sth = $database->handler->prepare("SELECT cupon_id FROM cupon
                          WHERE (SELECT pv_id FROM `product-vendor`
                          WHERE (SELECT vendor_id FROM vendor WHERE user_id = :user_id) AND :product_id = product_id) AND
                          cupon_code = :cupon_code AND new_price = :new_price");
    $sth->execute($cupon);
    $cupon_id= $sth->fetchColumn();
    $update = "Vendor with user id $user_id has added cupon with id $cupon_id";
    $q = "INSERT INTO `record` (`record_string`)
          VALUES (:record_string)";
    $statment = $database->handler->prepare($q);
    $statment->execute(['record_string' => $update]);

    Flight::halt(200);
});

Flight::route('POST /register/vendors', function(){
    $database = new Database();
    $request = Flight::request();
    $intert_data = $request->data->getData();
    $user['name'] = $intert_data['name'];
    $user['lastname'] = $intert_data['lastname'];
    $user['address'] = $intert_data['address'];
    $user['email'] = $intert_data['email'];
    $user['password'] = password_hash($intert_data['password'], PASSWORD_DEFAULT);
    $vendor['name'] = $intert_data['name'];
    $vendor['email'] = $intert_data['email'];
    $vendor['company'] = $intert_data['company'];
    $q1 = "INSERT INTO `user` (`name`, `lastname`, `address` ,`email`, `password`, `user_type_id`)
           VALUES (:name, :lastname, :address ,:email, :password, 3)";
    $q2 = "INSERT INTO `vendor`(`user_id`, `company`)
           VALUES ((SELECT user_id FROM user WHERE :name = name AND :email = email), :company)";
    $statment = $database->handler->prepare($q1);
    $statment->execute($user);
    $statment = $database->handler->prepare($q2);
    $statment->execute($vendor);

    $customer['name'] = $intert_data['name'];
    $customer['email'] = $intert_data['email'];
    $sth = $database->handler->prepare("SELECT user_id FROM user WHERE :name = name AND :email = email");
    $sth->execute($customer);
    $id= $sth->fetchColumn();

    $update = "User with id $id has been created";
    $q = "INSERT INTO `record` (`record_string`)
          VALUES (:record_string)";
    $statment = $database->handler->prepare($q);
    $statment->execute(['record_string' => $update]);
    Flight::halt(200);
});

Flight::route('PUT /vendors/@user_id', function($user_id){
    $database = new Database();
    $request = Flight::request();

    $intert_data = $request->data->getData();

    $user_table['name']=$intert_data['name'];
    $user_table['lastname']=$intert_data['lastname'];
    $user_table['address']=$intert_data['address'];
    $user_table['email']=$intert_data['email'];
    $user_table['user_Id']=$user_id;
    $vendor_table['company']=$intert_data['company'];
    $vendor_table['user_Id']=$user_id;
    $q = "UPDATE user
          SET name = :name, lastname = :lastname, address = :address, email = :email
          WHERE user_id = :user_Id";
    $statment = $database->handler->prepare($q);
    $statment->execute($user_table);
    $q = "UPDATE vendor
          SET company = :company
          WHERE user_id = :user_Id";
    $statment = $database->handler->prepare($q);
    $statment->execute($vendor_table);

    $user = Flight::get('user')['user_id'];
    $update = " Vendor with user id $user has updated his information";
    $q = "INSERT INTO `record` (`record_string`)
          VALUES (:record_string)";
    $statment = $database->handler->prepare($q);
    $statment->execute(['record_string' => $update]);

    Flight::halt(200);
});

Flight::route('PUT /user/@user_id', function($user_id){
    $database = new Database();
    $request = Flight::request();

    $intert_data = $request->data->getData();
    $user_table['name']=$intert_data['name'];
    $user_table['lastname']=$intert_data['lastname'];
    $user_table['address']=$intert_data['address'];
    $user_table['email']=$intert_data['email'];
    $user_table['user_Id']=$user_id;
    $q = "UPDATE user
          SET name = :name, lastname = :lastname, address = :address, email = :email
          WHERE user_id = :user_Id";
    $statment = $database->handler->prepare($q);
    $statment->execute($user_table);

    $user = Flight::get('user')['user_id'];
    $update = "User with id $user updated his information";
    $q = "INSERT INTO `record` (`record_string`)
          VALUES (:record_string)";
    $statment = $database->handler->prepare($q);
    $statment->execute(['record_string' => $update]);
    Flight::halt(200);
});

Flight::route('PUT /vendorCupons/@cupon_id', function($cupon_id){
    $database = new Database();
    $request = Flight::request();

    $intert_data = $request->data->getData();
    $cupon['cupon_id'] = $cupon_id;
    $cupon['cupon_code'] = $intert_data['cupon_code'];
    $cupon['new_price'] = $intert_data['new_price'];
    $q = "UPDATE cupon
          SET cupon_code = :cupon_code, new_price = :new_price
          WHERE cupon_id = :cupon_id";
    $statment = $database->handler->prepare($q);
    $statment->execute($cupon);

    $user = Flight::get('user')['user_id'];
    $update = "Vendor with user id $user updated cupon with id $cupon_id";
    $q = "INSERT INTO `record` (`record_string`)
          VALUES (:record_string)";
    $statment = $database->handler->prepare($q);
    $statment->execute(['record_string' => $update]);

    Flight::halt(200);
});

Flight::route('PUT /password/@user_id', function($user_id){
    $database = new Database();
    $request = Flight::request();
    $intert_data = $request->data->getData();
    $password['user_id'] = $user_id;
    $password['new_password'] = password_hash($intert_data['new_password'], PASSWORD_DEFAULT);
    $q = "UPDATE user
          SET password = :new_password
          WHERE user_id = :user_id";
    $statment = $database->handler->prepare($q);
    $statment->execute($password);

    $user = Flight::get('user')['user_id'];
    $update = "User with id $user updated his password";
    $q = "INSERT INTO `record` (`record_string`)
          VALUES (:record_string)";
    $statment = $database->handler->prepare($q);
    $statment->execute(['record_string' => $update]);

    Flight::halt(200);
});


Flight::route('POST /register/customers', function(){
    $database = new Database();
    $request = Flight::request();
    $intert_data = $request->data->getData();
    $user['name'] = $intert_data['name'];
    $user['lastname'] = $intert_data['lastname'];
    $user['address'] = $intert_data['address'];
    $user['email'] = $intert_data['email'];
    $user['password'] = password_hash($intert_data['password'], PASSWORD_DEFAULT);
    $customer['name'] = $intert_data['name'];
    $customer['email'] = $intert_data['email'];
    $q1 = "INSERT INTO `user` (`name`, `lastname`, `address`, `email`, `password`, `user_type_id`)
           VALUES (:name, :lastname, :address ,:email, :password, 2)";
    $q2 = "INSERT INTO `customer` (`user_id`)
           VALUES ((SELECT user_id FROM user WHERE :name = name AND :email = email))";
    $statment = $database->handler->prepare($q1);
    $statment->execute($user);
    $statment = $database->handler->prepare($q2);
    $statment->execute($customer);

    $sth = $database->handler->prepare("SELECT user_id FROM user WHERE :name = name AND :email = email");
    $sth->execute($customer);
    $s= $sth->fetchColumn();
    $update = "Customer with user id $s has been created";
    $q = "INSERT INTO `record` (`record_string`)
          VALUES (:record_string)";
    $statment = $database->handler->prepare($q);
    $statment->execute(['record_string' => $update]);
    Flight::halt(200);
});


Flight::route('DELETE /user/@user_id', function($user_id){
    $database = new Database();
    $q = "DELETE FROM `user` WHERE user_id = :user_id";
    $statment = $database->handler->prepare($q);
    $statment->execute(['user_id' => $user_id]);

    $update = "User with id $user_id has been deleted";
    $q = "INSERT INTO `record` (`record_string`)
          VALUES (:record_string)";
    $statment = $database->handler->prepare($q);
    $statment->execute(['record_string' => $update]);
    Flight::halt(200);
});

Flight::route('DELETE /product/@product_id', function($product_id){
    $database = new Database();
    $q = "DELETE FROM `product` WHERE product_id = :product_id";
    $statment = $database->handler->prepare($q);
    $statment->execute(['product_id' => $product_id]);

    $user = Flight::get('user')['user_id'];
    $update = " Vendor with user id $user has deleted product with id $product_id";
    $q = "INSERT INTO `record` (`record_string`)
          VALUES (:record_string)";
    $statment = $database->handler->prepare($q);
    $statment->execute(['record_string' => $update]);

    Flight::halt(200);
});

Flight::route('DELETE /cupon/@id', function($id){
    $database = new Database();
    $q = "DELETE FROM `cupon` WHERE cupon_id=:id";
    $statment = $database->handler->prepare($q);
    $statment->execute(['id' => $id]);

    $user = Flight::get('user')['user_id'];
    $update = " Vendor with user id $user has deleted cupon with id $id";
    $q = "INSERT INTO `record` (`record_string`)
          VALUES (:record_string)";
    $statment = $database->handler->prepare($q);
    $statment->execute(['record_string' => $update]);

    Flight::halt(200);
});

Flight::route('DELETE /record/@record_id', function($record_id){
    $database = new Database();
    $q = "DELETE FROM record WHERE record_id = :record_id";
    $statment = $database->handler->prepare($q);
    $statment->execute(['record_id' => $record_id]);

    Flight::halt(200);
});




Flight::start();
