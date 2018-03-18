<?php


  include_once 'core.php';


  // Get exchange

  if(!isset($_GET['exchange']) || empty($_GET['exchange'])){print json_encode(['success'=>'false', 'error_code'=>'100']);exit;}
  $exchange = mb_strtolower($_GET['exchange']);
  if(!in_array($exchange, $exchanges)){print json_encode(['success'=>'false', 'error_code'=>'101']);exit;}


  // Cache

  $cache = new cacheBase;
  $cache->selectCache('markets_'.$exchange, 5);
  

  // Bittrex (/markets?exchange=bittrex)

  if($exchange == 'bittrex'){
    $bittrex_input = go('https://bittrex.com/api/v1.1/public/getmarketsummaries')['result'];
    foreach ($bittrex_input as $data) {
      $change = $data['PrevDay']/$data['Ask']*100-100;
      $bittrex[$data['MarketName']] = ['ask'=>$data['Ask'],'bid'=>$data['Bid'],'24h'=>$change];
    }
    $bittrex = json_encode($bittrex);
    $cache->updateCache('markets_'.$exchange, $bittrex);
    print $bittrex;exit;
  }


  // Poloniex (/markets?exchange=poloniex)

  if($exchange == 'poloniex'){
    $poloniex_input = go('https://poloniex.com/public?command=returnTicker');
    foreach ($poloniex_input as $name => $data) {
      $change = $data['high24hr']/$data['lowestAsk']*100-100;
      $poloniex[$name] = ['ask'=>$data['lowestAsk'],'bid'=>$data['highestBid'],'24h'=>$change];
    }
    $poloniex = json_encode($poloniex);
    $cache->updateCache('markets_'.$exchange, $poloniex);
    print $poloniex;exit;
  }


?>
