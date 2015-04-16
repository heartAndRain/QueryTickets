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
                        $this->get_station_code($this->from,$from_c);
                        $this->get_station_code($this->to,$to_c);

                       
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
                            $this->show($this->query_stat, "The type of people is wrong!", array());
                            exit;
                        }
                         
                        $result_str=$this->curl_get($query_str);
             
                        //check the curl query is successful
                        $result_array=json_decode($result_str,TRUE); 
                        //check if return json or xml
                        if($return_type=="json")
                        {
                                $this->query_stat=1;
                                $this->show($this->query_stat,"ok", $result_array[data][datas]);
                        }
                        else if($return_type=="xml")
                        {
                               
                              
                        } 
                        else
                        {
                            $this->show($this->query_stat, "No such of return type", array());
                        }
                }
        private function show($status,$message,$datas)
        {
            $tmp_arry=array("status"=>$status,"message"=>$message,"datas"=>$datas);
            echo json_encode($tmp_arry);
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
                        }
                        else
                        {
                            $this->show($this->query_stat, "Invalid station name!", array());                           
                            exit;
                        }        
                }
       
        private function encode_xml($info_array)
        {
               
        }
}
?>
