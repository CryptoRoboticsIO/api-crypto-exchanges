<?php


  include_once 'core.php';


  // Get exchange

  if(!isset($_GET['exchange']) || empty($_GET['exchange'])){print json_encode(['success'=>'false', 'error_code'=>'100']);exit;}
  $exchange = mb_strtolower($_GET['exchange']);
  if(!in_array($exchange, $exchanges)){print json_encode(['success'=>'false', 'error_code'=>'101']);exit;}
  if(!isset($_GET['key']) || empty($_GET['key'])){print json_encode(['success'=>'false', 'error_code'=>'105']);exit;}
  $key = $_GET['key'];


  // Bittrex (/balance?exchange=bittrex&key=123:123)

  if($exchange == 'bittrex'){
    include_once PATH.'/core/bittrex.php';
    $login = explode(':', $key);
    $balance_input = bittrex_auth('https://bittrex.com/api/v1.1/account/getbalances?',$login[0],$login[1]);
    if($balance_input['success'] == 1){
      foreach ($balance_input['result'] as $key => $value) {
        $balance[$value['Currency']] = ['balance'=>$value['Balance'], 'available'=>$value['Available']];
      }
      print json_encode(['success'=>'true', 'balance'=>json_encode($balance)]);
    }else{
      print json_encode(['success'=>'false', 'error'=>$balance_input['message']]);
    }
  }


  // Poloniex (/balance?exchange=poloniex&key=123:123)

  if($exchange == 'poloniex'){
    include_once PATH.'/core/poloniex.php';
    $login = explode(':', $key);
    $poloniex = new poloniex($login[0], $login[1]);
    $balance_input = $poloniex->get_balances();
    if(!empty($balance_input['error'])){
      print json_encode(['success'=>'false', 'error'=>$balance_input['error']]);exit;
    }
    foreach ($balance_input as $key => $value) {
      $balance[$key] = ['balance'=>$value, 'available'=>$value];
    }
    print json_encode(['success'=>'true', 'balance'=>json_encode($balance)]);exit;
  }


?>
