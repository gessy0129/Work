<?php

// 定数定義. 設定ファイルを作る場合はこの部分を移行する
$host       = 'http://mbot.adfrap.com/fam/javascriptGetAd.php?';
$version    = '1.5';
$format     = 'wap';
//$partner_id = '738e003f4f6efede'; // テスト用 - パートナーでUNIQUEなので固定
$partner_id = 'fd4075db58c852ce'; // 本番用
$size       = '320x50';              // Adfrapの広告サイズ
$javascript = 'yes';
$timeout    = 7;

// パラメータ処理
$clickurl = isset($_REQUEST['clickurl']) ? $_REQUEST['clickurl'] : null;
$topic_id = isset($_REQUEST['topic_id']) ? $_REQUEST['topic_id'] : null;
$size     = isset($_REQUEST['size']) ? $_REQUEST['size'] : $size;
//広告サイズをAdfrap用に変換する
list($width, $height) = explode('x', preg_replace('/^.*?([1-9])/', '${1}', $size)); 

// AdMakerカテゴリとAdFlapカテゴリのマッチング
// TODO: 悩み中
// The unique ID provided by the Adfrap to represent the site (or location on a site) where an ad will be displayed. 
$site_id_lists = array('21' => 22615, //Blog
                       '3' => 22617,  //Books
                       '4' => 22623,  //Businness
                       '5' => 22616,  //Education
                       '7' => 22609,  //Entertainment
                       '6' => 22618,  //Fainance
                       '1' => 22607,  //Games
                       '8' => 22612,  //Health & Fitness
                       '18' => 22617, //Lifestyle
                       '2' => 22626,  //Medical
                       '16' => 22613, //Mysic
                       '14' => 22622, //Navigation
                       '15' => 22611, //News
                       '10' => 22628, //Photography
                       '23' => 22614, //PortalSites
                       '9' => 22620,  //Productivity
                       '20' => 22624, //Reference
                       '12' => 22610, //Social Networking
                       '11' => 22625, //Sports
                       '19' => 22619, //Travel
                       '17' => 22608, //Utilty
                       '13' => 22621  //Weather
                       );

$site_id = isset($site_id_lists[$topic_id]) ? $site_id_lists[$topic_id] : $site_id_lists[array_rand($site_id_lists)];

// HTTPのheader作成
$opts = array(
        'http'=>array(
            'method'        => 'GET',
            'timeout'       => $timeout, // Adflapは大体3〜4秒で返してくる
            'ignore_errors' => true, // WARNING抑制
            'header'        => 'User-Agent: ' . $_SERVER['HTTP_USER_AGENT'] . "\r\n"
            )
        );
$context = stream_context_create($opts);

// URL生成
$params['partner_id'] = $partner_id;
$params['site_id']    = $site_id;
$params['version']    = $version;
$params['format']     = $format;
$params['JAVASCRIPT'] = $javascript;
$params['WIDTH']      = $width;
$params['HEIGHT']     = $height;
$url = $host . http_build_query($params);

// リクエスト実行
try {
    $ad_html = file_get_contents($url, false, $context);
    // 取得確認
    if ($ad_html === false || empty($ad_html)) {
        throw new Exception('ad empty');
    }
    //URL 書き換え。リダイレクタ付与と、target属性指定
    $ad_html = preg_replace("/href=(\\\[\"|'])/", "target=\\\"_parent\\\" href=\${1}" . $clickurl, $ad_html);
} catch (Exception $e) {
    error_log($url);
    $ad_html = false;
}


?>
<html><head></head><body style="margin:0">
<?php if ($ad_html !== false) : ?>
<script type="text/javascript"><?php echo $ad_html; ?></script>
<?php else : ?>
<?php echo include('./no_ad.html'); ?>
<?php endif; ?>
</body></html>
