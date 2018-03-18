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


  // Bittrex (/sell?exchange=bittrex&name=BTC-LTC&quantity=1&rate=0.001&key=123:123)

  if($exchange == 'bittrex'){
    include_once PATH.'/core/bittrex.php';
    $login = explode(':', $key);
    $sell = bittrex_auth('https://bittrex.com/api/v1.1/market/selllimit?market='.$name.'&quantity='.$quantity.'&rate='.$rate ,$login[0],$login[1]);
    if($sell['success'] == 1){
      print json_encode(['success'=>'true']);
    }else{
      print json_encode(['success'=>'false', 'error'=>$sell['message']]);
    }
  }


  // Poloniex (/sell?exchange=poloniex&name=BTC_LTC&quantity=1&rate=0.001&key=123:123)

  if($exchange == 'poloniex'){
    include_once PATH.'/core/poloniex.php';
    $login = explode(':', $key);
    $poloniex = new poloniex($login[0], $login[1]);
    $sell = $poloniex->sell($name, $rate, $quantity);
    if(isset($sell['error']) && !empty($sell['error'])){
      print json_encode(['success'=>'false', 'error'=>$sell['error']]);
    }else{
      print json_encode(['success'=>'true']);
    }
  }


?>
