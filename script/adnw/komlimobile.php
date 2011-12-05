<?php

// 定数定義. 設定ファイルを作る場合はこの部分を移行する
$timeout = 20;
$host    = 'http://a.zestadz.com/waphandler/deliverad?';
//query param
$q['cid']           = '14131C047A504041455742564357475C8B892433';
//$q['cid']           = '14131C047A504041455840504652425C8B892734';
//$q['cid']           = '14131C047A5040414558405D45594701D0D97E6D18298DE047A8EB6AB36844F257A3B7724200E79BE9DB0F07B7';
$q['response_type'] = 'xml';
$q['ua']            = $_SERVER['HTTP_USER_AGENT'];
$q['ip']            = $_SERVER['REMOTE_ADDR'];
$q['ad_type']       = "Text+Picture";
$q['url']           = isset($_REQUEST['url']) ? $_REQUEST['url'] : null;
$q['meta']          = 'mobile';

// パラメータ処理
$clickurl   = isset($_REQUEST['clickurl']) ? $_REQUEST['clickurl'] : null;

// HTTPのheader作成
$opts = array(
        'http'=>array(
            'method'        => 'GET',
            'timeout'       => $timeout,
            'ignore_errors' => true, // WARNING抑制
            'header'        => 'User-Agent: ' . $q['ua'] . "\r\n",
            )
        );
$context = stream_context_create($opts);

// URL生成
$url = $host . http_build_query($q);

// リクエスト実行
$ad_html = null;
try {
    $ad_xml = file_get_contents($url, false, $context);
    // 取得確認
    if ($ad_xml === false || empty($ad_xml)) {
        throw new Exception('ad empty');
    }
    $xml_obj = simplexml_load_string($ad_xml);
    $lp_url = $clickurl . $xml_obj->ad->url;
    $ad_attr = $xml_obj->attributes();
    if ($ad_attr['type'] === 'text') {
        $ad_html =<<< EOL
<a href="$lp_url">$xml_obj->ad->text</a>
EOL;
    }
    if ($ad_attr['type'] === 'picture') {
        $ad_html =<<< EOL2
<a href="$lp_url"><img src="$xml_obj->ad->picture" /></a>
EOL2;
    }
    
} catch (Exception $e) {
    echo $url;
    //error_log($url);  //DEBUG
    $tag = null;
}

?>
<?php if ($ad_html) : ?>
<?php echo $ad_html; ?>
<?php else : ?>
<?php header('Content-type: text/xml'); ?>
<?php echo $ad_xml;  ?>
<?php //include ('./no_ad.html'); ?>
<?php endif; ?>
