<?php
/**
* 电台
*/
$api->any('radio/info', 'App\Http\Controllers\Api\V2\Hd\Radio\RadioController@getRadio');
$api->any('radio/column/report', 'App\Http\Controllers\Api\V2\Hd\Radio\RadioController@getColumnReport');
$api->any('radio/report/detail', 'App\Http\Controllers\Api\V2\Hd\Radio\RadioController@getReport');
$api->any('radio/report/collect', 'App\Http\Controllers\Api\V2\Hd\Radio\RadioController@collect');
$api->any('radio/report/browse', 'App\Http\Controllers\Api\V2\Hd\Radio\RadioController@browse');
$api->any('radio/my/collection', 'App\Http\Controllers\Api\V2\Hd\Radio\RadioController@getMyCollect');
$api->any('radio/my/browse', 'App\Http\Controllers\Api\V2\Hd\Radio\RadioController@getMyBrowse');
$api->any('radio/user/is/collect', 'App\Http\Controllers\Api\V2\Hd\Radio\RadioController@CollectStatus');
$api->any('radio/my/report/see', 'App\Http\Controllers\Api\V2\Hd\Radio\RadioController@getMyReport');
$api->any('radio/find/report', 'App\Http\Controllers\Api\V2\Hd\Radio\RadioController@getFindReport');
$api->any('radio/story/get', 'App\Http\Controllers\Api\V2\Hd\Radio\RadioController@getStory');

?>