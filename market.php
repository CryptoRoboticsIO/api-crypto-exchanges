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
  $cache->selectCache('market_'.$exchange.'_'.strtr($name, ['/'=>'_']), 1);


  // Bittrex (/market?exchange=bittrex&name=BTC-LTC)

  if($exchange == 'bittrex'){
    $bittrex_input = go('https://bittrex.com/api/v1.1/public/getticker?market='.$name)['result'];
    if(empty($bittrex_input['Ask'])){print json_encode(['success'=>'false', 'error_code'=>'103']);exit;}
    $bittrex = json_encode(['ask'=>$bittrex_input['Ask'],'bid'=>$bittrex_input['Bid']]);
    $cache->updateCache('market_'.$exchange.'_'.$name, $bittrex);
    print $bittrex;exit;
  }


  // Poloniex (/market?exchange=poloniex&name=BTC_BCN)

  if($exchange == 'poloniex'){
    $poloniex_input = go('https://poloniex.com/public?command=returnTicker');
    if(empty($poloniex_input[$name]['lowestAsk'])){print json_encode(['success'=>'false', 'error_code'=>'103']);exit;}
    $poloniex = json_encode(['ask'=>$poloniex_input[$name]['lowestAsk'],'bid'=>$poloniex_input[$name]['highestBid']]);
    $cache->updateCache('market_'.$exchange.'_'.$name, $poloniex);
    print $poloniex;exit;
  }


?>
