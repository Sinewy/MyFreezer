<?php
/**
 * Created by IntelliJ IDEA.
 * User: jurezilavec
 * Date: 7/3/14
 * Time: 6:53 PM
 * To change this template use File | Settings | File Templates.
 */

$host = "smtp.gmail.com";
$port = "587";
$checkconn = fsockopen($host, $port, $errno, $errstr, 5);
if(!$checkconn){
	echo "($errno) $errstr";
} else {
	echo 'ok';
}