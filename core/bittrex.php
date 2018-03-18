 <?php

  function bittrex_auth($get,$apikey,$apisecret){
    $nonce=time();
    $uri=$get.'&apikey='.$apikey.'&nonce='.$nonce;
    $sign=hash_hmac('sha512',$uri,$apisecret);
    $ch = curl_init($uri);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:'.$sign));
    $execResult = curl_exec($ch);
    $obj = json_decode($execResult, true);
    return $obj;
  }

 ?>
