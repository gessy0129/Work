<?php
// 定数定義. 設定ファイルを作る場合はこの部分を移行する
$domain     = 'http://im.ov.yahoo.co.jp/';
$path       = 'ads_smartphone.js?';
$zone       = '12345'; // test //DEBUG
$ip         = null;
$ua         = null;
$width      = 320;
$height     = 50;
$mode       = 'test';
$format     = 'html'; //html or json
$timeout    = 7;

// パラメータ処理
$clickurl = isset($_REQUEST['clickurl']) ? $_REQUEST['clickurl'] : null;
$size     = isset($_REQUEST['size']) ? $_REQUEST['size'] : null;
//広告サイズをAdfrap用に変換する
if (!is_null($size)) {
    list($width, $height) = explode('x', preg_replace('/^.*?([1-9])/', '${1}', $size)); 
}
$ua = $_SERVER['HTTP_USER_AGENT'];
$ip = isset($_SERVER["HTTP_CLIENT_IP"]) ? $_SERVER["HTTP_CLIENT_IP"] : $_SERVER["REMOTE_ADDR"];

// HTTPのheader作成
$opts = array(
        'http'=>array(
            'method'        => 'GET',
            'timeout'       => $timeout, 
            'ignore_errors' => true,     // WARNING抑制
            'header'        => 'User-Agent: ' . $ua . "\r\n"
            )
        );
$context = stream_context_create($opts);

// URL生成
$params['zone']   = $zone;
$params['ip']     = $ip;
$params['ua']     = $ua;
$params['format'] = $format;
$params['mode']   = $mode;
$params['w']      = $width;
$params['h']      = $height;
$url = $domain . $path . http_build_query($params);
error_log($url);

// リクエスト実行
try {
    $ad_html = file_get_contents($url, false, $context);
    // 取得確認
    if ($ad_html === false || empty($ad_html)) {
        throw new Exception('ad empty');
    }
    //URL 書き換え。リダイレクタ付与と、target属性指定
    $ad_html = preg_replace("/href=([\"|'])/", "target=\"_blank\" href=\${1}" . $clickurl, $ad_html);
} catch (Exception $e) {
    error_log($url);
    $ad_html = false;
}


?>
<html><head></head><body style="margin:0">
<?php if ($ad_html !== false) : ?>
<?php echo $ad_html; ?>
<?php else : ?>
<?php echo include('./no_ad.html'); ?>
<?php endif; ?>
</body></html>
