<?php
require_once("Tickets.class.php");
//echo phpinfo();
//exit;
if(isset($_GET['from'])&&isset($_GET['to'])&&isset($_GET['gotime']))
{
    $from=$_GET['from'];
    $to=$_GET['to'];
    $go_time=$_GET['gotime'];
}
else
{
    exit;
}

$ts =new Tickets($from,$to,$go_time);
header("Content-type:text/html;charset=utf-8");

//返回类型: json xml
if(isset($_GET['type'])&&isset($_GET['people']))
{
    $ts->query($_GET['people'], $_GET['type']);
}
 else {
     exit;
    
}

//查询限制类型数组
// all:所有 gc:高铁 dc:动车 zc:直达 tc:特快 kc:普快 oc:其它
$type_array=array('all'=>TRUE,'gc'=>FALSE,'dc'=>FALSE,'zc'=>FALSE,'tc'=>FALSE,'kc'=>FALSE,'oc'=>FALSE);

if(isset($_GET['gc']))
{
        $type_array['all']=FALSE;
        $type_array['gc']=TRUE;
}
if(isset($_GET['dc']))
{
        $type_array['all']=FALSE;
        $type_array['dc']=TRUE;
}
if(isset($_GET['zc']))
{
        $type_array['all']=FALSE;
        $type_array['zc']=TRUE;
}
if(isset($_GET['tc']))
{
        $type_array['all']=FALSE;
        $type_array['tc']=TRUE;
}
if(isset($_GET['kc']))
{
        $type_array['all']=FALSE;
        $type_array['kc']=TRUE;
}
if(isset($_GET['oc']))
{
        $type_array['all']=FALSE;
        $type_array['oc']=TRUE;
}



