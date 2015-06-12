<?php
    $salt    = 'Fill in the blank!';
    $aes_key = 'Fill in the blank!';
    $aes_iv  = 'Fill in the blank!';
    $api     = 'http://wifiapi02.51y5.net/wifiapi/fa.cmd';

    function decrypt ($str, $keys, $iv, $cipher_alg = MCRYPT_RIJNDAEL_128)
    {
       $clear  = mcrypt_decrypt($cipher_alg, $keys, pack("H*", $str), MCRYPT_MODE_CBC, $iv);
       $length = intval (substr ($clear, 0, 3));

       return substr ($clear, 3, $length);
    }

    // query data
    $bssid = $_GET['bssids'];
    $ssid  = $_GET['ssids'];

    if (empty ($bssid) || empty ($ssid))
    {
        die ("invalid parameters");
    }

    // go
    $ret   = array ();
    $data  = array (
       'dhid'   => 'Fill in the blank!',
       'st'     => 'm',
       'bssid'  => $bssid,
       'ssid'   => $ssid,
       'v'      => 'Fill in the blank!',
       'appid'  => '0006',
       'uhid'   => 'Fill in the blank!',
       'lang'   => 'cn',
       'chanid' => 'Fill in the blank!',
       'pid'    => 'qryapwd'
    );

    // 计算 sign=
    $sign = ''; ksort ($data);
    foreach ($data as $k => $v)
    {
       $sign .= $v;
    }
    $sign = md5 ($sign . $salt);
    $data['sign'] = strtoupper($sign);

    // 发送请求
    $postdata = http_build_query ($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $data = curl_exec ($ch);
    curl_close ($ch);

//    echo "RAW:\n", $data, "\n";

    // 处理数据
    $json = json_decode ($data, true);
    foreach ($json['psws'] as $bssid => $data)
    {
       array_push ($ret, array (
          'bssid' => $data['bssid'],
          'ssid'  => $data['ssid'], 
          'pass'  => decrypt ($data['pwd'], $aes_key, $aes_iv)));
    }

?>

<!DOCTYPE html>
<html ng-app="klbb">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <title>WIFI 密码搜索</title>
        <link rel="stylesheet" href="css/framework7.min.css">
        <link rel="stylesheet" href="css/my-app.css">
    </head>
    <body>
        <!-- Status bar overlay for fullscreen mode-->
        <div class="statusbar-overlay"></div>
        <!-- Panels overlay-->
        <div class="panel-overlay"></div>
        <div class="panel panel-right panel-cover" id="rightPanelHTML">
        </div>
        <!-- Views-->
        <div class="views">
            <!-- Your main view, should have "view-main" class-->
            <div class="view view-main">
                <!-- Top Navbar-->
                <div class="navbar">
                    <div class="navbar-inner">
                        <div class="center sliding">WIFI 密码搜索</div>
                    </div>
                </div>
                <!-- Pages, because we need fixed-through navbar and toolbar, it has additional appropriate classes-->
                <div class="pages navbar-through toolbar-through">
                    <!-- Page, data-page contains page name-->
                    <div class="page">

                        <div class="page-content">
                            <div class="list-block media-list">
                                <ul>

                                   <?php foreach ($ret as $wifi) { ?>
                                    <li class="item-content">
                                        <div class="item-inner">
                                            <div class="item-title-row">
                                               <div class="item-title"><?= $wifi['ssid'] ?></div>
                                               <div class="item-after"><?= $wifi['bssid'] ?></div>
                                            </div>

                                            <div class="item-subtitle"><?= $wifi['pass'] ?></div>
                                        </div>
                                    </li>
                                    <?php } ?>

                                </ul>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>

        <script type="text/javascript" src="js/framework7.min.js"></script>
        <script type="text/javascript" src="js/my-app.js"></script>
    </body>
</html>
