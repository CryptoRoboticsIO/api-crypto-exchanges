<?php


  header('Access-Control-Allow-Origin: *');
  date_default_timezone_set('America/New_York');
  ini_set('error_reporting', E_ALL);
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);


  define('PATH', $_SERVER['DOCUMENT_ROOT'].'/api/');


  class cacheBase {
    public function selectCache($x, $t){
      if(file_exists('cache/'.$x.'.json')){
        $mt = explode('.', microtime(true))[0];
        $nc = filectime('cache/'.$x.'.json')+$t;
        if($nc > $mt){
          print file_get_contents('cache/'.$x.'.json');exit;
        }
      }
    }
    public function updateCache($x, $c){
      file_put_contents('cache/'.$x.'.json', $c);
    }
  }


  function get_user_agent($url){
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_USERAGENT, "My User Agent Name");
    curl_setopt($curl, CURLOPT_FAILONERROR, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $html = curl_exec($curl);
    curl_close($curl);
    return $html;
  }


  function go($url){
    return json_decode(get_user_agent($url), true);
  }


  $exchanges = ['bittrex', 'poloniex'];


?>
