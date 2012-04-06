<?php
// If necessary, reference the sdk.class.php file. 
// For example, the following line assumes the sdk.class.php file is 
// in an sdk sub-directory relative to this file
require_once dirname(__FILE__) . '../../lib/sdk/sdk.class.php';

// Instantiate the class
$options = array('default_cache_config' => '/tmp/cache',
                 'key' => 'YOUR ID',
                 'secret' => 'YOUR ID');
$dynamodb = new AmazonDynamoDB($options);
// region japan
$dynamodb->set_hostname('https://dynamodb.ap-northeast-1.amazonaws.com');
const VCV_TABLE_NAME = 'test-table';

####################################################################
# Adding data to the table

echo PHP_EOL . PHP_EOL;
# Adding data to the table

try {
    // Add items
    echo "# Adding data to the table..." . PHP_EOL;
    $put_response = $dynamodb->put_item(array(
            'TableName' => VCV_TABLE_NAME,
            'Item' => array(
                'ID'           => array( AmazonDynamoDB::TYPE_NUMBER => '100019'  ), // Hash Key
                'UID'             => array( AmazonDynamoDB::TYPE_STRING => 'user_e'   ), //Range key
                'HASH'       => array( AmazonDynamoDB::TYPE_ARRAY_OF_STRINGS => array ('key', 'value')   ),
                )
            ));

    echo "# query search .... " . PHP_EOL;
    // query検索
//    $query_response = $dynamodb->query(array(
//            'TableName' => VCV_TABLE_NAME,
//                'HashKeyValue' => array( AmazonDynamoDB::TYPE_NUMBER => '100019' ),
//                'RangeKeyCondition' => array(
//                    // 範囲指定、LESSは有効か？
////                    'ComparisonOperator' => AmazonDynamoDB::CONDITION_LESS_THAN_OR_EQUAL,
////                    'AttributeValueList' => array (
////                        array(AmazonDynamoDB::TYPE_STRING => 'user_c')
////                    )
//                    // 範囲指定、BETWEENは有効か？
//                    'ComparisonOperator' => AmazonDynamoDB::CONDITION_BETWEEN,
//                    'AttributeValueList' => array (
//                        array(AmazonDynamoDB::TYPE_STRING => 'user_c'),
//                        array(AmazonDynamoDB::TYPE_STRING => 'user_d')
//                    )
//                    )
//                ));
//    foreach ($query_response->body->Items as $item) {
//        foreach ($item as $key => $value) {
//            print sprintf("%s : %s", $key, $value->{AmazonDynamoDB::TYPE_STRING}) . PHP_EOL;
//        }
//    }
    //scan 検索
    echo "#scan search .... " . PHP_EOL;
    $scan_response = $dynamodb->scan(array(
                'TableName' => VCV_TABLE_NAME,
//                'ScanFilter' => array( 
//                    'UID' => array(
//                        'ComparisonOperator' => AmazonDynamoDB::CONDITION_EQUAL,
//                        'AttributeValueList' => array(
//                            array( AmazonDynamoDB::TYPE_STRING => 'test2' )
//                            )
//                        ),
//                    )
//                //'AttributesToGet' => array('ID', 'UID')  // 取得制限
                ));

    foreach ($scan_response->body->Items as $item) {
        // check warning ?
        //var_dump($item->HOGE->{AmazonDynamoDB::TYPE_STRING});
        foreach ($item as $key => $value) {
            print sprintf("%s : %s", $key, $value->{AmazonDynamoDB::TYPE_STRING}) . PHP_EOL;
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
