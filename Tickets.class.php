<?php
class Tickets
{
        private $from;
        private $to;
        private $go_time;
        
        private $gc;
        private $dc;
        private $zc;
        private $tc;
        private $kc;
        
        private $oc;
        private $query_stu=FALSE;          //default: query nomal
        private $return_json=TRUE;         //default: return json

        public $query_stat=0;              //success: 1 ,fail: 0

        public function __construct($from,$to,$go_time,$type_array)
                {
                        $this->from=$from;
                        $this->to=$to;
                        $this->go_time=$go_time;
                        $this->gc=$type_array['gc'];
                        $this->dc=$type_array['dc'];
                        $this->zc=$type_array['zc'];
                        $this->tc=$type_array['tc'];
                        $this->kc=$type_array['kc'];
                        $this->oc=$type_array['oc'];
                }
        public function set_query_stu()
                {
                        $this->query_stu=TRUE;
                }
        public function set_return_xml()
                {
                        $this->return_json=FALSE;
                }
        public function query()
                {  
                        if(!isset($this->from)||!isset($this->to)||!isset($this->go_time))
                        {
                                echo 'lack of parameter!';
                                exit;
                        }                   
                        //get station code
                        $from_c=$this->get_station_code($this->from);
                        $to_c=$this->get_station_code($this->to);

                        //check if query student
                        if(!$this->query_stu)
                        {
                                $query_str="https://kyfw.12306.cn/otn/lcxxcx/query?purpose_codes=ADULT"."&queryDate=".$this->go_time."&from_station=".$from_c."&to_station=".$to_c;
                        }
                        else
                        {
                                $query_str="https://kyfw.12306.cn/otn/lcxxcx/query?purpose_codes=0X00"."&queryDate=".$this->go_time."&from_station=".$from_c."&to_station=".$to_c;
                        }

                        $result_str=$this->curl_get($query_str);

                        //check the curl query is successful
                        if($result_str==-1)
                        {
                                echo 'curl failed';
                                exit;
                        }
                        else
                        {
                                $result_array=json_decode($result_str,TRUE); 

                        }

                        //check if return json or xml
                        if($this->return_json)
                        {
                                $this->encode_json($result_array);
                        }
                        else
                        {
                               
                                echo 'xml';
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
        private function get_station_code($station)
                {
                        $json_str=file_get_contents("station_h.json");
                        $json_array=json_decode($json_str,TRUE);
                        if(array_key_exists($station, $json_array))
                        {
                                $station_c=$json_array[$station];
                                return  $station_c;
                        }
                        else
                        {
                                echo 'invalid station name!';
                                exit;
                        }
                        
                }
        private function encode_json($info_array)
        {
                if($info_array['httpstatus']==200)
                {
                        echo json_encode($info_array['data']['datas']);
                }
                else
                {
                        echo '12306 server error';
                        exit;
                }
                
                //return $json_str;
        }
        private function encode_xml($info_array)
        {
                $xml= new A2Xml();
                echo $xml->toXml($info_array);
        }
}
?>