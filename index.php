<?php
require_once("Tickets.class.php");

@$from=$_GET['from'];
@$to=$_GET['to'];
@$go_time=$_GET['gotime'];

@$gc=$_GET['gc'];
@$dc=$_GET['dc'];
@$zc=$_GET['zc'];
@$tc=$_GET['tc'];
@$kc=$_GET['kc'];
@$oc=$_GET['oc'];

@$query_stu=$_GET['stu'];

@$return_xml=$_GET['xml'];

$type_array=array('all'=>TRUE,'gc'=>FALSE,'dc'=>FALSE,'zc'=>FALSE,'tc'=>FALSE,'kc'=>FALSE,'oc'=>FALSE);

if(isset($gc))
{
        $type_array['all']=FALSE;
        $type_array['gc']=TRUE;
}
if(isset($dc))
{
        $type_array['all']=FALSE;
        $type_array['dc']=TRUE;
}
if(isset($zc))
{
        $type_array['all']=FALSE;
        $type_array['zc']=TRUE;
}
if(isset($tc))
{
        $type_array['all']=FALSE;
        $type_array['tc']=TRUE;
}
if(isset($kc))
{
        $type_array['all']=FALSE;
        $type_array['kc']=TRUE;
}
if(isset($oc))
{
        $type_array['all']=FALSE;
        $type_array['oc']=TRUE;
}

$ts =new Tickets($from,$to,$go_time,$type_array);

if (isset($query_stu)&&$query_stu==1) 
{
        $ts->set_query_stu();
}
if(isset($return_xml)&&$return_xml==1)
{
        $ts->set_return_xml();
}

header("Content-type:text/html;charset=utf-8");

$ts->query();
?>