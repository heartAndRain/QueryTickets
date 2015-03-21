<?php
require_once("Tickets.class.php");

$from=$_GET['from'];
$to=$_GET['to'];
$a=array('gc'=>0,'dc'=>0,'zc'=>0,'tc'=>0,'kc'=>1,'oc'=>0);
$ts =new Tickets($from,$to,"2015-03-20",$a,FALSE);
header("Content-type:text/html;charset=utf-8");
echo $from;
//$ts->query();
?>