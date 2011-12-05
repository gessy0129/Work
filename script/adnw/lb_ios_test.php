<?php
/**
 * LeadBolt iPhone用プログラム
 * 
 */

// 定数定義. 設定ファイルを作る場合はこの部分を移行する
//http://api.leadbolt.net/api/ad_feed?section_id=843317085&secret_key=947a688d07c57b77&client_ip=%22.$ip.%22&user_agent=%22.$ua.%22&width=320&height=50&format=json&ad_type=Text,Image
$host    = 'http://api.leadbolt.net/api/ad_feed?';
$format  = 'json';
$ad_type = 'Image';
$timeout = 7;
$width   = 320;
$height  = 50;
$pf      = 1;
$section_id = '775463877';
$secret_key = '21d22c9ab7aa0ced';

// パラメータ処理
$clickurl   = isset($_REQUEST['clickurl']) ? $_REQUEST['clickurl'] : null;

$ua = $_SERVER['HTTP_USER_AGENT'];
$ip = isset($_REQUEST['ip']) ? $_REQUEST['ip'] : $_SERVER['REMOTE_ADDR'];
$x_forwarded_for = $ip;
// HTTPのheader作成
$opts = array(
        'http'=>array(
            'method'        => 'GET',
            'timeout'       => $timeout,
            'ignore_errors' => true, // WARNING抑制
            'header'        => 'User-Agent: ' . $ua .
                               "\r\nX-Forwarded-For: $x_forwarded_for\r\n",
            )
        );
$context = stream_context_create($opts);

// URL生成
$params['section_id'] = $section_id;
$params['secret_key'] = $secret_key;
$params['format']     = $format;
$params['ad_type']    = $ad_type;
$params['width']      = $width;
$params['height']     = $height;
$params['user_agent'] = $ua;
$params['client_ip']  = $ip;
/**
 * pf, dev_id 無しでも問題ないらしい
$params['pf']         = $pf;
$params['dev_id']     = md5(uniqid(rand(), true)); //UDIDを偽装
 */

$url = $host . http_build_query($params);

// リクエスト実行
$tag = null;
try {
    $ad_json = @file_get_contents($url, false, $context);
    // 取得確認
    if ($ad_json === false || empty($ad_json)) {
        throw new Exception('ad empty');
    }
    $ad = json_decode($ad_json, true);
    if ($ad === false || empty($ad)) {
        throw new Exception($ad);
    }
    $tag = $ad[0];
    $clickurl = $clickurl . $tag['clk_url'];
} catch (Exception $e) {
    error_log($url);  //DEBUG
    $tag = null;
}
?>
<?php if (!is_null($tag)): ?>
<?php if ($tag['ad_type'] === 'Image'): ?>
<a href="<?php echo $clickurl; ?>" title="<?php echo $tag['title']; ?>"><img src="<?php echo $tag['src_url']; ?>" alt="<?php echo $tag['description']; ?>" /></a>
<?php endif; ?>
<?php else: ?>
<?php include('./no_ad.html'); ?>
<?php endif; ?>
