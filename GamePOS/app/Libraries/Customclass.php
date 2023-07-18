<?php 

namespace App\Libraries;

class Customclass {
    //removespecialsymbol
    public function rsstr($str){
        if($str != null){
            $str = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $str);
        }
        return $str;
    }
  
    public function rtndata($code,$msg,$data){
        $rtnData = array();
        $rtnData['code'] = $code;
        $rtnData['message'] = $msg;
        $rtnData['datas'] = $data;
        return $rtnData;
    }

    public function checkAuth(){
		
        $gToken = null;
        $headers = apache_request_headers();
        foreach ($headers as $header => $value) {
          if($header == 'gtoken'){
            $gToken = $value;
          }
        }

        // 디비에서 해당 처리가 유효 한지 체크 후 다음처리함.
        $db = \Config\Database::connect("default");
        $query = $db->query('SELECT * FROM ADMIN WHERE ADMIN_ABLE_YN="N" AND WEB_ID_YN = "N" AND GTOKEN_TIME > NOW() AND GTOKEN="'.$this->rsstr($gToken).'"');
        $results = $query->getResultArray();
        if($gToken == null || count($results) == 0){
          return false;
        }else{
          return true;
        }
    }
}