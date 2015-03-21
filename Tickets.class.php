<?php
class Tickets
{
        private $from;
        private $to;
        private $go_time;
        private $gc=FALSE;
        private $dc=FALSE;
        private $zc=FALSE;
        private $tc=FALSE;
        private $kc=FALSE;
        private $oc=FALSE;
        private $query_student=FALSE;

        public $query_stat=0;        //success: 1 ,fail: 0

        public function __construct($from,$to,$go_time,$cc_array,$query_student)
                {
                        $this->from=$from;
                        $this->to=$to;
                        $this->go_time=$go_time;
                        $this->gc=$cc_array['gc'];
                        $this->dc=$cc_array['dc'];
                        $this->zc=$cc_array['zc'];
                        $this->tc=$cc_array['tc'];
                        $this->kc=$cc_array['kc'];
                        $this->oc=$cc_array['oc'];
                        $this->query_student=$query_student;
                }
        public function query()
                {
                        if($this->query_student)
                                {

                                }
                        else
                                {
                                        $from_c=$this->get_station_code($this->from);
                                        $to_c=$this->get_station_code($this->to);
                                       
                                        if($from_c!=FALSE&&$to_c!=FALSE)
                                        {
                                       
                                                $query_str="https://kyfw.12306.cn/otn/lcxxcx/query?purpose_codes=ADULT"."&queryDate=".$this->go_time."&from_station=".$from_c."&to_station=".$to_c;
                                                $result_str=$this->curl_get($query_str);
                                                $json_array=json_decode($result_str,TRUE);
                                                print_r($json_array);
                                        }
                                        else
                                        {
                                                echo  'invalid station name';
                                       
                                        }
                                        
                                        

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
                                return FALSE;
                        }
                        
                }
}
?>