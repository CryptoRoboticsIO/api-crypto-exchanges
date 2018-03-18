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
  $cache->selectCache('orderbook_'.$exchange.'_'.strtr($name, ['/'=>'_']), 1);


  // Bittrex (/orderbook?exchange=bittrex&name=BTC-LTC)

  if($exchange == 'bittrex'){
    $bittrex_input = go('https://bittrex.com/api/v1.1/public/getorderbook?market='.$name.'&type=both')['result'];
    if(empty($bittrex_input['buy'])){print json_encode(['success'=>'false', 'error_code'=>'103']);exit;}
    foreach ($bittrex_input['buy'] as $data) {
      $bittrex_buy[] = ['quantity'=>$data['Quantity'], 'rate'=>$data['Rate']];
    }
    foreach ($bittrex_input['sell'] as $data) {
      $bittrex_sell[] = ['quantity'=>$data['Quantity'], 'rate'=>$data['Rate']];
    }
    $bittrex = json_encode(['buy'=>$bittrex_buy,'sell'=>$bittrex_sell]);
    $cache->updateCache('orderbook_'.$exchange.'_'.$name, $bittrex);
    print $bittrex;exit;
  }


  // Poloniex (/orderbook?exchange=poloniex&name=BTC_NXT)

  if($exchange == 'poloniex'){
    $poloniex_input = go('https://poloniex.com/public?command=returnOrderBook&currencyPair='.$name.'&depth=1000');
    if(empty($poloniex_input['asks'])){print json_encode(['success'=>'false', 'error_code'=>'103']);exit;}
    foreach ($poloniex_input['asks'] as $data) {
      $poloniex_buy[] = ['quantity'=>$data[1], 'rate'=>$data[0]];
    }
    foreach ($poloniex_input['bids'] as $data) {
      $poloniex_sell[] = ['quantity'=>$data[1], 'rate'=>$data[0]];
    }
    $poloniex = json_encode(['buy'=>$poloniex_buy,'sell'=>$poloniex_sell]);
    $cache->updateCache('orderbook_'.$exchange.'_'.$name, $poloniex);
    print $poloniex;exit;
  }


?>
