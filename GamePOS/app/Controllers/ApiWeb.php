<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Libraries\Customclass;

class ApiWeb extends ResourceController
{
    use ResponseTrait;

    // all users
    
    public function index(){

        $cc = new Customclass();
        // 세션체크
		$session = session();
        if($session-> get('user') == NULL){
        	return $this->respond($cc->rtndata("999","토큰 에러",null));
		}
		
		$gt = array(); 
		return $this->respond($cc->rtndata("200","",$gt));
	}
	
	// 정산 리스트 
    public function baseCal(){

        $cc = new Customclass();
        // 세션체크
		$session = session();
        if($session-> get('user') == NULL){
        	return $this->respond($cc->rtndata("999","토큰 에러",null));
		}
		
		// 파라메터 정의
      $admin_id =$this->request->getVar('ADMIN_ID');
	  $st_date =$this->request->getVar('ST_DATE');
	  $ed_date =$this->request->getVar('ED_DATE');
	  $machine_no =$this->request->getVar('MACHINE_NO');
	  $user_no = $this->request->getVar('USER_NO');
	  $search_key =$this->request->getVar('SEARCH_KEY');
      $page =intval($this->request->getVar('PAGE'));
      $row_cnt =intval($this->request->getVar('ROW_CNT'));
	  
	  $sessionData = $session-> get('user'); 
      $companyNo = $sessionData['COMPANY_NO'];
	  
	  // 페이지가 없을때 페이지 번호는 0  검색 카운트는 20 으로 고정함
	    if($page == null){
	      $page = 0;
	    }
	    if($row_cnt == null){
	      $row_cnt = 20;
	    }
	
      
      $db = \Config\Database::connect("default");
	  
	  $where  = '';
	  
  	  if(!is_null($companyNo) && strlen($companyNo) > 0){  // 키워드가 있을떄만 조회함
        $where = $where." AND A.`COMPANY_NO` = '".$companyNo."'";
      }
	  if(!is_null($admin_id) && strlen($admin_id) > 0){  // 키워드가 있을떄만 조회함
        $where = $where." AND (A.`ADMIN_ID`= '".$admin_id."' OR A.`ADMIN_ID` LIKE '%".$admin_id."%')";
      }
	  if(!is_null($user_no) && strlen($user_no) > 0){  // 키워드가 있을떄만 조회함
       $where = $where." AND A.`USER_NO` = '".$user_no."'";
     }
	  if(!is_null($search_key) && strlen($search_key) > 0 ){  // 키워드가 있을떄만 조회함
	      $where = $where." AND (B.`SEARCH_NO`= '".$search_key."' OR B.`USER_NM` LIKE '%".$search_key."%')";
      }
	  if(!is_null($machine_no) && strlen($machine_no) > 0){  // 키워드가 있을떄만 조회함
        $where = $where." AND A.`MACHINE_NO`= '".$machine_no."'";
      }
     if(!is_null($st_date) && strlen($st_date) > 0){  // 키워드가 있을떄만 조회함
      $where = $where." AND A.`CREATE_TIME` BETWEEN '".$st_date."'  AND  '".$ed_date."'";
     }
	  


      // 검색쿼리 생성
      $sql = "SELECT A.`CREATE_TIME`,A.`POINT_NO`, A.`USER_NO`, B.`USER_NM`, B.`PHONE_NO`, A.`MACHINE_NO`, A.`ADMIN_ID`, A.`POINT_TP`, A.`POINT`, A.`DELETE_YN`, A.`DELETE_NOTE`, A.`DELETE_TIME`, A.`LAST_POINT` FROM `POINT` A
				LEFT JOIN USER B ON A.USER_NO = B.USER_NO  WHERE 1=1 ";
      $sql = $sql.$where;
      $sql = $sql." ORDER BY A.`POINT_NO` DESC ";
      $sql = $sql." LIMIT ".$page*$row_cnt.",".$row_cnt;
      
      $query = $db->query($sql);
      $results = $query->getResultArray();
	  
	  // 결과값      
      $gt = array();
	  $gt['POINT_LIST'] = $results;   
	  
	  // 페이지가 1페이지 일대  총합
  	  if($page == 0 && count($results) != 0){
	  	    $sql = "SELECT 
	  	    (SELECT SUM(P.POINT) FROM POINT P WHERE P.DELETE_YN = 'N' ) AS ALL_SUM
		    ,SUM(CASE WHEN POINT > 0 THEN A.POINT ELSE 0 END) AS PLUS_POINT 
		    ,SUM(CASE WHEN POINT > 0 THEN 1 ELSE 0 END) AS PLUS_POINT_CNT
		    ,SUM(CASE WHEN POINT < 0 THEN A.POINT ELSE 0 END) AS MINUS_POINT
		    ,SUM(CASE WHEN POINT < 0 THEN 1 ELSE 0 END) AS MINUS_POINT_CNT 
		    ,SUM(A.POINT) AS TOTAL 
		    FROM `POINT` A INNER JOIN USER B ON A.USER_NO = B.USER_NO WHERE 1=1 AND A.DELETE_YN = 'N' AND A.COMPANY_NO = ?  ";
			$sql = $sql.$where;
			$query = $db->query($sql,[$companyNo]);
		    $gt['POINT_SUM']  = $query->getResultArray()[0];
	  }
	  
	  // 
	  if(count($results) != 0){
	  	$page = $page+1;
	  }
	  // 전달받은 파라메터 다시 전달
	  $gt['PARAMS']['ADMIN_ID'] =   $admin_id;
	  $gt['PARAMS']['ST_DATE'] =   $st_date;
	  $gt['PARAMS']['ED_DATE'] =   $ed_date;
	  $gt['PARAMS']['MACHINE_NO'] =   $machine_no;
	  $gt['PARAMS']['USER_NO'] =   $user_no;
	  $gt['PARAMS']['SEARCH_KEY'] =   $search_key;
	  $gt['PARAMS']['PAGE'] =   $page;
	  $gt['PARAMS']['ROW_CNT'] =   $row_cnt;
	  
        return $this->respond($cc->rtndata("200","",$gt));
    }
 
     public function machineCal(){
     	
     	$cc = new Customclass();
        // 세션체크
		$session = session();
        if($session-> get('user') == NULL){
        	return $this->respond($cc->rtndata("999","토큰 에러",null));
		}
		$sessionData = $session-> get('user'); 
		// 파라메터 정의
      
      $companyNo = $sessionData['COMPANY_NO'];
	  $st_date =$this->request->getVar('ST_DATE');
	  $ed_date =$this->request->getVar('ED_DATE');
      
	  
	  // 페이지가 없을때 페이지 번호는 0  검색 카운트는 20 으로 고정함
	    if($st_date == null || strlen($st_date) == 0){
	      $st_date = date("Y-m-d");
	    }
	    if($ed_date == null || strlen($ed_date) == 0){
	      $ed_date = date("Y-m-d",strtotime("+1 days"));
	    }
	
      
      $db = \Config\Database::connect("default");
	  
	  $where  = '';
	  
      // 검색쿼리 생성   기준시간을 봐야하기때문에 해당 부분 만큼 추가한 날짜를 가져옴
      $sql = "SELECT SUM(POINT),MACHINE_NO
			      ,SUM(CASE WHEN POINT > 0 THEN A.POINT ELSE 0 END) AS PLUS_POINT 
			      ,SUM(CASE WHEN POINT > 0 THEN 1 ELSE 0 END) AS PLUS_POINT_CNT
			      ,SUM(CASE WHEN POINT < 0 THEN A.POINT ELSE 0 END) AS MINUS_POINT
			      ,SUM(CASE WHEN POINT < 0 THEN 1 ELSE 0 END) AS MINUS_POINT_CNT 
			    FROM (
					SELECT POINT,MACHINE_NO 
					FROM POINT WHERE COMPANY_NO = ? AND CREATE_TIME BETWEEN DATE_ADD(?, INTERVAL (SELECT CAL_TIME FROM COMPANY WHERE COMPANY_NO = ? ) HOUR) AND DATE_ADD(?, INTERVAL (SELECT CAL_TIME FROM COMPANY WHERE COMPANY_NO = ? ) HOUR)
				)A GROUP BY MACHINE_NO ORDER BY MACHINE_NO";
      $sql = $sql.$where;
      
      $query = $db->query($sql,[$companyNo,$st_date,$companyNo,$ed_date,$companyNo]);
      $results = $query->getResultArray();
	  
	  // 결과값      
      $gt = array();
	  $gt['MACHINE_LIST'] = $results;   
	  
       return $this->respond($cc->rtndata("200","",$gt));
     }

	public function dayCal(){
     	
     	$cc = new Customclass();
        // 세션체크
		$session = session();
        if($session-> get('user') == NULL){
        	return $this->respond($cc->rtndata("999","토큰 에러",null));
		}
		$sessionData = $session-> get('user'); 
		// 파라메터 정의
      
      $companyNo = $sessionData['COMPANY_NO'];
      
      $db = \Config\Database::connect("default");
	  
	  $where  = '';
	  
      // 검색쿼리 생성   기준시간을 봐야하기때문에 해당 부분 만큼 추가한 날짜를 가져옴
      $sql = "SELECT SUM(POINT) AS TOTAL_POINT
						,SUM(CASE WHEN POINT > 0 THEN A.POINT ELSE 0 END) AS PLUS_POINT 
						,SUM(CASE WHEN POINT < 0 THEN A.POINT ELSE 0 END) AS MINUS_POINT
						,SUM(CASE POINT_TP WHEN '1' THEN A.POINT ELSE 0 END) AS SERVICE_POINT 
						, CREATE_TIME 
					FROM (
					SELECT POINT,POINT_TP,MACHINE_NO, DATE_FORMAT(DATE_SUB(CREATE_TIME, INTERVAL (SELECT CAL_TIME FROM COMPANY WHERE COMPANY_NO = ? ) HOUR), '%Y-%m-%d') AS CREATE_TIME 
					FROM POINT WHERE COMPANY_NO = ? AND CREATE_TIME BETWEEN DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 90 DAY), '%Y-%m-%d') AND NOW()
				)A GROUP BY CREATE_TIME";
      $sql = $sql.$where;
      
      $query = $db->query($sql,[$companyNo, $companyNo]);
      $results = $query->getResultArray();
	  
	  // 결과값      
      $gt = array();
	  $gt['DAY_LIST'] = $results;   
	  
	  
	  $sql = "SELECT 
	  	    (SELECT SUM(P.POINT) FROM POINT P WHERE P.DELETE_YN = 'N' ) AS ALL_SUM
		    ,SUM(CASE WHEN POINT > 0 THEN A.POINT ELSE 0 END) AS PLUS_POINT 
		    ,SUM(CASE WHEN POINT > 0 THEN 1 ELSE 0 END) AS PLUS_POINT_CNT
		    ,SUM(CASE WHEN POINT < 0 THEN A.POINT ELSE 0 END) AS MINUS_POINT
		    ,SUM(CASE WHEN POINT < 0 THEN 1 ELSE 0 END) AS MINUS_POINT_CNT 
		    ,SUM(A.POINT) AS TOTAL 
		    FROM `POINT` A INNER JOIN USER B ON A.USER_NO = B.USER_NO WHERE 1=1 AND A.DELETE_YN = 'N' AND A.COMPANY_NO = ? ";
		$sql = $sql.$where;
		$query = $db->query($sql,[$companyNo]);
		$gt['POINT_SUM']  = $query->getResultArray()[0];
	  
       return $this->respond($cc->rtndata("200","",$gt));
     }

	// 정산 리스트 
    public function updateSetting(){
    	$cc = new Customclass();
        // 세션체크
		$session = session();
        if($session-> get('user') == NULL){
        	return $this->respond($cc->rtndata("999","토큰 에러",null));
		}
		$sessionData = $session-> get('user'); 
		// 파라메터 정의
      
       $companyNo = $sessionData['COMPANY_NO'];
	   $calTime =$this->request->getVar('CAL_TIME');
	   $machine_cnt =$this->request->getVar('MACHINE_CNT');
	   
	   $db = \Config\Database::connect("default");
	  
      // 검색쿼리 생성   기준시간을 봐야하기때문에 해당 부분 만큼 추가한 날짜를 가져옴
      $sql = "UPDATE COMPANY SET MACHINE_CNT = ? ,  CAL_TIME = ? WHERE COMPANY_NO=? ";
      $query = $db->query($sql,[$machine_cnt, $calTime,$companyNo]);
      $results = $query->getResultArray();
	  
	  $sessionData['CAL_TIME'] =$calTime;
	  $sessionData['MACHINE_CNT'] =  $machine_cnt;
	  $session-> set('user',$sessionData); 
	  
	  return $this->respond($cc->rtndata("200","저장되었습니다.",array()));
	}
	
	public function getAdmin(){
     	
     	$cc = new Customclass();
        // 세션체크
		$session = session();
        if($session-> get('user') == NULL){
        	return $this->respond($cc->rtndata("999","토큰 에러",null));
		}
		$sessionData = $session-> get('user'); 
		// 파라메터 정의
      
      $companyNo = $sessionData['COMPANY_NO'];
      
      $db = \Config\Database::connect("default");
	  
	  $where  = '';
	  
      // 검색쿼리 생성   기준시간을 봐야하기때문에 해당 부분 만큼 추가한 날짜를 가져옴
      $sql = "SELECT `ADMIN_NO`,`ADMIN_ID`,`USE_ABLE_TIME`,`ADMIN_ABLE_YN` FROM `ADMIN` WHERE `WEB_ID_YN` = 'N' AND COMPANY_NO = ?";
      $sql = $sql.$where;
      
      $query = $db->query($sql,[$companyNo]);
      $results = $query->getResultArray();
	  
	  // 결과값      
      $gt = array();
	  $gt['ADMIN_LIST'] = $results;   
	  
       return $this->respond($cc->rtndata("200","",$gt));
     }
	
	// 정산 리스트 
    public function stopAppAdmin(){
    	$cc = new Customclass();
        // 세션체크
		$session = session();
        if($session-> get('user') == NULL){
        	return $this->respond($cc->rtndata("999","토큰 에러",null));
		}
		
		$sessionData = $session-> get('user');
		 
		// 파라메터 정의
       $companyNo = $sessionData['COMPANY_NO'];
	   $adminNo =$this->request->getVar('ADMIN_NO');
	   
	   
	   $db = \Config\Database::connect("default");
	  
      // 검색쿼리 생성   기준시간을 봐야하기때문에 해당 부분 만큼 추가한 날짜를 가져옴
      $sql = "UPDATE ADMIN SET ADMIN_ABLE_YN ='Y' ,  	MACHINE_ID = NULL WHERE COMPANY_NO = ? AND ADMIN_NO=? ";
      $query = $db->query($sql,[$companyNo, $adminNo]);
      $results = $query->getResultArray();
	  
	  return $this->respond($cc->rtndata("200","저장되었습니다.",array()));
	}
	
	// 회원리스트
    public function searchUser(){

        $cc = new Customclass();
        // 세션체크
		$session = session();
        if($session-> get('user') == NULL){
        	return $this->respond($cc->rtndata("999","토큰 에러",null));
		}
		
		// 파라메터 정의
	  $user_no = $this->request->getVar('USER_NO');
	  $search_key =$this->request->getVar('SEARCH_KEY');
      $page =intval($this->request->getVar('PAGE'));
      $row_cnt =intval($this->request->getVar('ROW_CNT'));
	  $sort =$this->request->getVar('SORT');
	  
	  $sessionData = $session-> get('user'); 
      $companyNo = $sessionData['COMPANY_NO'];
	  
	  // 페이지가 없을때 페이지 번호는 0  검색 카운트는 20 으로 고정함
	    if($page == null){
	      $page = 0;
	    }
	    if($row_cnt == null){
	      $row_cnt = 20;
	    }
	
      
      $db = \Config\Database::connect("default");
	  
	  $where  = '';

	  if(!is_null($user_no) && strlen($user_no) > 0 ){  // 키워드가 있을떄만 조회함
       $where = $where." AND A.`USER_NO` = '".$user_no."'";
     }
	  if(!is_null($search_key) && strlen($search_key) > 0 ){  // 키워드가 있을떄만 조회함
	      $where = $where." AND (A.`SEARCH_NO`= '".$search_key."' OR A.`USER_NM` LIKE '%".$search_key."%')";
      }

      // 검색쿼리 생성
      $sql = "SELECT * FROM (
					SELECT *,'' AS DEL_FLG FROM USER WHERE COMPANY_NO = ?
					UNION 
					SELECT * FROM USER_BACK_UP WHERE COMPANY_NO = ? AND LAST_FLG = 'Y'
				)A WHERE 1=1";
      $sql = $sql.$where;
      $sql = $sql." ORDER BY ".$sort." DESC ";
      $sql = $sql." LIMIT ".$page*$row_cnt.",".$row_cnt;
      
      $query = $db->query($sql,[$companyNo,$companyNo]);
      $results = $query->getResultArray();
	  
	  // 결과값      
      $gt = array();
	  $gt['USER_LIST'] = $results;   
	  
	  // 페이지가 1페이지 일대  총합
  	  if($page == 0 && count($results) != 0){
	  	    $sql = "SELECT 
	  	    (SELECT COUNT(*) FROM USER WHERE COMPANY_NO = ?) AS USER_ALL_CNT
		    ,SUM(CASE WHEN POINT > 0 THEN A.POINT ELSE 0 END) AS PLUS_POINT 
		    ,SUM(CASE WHEN POINT > 0 THEN 1 ELSE 0 END) AS PLUS_POINT_CNT
		    ,SUM(CASE WHEN POINT < 0 THEN A.POINT ELSE 0 END) AS MINUS_POINT
		    ,SUM(CASE WHEN POINT < 0 THEN 1 ELSE 0 END) AS MINUS_POINT_CNT 
		    ,SUM(A.POINT) AS TOTAL 
		    FROM `POINT` A INNER JOIN USER B ON A.USER_NO = B.USER_NO WHERE 1=1 AND A.DELETE_YN = 'N' AND A.COMPANY_NO = ?  ";
			#$sql = $sql.$where;
			$query = $db->query($sql,[$companyNo,$companyNo]);
		    $gt['POINT_SUM']  = $query->getResultArray()[0];
	  }
	  
	  // 
	  if(count($results) != 0){
	  	$page = $page+1;
	  }
	  
	  // 전달받은 파라메터 다시 전달
	  $gt['PARAMS']['USER_NO'] =   $user_no;
	  $gt['PARAMS']['SEARCH_KEY'] =   $search_key;
	  $gt['PARAMS']['PAGE'] =   $page;
	  $gt['PARAMS']['ROW_CNT'] =   $row_cnt;
	  $gt['PARAMS']['SORT'] =   $sort;
	  
        return $this->respond($cc->rtndata("200","",$gt));
    }
    
    // 회원정지 수정
    public function updateUser(){
    	$cc = new Customclass();
        // 세션체크
		$session = session();
        if($session-> get('user') == NULL){
        	return $this->respond($cc->rtndata("999","토큰 에러",null));
		}

	  $user_no = $this->request->getVar('USER_NO');
	  $del_flg =$this->request->getVar('DEL_FLG');
	  
	  $sessionData = $session-> get('user'); 
      $companyNo = $sessionData['COMPANY_NO'];
	  
	  
	  $db = \Config\Database::connect("default");

		$sql = "SELECT * FROM USER WHERE USER_NO = ?";
	    $query = $db->query($sql, [$user_no]);
        $rows = $query->getResultArray();
        
	  if($del_flg == 'Y'){
	  	if(count($rows) != 0){
	  	 	return $this->respond($cc->rtndata("500","작업중 에러가 발생하였습니다.",null));
	  	 }			
		$sql = "INSERT INTO USER  SELECT `USER_NO`, `COMPANY_NO`, `USER_NM`, `PASSWORD`, `PHONE_NO`, `SEARCH_NO`, `LAST_POINT`, `CREATE_ID`, `CREATE_TIME`, `UPDATE_ID`, `UPDATE_TIME` FROM `USER_BACK_UP` WHERE USER_NO = ? AND  LAST_FLG = 'Y'";
	    $db->query($sql,[$user_no]);
		$sql = "UPDATE USER_BACK_UP SET LAST_FLG = 'N' WHERE USER_NO = ?";
		$db->query($sql,[$user_no]);
	  }else{
	  	 if(count($rows) != 1){
	  	 	return $this->respond($cc->rtndata("500","작업중 에러가 발생하였습니다.",null));
	  	 }
	 	  $sql = "UPDATE USER_BACK_UP SET LAST_FLG = 'N' WHERE USER_NO = ?";
		  $db->query($sql,[$user_no]);
		  $sql = "INSERT INTO USER_BACK_UP (`USER_NO`, `COMPANY_NO`, `USER_NM`, `PASSWORD`, `PHONE_NO`, `SEARCH_NO`, `LAST_POINT`, `CREATE_ID`, `CREATE_TIME`, `UPDATE_ID`, `UPDATE_TIME`) SELECT * FROM USER WHERE USER_NO = ?";
		  $db->query($sql,[$user_no]);
		  $sql = "DELETE FROM USER WHERE USER_NO = ?";
		  $db->query($sql,[$user_no]);
	  }
		
	   // 결과값      
       $gt = array();
	   $gt['USER_NO'] = $user_no;
	   $gt['DEL_FLG'] = $del_flg;
	   
		return $this->respond($cc->rtndata("200","",$gt));
    } 
    
    public function selelctCompany(){
    	$cc = new Customclass();
        // 세션체크
		$session = session();
        if($session-> get('user') == NULL){
        	return $this->respond($cc->rtndata("999","토큰 에러",null));
		}
	  
	  $companyNo = $this->request->getVar('COMPANY_NO');
	  $sessionData = $session-> get('user'); 
	  $amdinId = $sessionData['ADMIN_ID'];
	  
	  if($amdinId != 'ADMIN'){
	  	return $this->respond($cc->rtndata("000","권한없음",null));
	  }
	  
	  $db = \Config\Database::connect("default");

		$sql = "SELECT * FROM `COMPANY` WHERE 1";

	    $query = $db->query($sql);
        $rows = $query->getResultArray();
	  
	   // 결과값      
       $gt = array();
	   $gt['COMPANY_LIST'] = $rows;
	   
		return $this->respond($cc->rtndata("200","",$gt));
    }
	
	public function insertCompany(){
    	$cc = new Customclass();
        // 세션체크
		$session = session();
        if($session-> get('user') == NULL){
        	return $this->respond($cc->rtndata("999","토큰 에러",null));
		}
	  
	  $company_no = $this->request->getVar('COMPANY_NO');
	  if($company_no != NULL && strlen($company_no) == 0){
	  	$company_no = NULL;
	  }
	  $company_nm = $this->request->getVar('COMPANY_NM');
	  $company_biz_no = $this->request->getVar('COMPANY_BIZ_NO');
	  $company_phone_no = $this->request->getVar('COMPANY_PHONE_NO');
	  $machine_cnt = $this->request->getVar('MACHINE_CNT');
	  $cal_time = $this->request->getVar('CAL_TIME');
	  
	  $sessionData = $session-> get('user'); 
	  $adminId = $sessionData['ADMIN_ID'];
	  $admin_id = $sessionData['ADMIN_ID'];
	  
	  if($adminId != 'ADMIN'){
	  	return $this->respond($cc->rtndata("000","권한없음",null));
	  }
	  
	  $db = \Config\Database::connect("default");

		$sql = "INSERT INTO `COMPANY`(`COMPANY_NO`, `COMPANY_NM`, `COMPANY_BIZ_NO`, `COMPANY_PHONE_NO`, `MACHINE_CNT`, `CAL_TIME`, `CREATE_ID`, `CREATE_TIME`, `UPDATE_ID`, `UPDATE_TIME`) 
					VALUES (?,?,?,?,?,?,?,NOW(),?,NOW()) 
					ON DUPLICATE KEY UPDATE 
						UPDATE_TIME = NOW()
					    ,COMPANY_NM = ?
					    ,COMPANY_BIZ_NO = ?
					    ,COMPANY_PHONE_NO = ?
					    ,MACHINE_CNT = ?
					    ,CAL_TIME = ?
					    ,UPDATE_ID = ?"
						;
	    $query = $db->query($sql,[$company_no,$company_nm,$company_biz_no,$company_phone_no,$machine_cnt,$cal_time,$admin_id,$admin_id,$company_nm,$company_biz_no,$company_phone_no,$machine_cnt,$cal_time,$admin_id]);
        $rows = $query->getResultArray();
	  
	   // 결과값      
       $gt = array();
	   
		return $this->respond($cc->rtndata("200","",$gt));
    }
    
    // 관리자 리스트 (admin 만 허용함)
     public function selectAdmin(){
    	$cc = new Customclass();
        // 세션체크
		$session = session();
        if($session-> get('user') == NULL){
        	return $this->respond($cc->rtndata("999","토큰 에러",null));
		}
	  $admin_id = $this->request->getVar('ADMIN_ID');
	  $companyNo = $this->request->getVar('COMPANY_NO');
	  $sessionData = $session-> get('user'); 
	  
	  $amdinId = $sessionData['ADMIN_ID'];
	  
	  if($amdinId != 'ADMIN'){
	  	return $this->respond($cc->rtndata("000","권한없음",null));
	  }
	  
	  $db = \Config\Database::connect("default");

		$sql = "SELECT A.*,B.COMPANY_NM,B.COMPANY_BIZ_NO,B.COMPANY_PHONE_NO,B.MACHINE_CNT,B.MACHINE_CNT FROM ADMIN A, COMPANY B WHERE A.COMPANY_NO = B.COMPANY_NO AND A.ADMIN_NO != 1";
		
		$where = '';
		if(!is_null($companyNo) && strlen($companyNo) > 0 ){  // 키워드가 있을떄만 조회함
	       $where = $where." AND A.COMPANY_NO = '".$companyNo."' ";
	    }
		if(!is_null($admin_id) && strlen($admin_id) > 0 ){  // 키워드가 있을떄만 조회함
		   $where = $where." AND  A.`ADMIN_ID` LIKE '%".$admin_id."%' ";
	    }
		$sql = $sql.$where;
		
	    $query = $db->query($sql);
        $rows = $query->getResultArray();
	  
	   // 결과값      
       $gt = array();
	   $gt['ADMIN_LIST'] = $rows;
	   
		return $this->respond($cc->rtndata("200","",$gt));
    } 

	public function insertAdmin(){
    	$cc = new Customclass();
        // 세션체크
		$session = session();
        if($session-> get('user') == NULL){
        	return $this->respond($cc->rtndata("999","토큰 에러",null));
		}
	  $admin_no = $this->request->getVar('ADMIN_NO');
  	  if($admin_no != NULL && strlen($admin_no) == 0){
	  	$admin_no = NULL;
	  }
	  $company_no = $this->request->getVar('COMPANY_NO');
	  $admin_id = $this->request->getVar('ADMIN_ID');
	  $web_id_yn = $this->request->getVar('WEB_ID_YN');
	  $use_able_time = $this->request->getVar('USE_ABLE_TIME');
	  $admin_able_yn = $this->request->getVar('ADMIN_ABLE_YN');
	  
	  $admin_password = $this->request->getVar('ADMIN_PASSWORD');
	  
	  $sessionData = $session-> get('user'); 
	  $adminId = $sessionData['ADMIN_ID'];
	  if($adminId != 'ADMIN'){
	  	return $this->respond($cc->rtndata("000","권한없음",null));
	  }
	  
	  $db = \Config\Database::connect("default");

		$sql = "INSERT INTO ADMIN(ADMIN_NO, COMPANY_NO, ADMIN_ID,  WEB_ID_YN, ADMIN_ABLE_YN, USE_ABLE_TIME, CREATE_ID, UPDATE_ID, CREATE_TIME, UPDATE_TIME) 
					VALUES (?,?,?,?,?,?,?,?,NOW(),NOW())
					ON DUPLICATE KEY UPDATE 
					UPDATE_TIME = NOW()
					,ADMIN_ID = ?
					,WEB_ID_YN = ?
					,ADMIN_ABLE_YN = ?
					,USE_ABLE_TIME = ?
					,UPDATE_ID = ? ";
					
	   $db->query($sql,[$admin_no,$company_no,$admin_id,$web_id_yn,$admin_able_yn,$use_able_time,$adminId,$adminId,$admin_id,$web_id_yn,$admin_able_yn,$use_able_time,$adminId]);
  
		// 비밀번호 수정
		if($admin_password != NULL && strlen($admin_password) != 0){
	  		$db->query("UPDATE ADMIN SET ADMIN_PASSWORD = md5(?) WHERE ADMIN_ID = ?",[$admin_password,$admin_id]);
	  	}
	  
	   // 결과값      
       $gt = array();
	   
		return $this->respond($cc->rtndata("200","저장되었습니다.",$gt));
    }
	public function deleteAdmin(){
		
	}
}
