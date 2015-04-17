<?php
class Tickets
{
        private $from;
        private $to;
        private $go_time;

        public $query_stat=0;              //success: 1 ,fail: 0

        public function __construct($from,$to,$go_time)
                {
                        $this->from=$from;
                        $this->to=$to;
                        $this->go_time=$go_time;      
                }
        public function query($people,$return_type)
                {                                          
                        //get station code
                        $from_c="";
                        $to_c="";
                        if(!$this->get_station_code($this->from,$from_c)||
                        !$this->get_station_code($this->to,$to_c))
			{ 
				$this->show($return_type,$this->query_stat,"Invalid station name!",array());
				exit;		
                        }
                       
                        //check the type of people
                        if($people=="adu")
                        {
                                $query_str="https://kyfw.12306.cn/otn/lcxxcx/query?purpose_codes=ADULT"."&queryDate=".$this->go_time."&from_station=".$from_c."&to_station=".$to_c;
                        }
                        else if($people=="stu")
                        {
                                $query_str="https://kyfw.12306.cn/otn/lcxxcx/query?purpose_codes=0X00"."&queryDate=".$this->go_time."&from_station=".$from_c."&to_station=".$to_c;
                        }
                        else
                        {
                            $this->show($return_type,$this->query_stat, "The type of people is wrong!", array());
                            exit;
                        }
                         
                        $result_str=$this->curl_get($query_str);
             
                        //check the curl query is successful
                        $result_array=json_decode($result_str,TRUE); 
                        //check if return json or xml
                        if($return_type=="json"||$return_type=="xml")
                        {
                                $this->query_stat=1;
                                $this->show($return_type,$this->query_stat,"ok", $result_array['data']['datas']);
                        }
                        else
                        {
                            $this->show($return_type,$this->query_stat, "No such of return type", array());
                        }
                }
        private function show($type,$status,$message,$datas)
        {
            $tmp_arry=array("status"=>$status,"message"=>$message,"datas"=>$datas);
            if($type=="json")
            {
             echo json_encode($tmp_arry,JSON_UNESCAPED_UNICODE);
	    }
	    else
		{
		$xml="<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";
		$xml.="<root>";
	        $this->xml_encode($tmp_arry,$xml);
		$xml.="</root>";
		header("Content-type: xml,charset=utf-8");
		echo $xml;
		}
        }

        private function curl_get($query_str)
                {
                        $ch=curl_init();
                        curl_setopt($ch,CURLOPT_URL,$query_str);
                        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                        $str=curl_exec($ch);
                        curl_close($ch);
                        return $str;
                }
        private function get_station_code($station,&$station_c)
                {
                        $json_str=file_get_contents("station_h.json");
                        $json_array=json_decode($json_str,TRUE);
                        if(array_key_exists($station, $json_array))
                        {
                                $station_c=$json_array[$station];
				return TRUE;
                        }
                        else
                        {
                            return FALSE;
                        }        
                }
       
       private  function xml_encode($array,&$xml)
             { 
	        foreach ($array as $key => $value) {
		  $key=is_numeric($key)?"case".$key:$key;
		  $xml.="<".$key.">";
		  $value=is_array($value)?$this->xml_encode($value,$xml):$value;
		  $xml.=$value."</".$key.">";
	       }
	        return;
             }  
}

