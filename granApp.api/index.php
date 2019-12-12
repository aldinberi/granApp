<?php

$config = include('config.php');

require 'lib/database.php';

require 'PersistanceManager.php';

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

Flight::before('start', function(&$params, &$output){
  if (Flight::request()->url != '/login' && strpos(Flight::request()->url, 'register') === false){
    $headers = getallheaders();
    try {
      $decoded = (array)JWT::decode($headers['Bearer-Jwt'], Flight::get('JWT_SECRET'), array('HS256'));
      Flight::set('user', $decoded);
    } catch (\Exception $e) {
      print_r($e->getMessage());
      Flight::halt(403, json_encode(['msg' => "token ne valja"]));
      die;
    }
  }
});

Flight::route('POST /login', function(){
  $login_data = Flight::request()->data->getData();
  $database = new Database();
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
    $pm = new PersistanceManager();
    //Flight::halt(200, json_encode($pm->get_all_cheapest_products()));
    Flight::json($pm->get_all_cheapest_products());
});

Flight::route('GET /products', function(){
    $pm = new PersistanceManager();
    //Flight::halt(200, json_encode($pm->get_all_products()));
    Flight::json($pm->get_all_products());
});

Flight::route('GET /vendorProducts/@userId', function($userId){
  $pm = new PersistanceManager();
  Flight::json($pm->get_all_products_from_vendor($userId));
});

Flight::route('GET /vendorProducts/@user_id/@product_id', function($user_id, $product_id){
  $pm = new PersistanceManager();
  Flight::json($pm->get_product_from_vendor($user_id, $product_id));
});

Flight::route('GET /vendors', function(){
    $pm = new PersistanceManager();
    Flight::json($pm->get_vendors());
});

Flight::route('GET /records', function(){
    $pm = new PersistanceManager();
    Flight::json($pm->get_records());
});

Flight::route('GET /vendors/@userId', function($userId){
    $pm = new PersistanceManager();
    Flight::json($pm->get_vendor($userId));
});

Flight::route('GET /customers', function(){
    $pm = new PersistanceManager();
    Flight::json($pm->get_customers());
});

Flight::route('GET /user/@user_id', function($user_id){
    $pm = new PersistanceManager();
    Flight::json($pm->get_customer($user_id));
});

Flight::route('GET /cupons', function(){
    $pm = new PersistanceManager();
    Flight::json($pm->get_all_cupons());
});

Flight::route('GET /vendorCupons/@userId/@cupon_id', function($userId, $cupon_id){
    $pm = new PersistanceManager();
    Flight::json($pm->get_vendor_cupon($userId, $cupon_id));
});


Flight::route('GET /vendorCupons/@userId', function($userId){
    $pm = new PersistanceManager();
    Flight::json($pm->get_vendor_cupons($userId));
});

Flight::route('POST /product/@user_Id', function($user_id){
    $pm = new PersistanceManager();
    $request = Flight::request();
    $pm->insert_product($request->data->getData(), $user_id);
    Flight::halt(200);
});

Flight::route('PUT /product/@user_id/@product_id', function($user_id, $product_id){
    $pm = new PersistanceManager();
    $request = Flight::request();
    $pm->update_product($request->data->getData(), $user_id, $product_id);
    Flight::halt(200);
});

Flight::route('POST /cupon/@user_id', function($user_id){
    $pm = new PersistanceManager();
    $request = Flight::request();
    $pm->insert_cupon($request->data->getData(), $user_id);
    Flight::halt(200);
});

Flight::route('POST /register/vendors', function(){
    $pm = new PersistanceManager();
    $request = Flight::request();
    $pm->insert_vendor($request->data->getData());
    Flight::halt(200);
});

Flight::route('PUT /vendors/@user_id', function($user_id){
    $pm = new PersistanceManager();
    $request = Flight::request();
    $pm->update_vendor($request->data->getData(), $user_id);
    Flight::halt(200);
});

Flight::route('PUT /user/@user_id', function($user_id){
    $pm = new PersistanceManager();
    $request = Flight::request();
    $user = Flight::get('user')['user_id'];
    $pm->update_customer($request->data->getData(), $user_id, $user);
    Flight::halt(200);
});

Flight::route('PUT /vendorCupons/@cupon_id', function($cupon_id){
    $pm = new PersistanceManager();
    $request = Flight::request();
    $user = Flight::get('user')['user_id'];
    $pm->update_cupon($request->data->getData(), $cupon_id, $user);
    Flight::halt(200);
});

Flight::route('PUT /password/@user_id', function($user_id){
    $pm = new PersistanceManager();
    $request = Flight::request();
    $user = Flight::get('user')['user_id'];
    $pm->update_password($request->data->getData(), $user_id, $user);
    Flight::halt(200);
});

Flight::route('POST /register/customers', function(){
    $pm = new PersistanceManager();
    $request = Flight::request();
    $pm->insert_customer($request->data->getData());
    Flight::halt(200);
});


Flight::route('DELETE /user/@user_id', function($user_id){
    $pm = new PersistanceManager();
    $pm->delete_user($user_id);
    Flight::halt(200);
});

Flight::route('DELETE /product/@product_id', function($product_id){
    $pm = new PersistanceManager();
    $user = Flight::get('user')['user_id'];
    $pm->delete_product($product_id, $user);
    Flight::halt(200);
});

Flight::route('DELETE /cupon/@id', function($id){
    $pm = new PersistanceManager();
    $user = Flight::get('user')['user_id'];
    $pm->delete_cupon($id, $user);
    Flight::halt(200);
});

Flight::route('DELETE /record/@record_id', function($record_id){
    $pm = new PersistanceManager();
    $pm->delete_record($record_id);
    Flight::halt(200);
});

Flight::start();



