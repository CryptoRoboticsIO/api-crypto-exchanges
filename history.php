<?php


  include_once 'core.php';


  // Get exchange

  if(!isset($_GET['exchange']) || empty($_GET['exchange'])){print json_encode(['success'=>'false', 'error_code'=>'100']);exit;}
  $exchange = mb_strtolower($_GET['exchange']);
  if(!in_array($exchange, $exchanges)){print json_encode(['success'=>'false', 'error_code'=>'101']);exit;}
  if(!isset($_GET['name']) || empty($_GET['name'])){print json_encode(['success'=>'false', 'error_code'=>'102']);exit;}
  $name = $_GET['name'];


  // Cache

  $cache = new cacheBase;
  $cache->selectCache('history_'.$exchange.'_'.strtr($name, ['/'=>'_']), 1);


  // Bittrex (/history?exchange=bittrex&name=BTC-LTC)

  if($exchange == 'bittrex'){
    $bittrex_input = go('https://bittrex.com/api/v1.1/public/getmarkethistory?market='.$name)['result'];
    foreach ($bittrex_input as $data) {
      if($data['OrderType'] == 'BUY'){$type = 'buy';}else{$type = 'sell';}
      $bittrex[] = ['quantity'=>$data['Quantity'],'rate'=>$data['Price'],'type'=>$type,'date'=>$data['TimeStamp']];
    }
    $bittrex = json_encode($bittrex);
    $cache->updateCache('history_'.$exchange.'_'.$name, $bittrex);
    print $bittrex;exit;
  }


  // Poloniex (/history?exchange=poloniex&name=BTC_NXT)

  if($exchange == 'poloniex'){
    $poloniex_input = go('https://poloniex.com/public?command=returnTradeHistory&currencyPair='.$name);
    if(empty($poloniex_input[0])){print json_encode(['success'=>'false', 'error_code'=>'103']);exit;}
    foreach ($poloniex_input as $data) {
      $poloniex[] = ['quantity'=>$data['amount'],'rate'=>$data['rate'],'type'=>$data['type'],'date'=>$data['date']];
    }
    $poloniex = json_encode($poloniex);
    $cache->updateCache('history_'.$exchange.'_'.$name, $poloniex);
    print $poloniex;exit;
  }


?>
