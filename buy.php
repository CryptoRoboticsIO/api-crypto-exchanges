<?php


  include_once 'core.php';


  // Get exchange

  if(!isset($_GET['exchange']) || empty($_GET['exchange'])){print json_encode(['success'=>'false', 'error_code'=>'100']);exit;}
  $exchange = mb_strtolower($_GET['exchange']);
  if(!in_array($exchange, $exchanges)){print json_encode(['success'=>'false', 'error_code'=>'101']);exit;}
  if(!isset($_GET['name']) || empty($_GET['name'])){print json_encode(['success'=>'false', 'error_code'=>'102']);exit;}
  $name = $_GET['name'];
  if(!isset($_GET['quantity']) || empty($_GET['quantity'])){print json_encode(['success'=>'false', 'error_code'=>'103']);exit;}
  $quantity = $_GET['quantity'];
  if(!isset($_GET['rate']) || empty($_GET['rate'])){print json_encode(['success'=>'false', 'error_code'=>'104']);exit;}
  $rate = $_GET['rate'];
  if(!isset($_GET['key']) || empty($_GET['key'])){print json_encode(['success'=>'false', 'error_code'=>'105']);exit;}
  $key = $_GET['key'];


  // Bittrex (/buy?exchange=bittrex&name=BTC-LTC&quantity=1&rate=0.001&key=123:123)

  if($exchange == 'bittrex'){
    include_once PATH.'/core/bittrex.php';
    $login = explode(':', $key);
    $buy = bittrex_auth('https://bittrex.com/api/v1.1/market/buylimit?market='.$name.'&quantity='.$quantity.'&rate='.$rate ,$login[0],$login[1]);
    if($buy['success'] == 1){
      print json_encode(['success'=>'true']);
    }else{
      print json_encode(['success'=>'false', 'error'=>$buy['message']]);
    }
  }


  // Poloniex (/buy?exchange=poloniex&name=BTC-LTC&quantity=1&rate=0.001&key=123:123)

  if($exchange == 'poloniex'){
    include_once PATH.'/core/poloniex.php';
    $login = explode(':', $key);
    $poloniex = new poloniex($login[0], $login[1]);
    $buy = $poloniex->buy($name, $rate, $quantity);
    if(isset($buy['error']) && !empty($buy['error'])){
      print json_encode(['success'=>'false', 'error'=>$buy['error']]);
    }else{
      print json_encode(['success'=>'true']);
    }
  }


?>
