<?php
$redis=new Redis();
$redis->connect('127.0.0.1',6379);
if(!$redis){
	echo "connect redis "; exit;
}
$redis->set('testkey1',1111);
echo "the new test key is :".$redis->get('testkey1');