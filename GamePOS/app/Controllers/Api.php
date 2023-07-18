<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Libraries\Customclass;

class Api extends ResourceController
{
    use ResponseTrait;

    function uuidgen() {
      return sprintf('%08x%04x%04x%04x%04x%08x',
         mt_rand(0, 0xffffffff),
         mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
         mt_rand(0, 0xffff), mt_rand(0, 0xffffffff)
       );
   }

    // all users
    public function index(){

      $cc = new Customclass();
      // 토큰 체크
        if(!$cc->checkAuth()){
          return $this->respond($cc->rtndata("999","토큰 에러",null));
        }

        return $this->respond($cc->rtndata("200","",array()));
    }

    // 로그인 확인
    public function adminLogin(){
      $cc = new Customclass();
      // 파라메터 정의
      $admin_id =$this->request->getVar('ADMIN_ID');
      $admin_password =$this->request->getVar('ADMIN_PASSWORD');
      $machine_id =$this->request->getVar('MACHINE_ID');

      $db = \Config\Database::connect("default");
      $query = $db->query("SELECT *, CASE WHEN USE_ABLE_TIME < NOW() THEN 0 ELSE 1 END AS USE_ABLE_TIME FROM ADMIN WHERE ADMIN_ID='".$admin_id."' AND ADMIN_PASSWORD= MD5('".$admin_password."')");
      $results = $query->getResultArray();

      $result_admin_able_yn = null;
      $result_able_key = null;
      $result_machine_id = null;
      $result_use_able_time = null;
      $result_company_no = null;
      foreach ($results as $result){
        $result_admin_able_yn = $result['ADMIN_ABLE_YN'];
        $result_machine_id = $result['MACHINE_ID'];
        $result_use_able_time = $result['USE_ABLE_TIME'];
        $result_company_no = $result['COMPANY_NO'];
      }

      if(count($results) == 0){
        // 관리자 데이터 취득 실패
        return $this->respond($cc->rtndata("500","아이디/패스워드를 확인해주세요.",null));
      }else if($result_use_able_time == 0){
        // 사용 가능기간 만료 아이디
        return $this->respond($cc->rtndata("500","사용기간이 만료되 었습니다. 관리자에게 문의해주세요.",null));
      }else if($result_admin_able_yn == 'Y'){
        // 활성 가능 상태이고 머신 아이디가 들어 있지 않으면 활성화 시키고 정상 로그인 처리함.
        $query = $db->query("UPDATE ADMIN SET ADMIN_ABLE_YN = 'N' ,MACHINE_ID = '".$machine_id."' WHERE ADMIN_ID='".$admin_id."' AND ADMIN_PASSWORD= MD5('".$admin_password."')");
      }else if($machine_id != $result_machine_id){
        // 다른 기기에서 이미 사용중임
        return $this->respond($cc->rtndata("500","이미 다른 기기에 활성화 되어 있습니다. 관리자에게 문의해주세요.",null));
      }

      $gtoken = $this->uuidgen();
      $query = $db->query("UPDATE ADMIN SET GTOKEN_TIME = DATE_ADD(NOW(),INTERVAL '3' HOUR) ,GTOKEN = '".$gtoken."' WHERE ADMIN_ID='".$admin_id."' AND ADMIN_PASSWORD= MD5('".$admin_password."')");
      // 로그인 성공했을때 gtoken 을 넘김  
      $gt = array();
      $gt['GTOKEN'] = $gtoken;
      $gt['COMPANY_NO'] = $result_company_no;
      $gt['ADMIN_ID'] = $admin_id;
      return $this->respond($cc->rtndata("200","",$gt));
    }

    // 회원등록
    public function insertUser(){
      $cc = new Customclass();
      // 토큰 체크
      if(!$cc->checkAuth()){
        return $this->respond($cc->rtndata("999","토큰 에러",null));
      }

      // 파라메터 정의
      //$user_no = $this->request->getVar('USER_NO');
      $company_no =$this->request->getVar('COMPANY_NO');
      $user_nm =$this->request->getVar('USER_NM');
      $password =$this->request->getVar('PASSWORD');
      $phone_no =$this->request->getVar('PHONE_NO');
      $search_no =$this->request->getVar('SEARCH_NO');
      //$last_point =$this->request->getVar('LAST_POINT');
      $admin_id =$this->request->getVar('ADMIN_ID');

      // 쿼리
      $db = \Config\Database::connect("default");
      $sql = "INSERT INTO USER (`USER_NO`,`COMPANY_NO`,`USER_NM`,`PASSWORD`,`PHONE_NO`,`SEARCH_NO`,`LAST_POINT`,`CREATE_ID`,`UPDATE_ID`,`CREATE_TIME`,`UPDATE_TIME`)";
      $sql = $sql." VALUES (NULL,?,?,?,?,?,?,?,?,NOW(),NOW())";
      $query = $db->query($sql, [$company_no, $user_nm, md5($password),$phone_no,$search_no,0, $admin_id, $admin_id]);
      // 결과값  
	  $gt = array();   
      return $this->respond($cc->rtndata("200","회원등록 되었습니다.",$gt));
    }

  // 회원수정
    public function updateUser(){
      $cc = new Customclass();
      // 토큰 체크
      if(!$cc->checkAuth()){
        return $this->respond($cc->rtndata("999","토큰 에러",null));
      }

      // 파라메터 정의
      $user_no = $this->request->getVar('USER_NO');
      $company_no =$this->request->getVar('COMPANY_NO');
      $user_nm =$this->request->getVar('USER_NM');
      $password =$this->request->getVar('PASSWORD');
      $phone_no =$this->request->getVar('PHONE_NO');
      $search_no =$this->request->getVar('SEARCH_NO');
      //$last_point =$this->request->getVar('LAST_POINT');
      $admin_id =$this->request->getVar('ADMIN_ID');

      //var_dump($cc->rsstr($this->request->getVar('test')));

      //쿼리
      $db = \Config\Database::connect("default");

      $sql = "UPDATE `USER` SET `PHONE_NO`= ?,`USER_NM`=? ,`SEARCH_NO`=?, UPDATE_ID =?, UPDATE_TIME= NOW()  WHERE USER_NO = ?";
      $query = $db->query($sql, [$phone_no, $user_nm, $search_no, $admin_id, $user_no]);
            
      // 결과값
      $gt = array(); 
      return $this->respond($cc->rtndata("200","회원정보가 수정 되었습니다.",$gt));
    }

    // 회원검색
    public function searchUser(){

      $cc = new Customclass();
      // 토큰 체크
      if(!$cc->checkAuth()){
        return $this->respond($cc->rtndata("999","토큰 에러",null));
      }

      // 파라메터 정의
      $companyNo =$this->request->getVar('COMPANY_NO');
      $search_key =$this->request->getVar('SEARCH_KEY');
	  
      $user_no =$this->request->getVar('USER_NO');
      $page =intval($this->request->getVar('PAGE'));
      $row_cnt =intval($this->request->getVar('ROW_CNT'));

      // 페이지가 없을때 페이지 번호는 0  검색 카운트는 20 으로 고정함
      if($page == null){
        $page = 0;
      }
      if($row_cnt == null){
        $row_cnt = 20;
      }
      
      $db = \Config\Database::connect("default");
      
      // 검색쿼리 생성
      $sql = "SELECT `USER_NO`, `COMPANY_NO`, `USER_NM`, `PHONE_NO`, `SEARCH_NO`, `LAST_POINT` FROM `USER` WHERE 1=1  AND COMPANY_NO=? ";
      if(!is_null($search_key) && strlen($search_key) > 0){  // 키워드가 있을떄만 조회함
        $sql = $sql."AND (`SEARCH_NO`= '".$search_key."' OR `USER_NM` LIKE '%".$search_key."%')";
      }
	  if(!is_null($user_no) && strlen($user_no) > 0){  // 키워드가 있을떄만 조회함
        $sql = $sql."AND `USER_NO`= '".$user_no."'";
      }
	  $sql = $sql." ORDER BY `USER_NO` DESC ";
      $sql = $sql."LIMIT ".$page*$row_cnt.",".$row_cnt;
      
      $query = $db->query($sql,[$companyNo]);
      $results = $query->getResultArray();

      // 결과값      
      $gt = array();
	  $gt['USER_LIST'] = $results;     
	  return $this->respond($cc->rtndata("200","",$gt));
  }

  // 포인트 등록
  public function insertPoint(){
    $cc = new Customclass();
    // 토큰 체크
    if(!$cc->checkAuth()){
      return $this->respond($cc->rtndata("999","토큰 에러",null));
    }

    // 파라메터 정의
    $user_no = $this->request->getVar('USER_NO');
	$company_no =$this->request->getVar('COMPANY_NO');
    $machine_no =$this->request->getVar('MACHINE_NO');
    $point =$this->request->getVar('POINT');
    $point_tp =$this->request->getVar('POINT_TP');
    $admin_id =$this->request->getVar('ADMIN_ID');
	
	if($company_no == null && strlen($company_no) == 0){
		return $this->respond($cc->rtndata("500","전달값 에러",null));
	}
		

    // 쿼리
    $db = \Config\Database::connect("default");
    $sql = "INSERT INTO POINT (`POINT_NO`,`COMPANY_NO`,`USER_NO`,`ADMIN_ID`,`MACHINE_NO`,`POINT_TP`,`POINT`,`LAST_POINT`, `CREATE_ID`,`UPDATE_ID`,`CREATE_TIME`,`UPDATE_TIME`)";
    $sql = $sql." VALUES (NULL,?,?,?,?,?,?,(SELECT IFNULL(SUM(A.POINT),0)+CAST(? AS SIGNED) FROM POINT A WHERE A.USER_NO = ? AND A.DELETE_YN = 'N'),?,?,NOW(),NOW())";
	
    $query = $db->query($sql, [$company_no,$user_no, $admin_id, $machine_no, $point_tp, $point,$point, $user_no, $admin_id,$admin_id]);

    // 최종 포인트 업데이트
    $query = $db->query("UPDATE USER SET LAST_POINT = (SELECT IFNULL(SUM(POINT),0) FROM POINT WHERE USER_NO = ? AND DELETE_YN = 'N') WHERE USER_NO = ?", [$user_no, $user_no]);
    // 결과값   
	$gt = array();
    return $this->respond($cc->rtndata("200","포인트 처리 되었습니다.",$gt));
  }

  // 포인트 삭제처리
  public function deletePoint(){
    $cc = new Customclass();
    // 토큰 체크
    if(!$cc->checkAuth()){
      return $this->respond($cc->rtndata("999","토큰 에러",null));
    }

    // 파라메터 정의
    $user_no = $this->request->getVar('USER_NO');
    $point_no = $this->request->getVar('POINT_NO');
    $delete_note =$this->request->getVar('DELETE_NOTE');
    $admin_id =$this->request->getVar('ADMIN_ID');

    
    // 쿼리
    $db = \Config\Database::connect("default");
    $sql = "UPDATE POINT SET DELETE_YN= 'Y',  DELETE_NOTE= ?, DELETE_TIME= NOW(), UPDATE_ID = ? WHERE POINT_NO = ? AND DELETE_YN = 'N'";
    $query = $db->query($sql, [$delete_note, $admin_id, $point_no]);

    // 최종 포인트 업데이트
    $query = $db->query("UPDATE USER SET LAST_POINT = (SELECT IFNULL(SUM(POINT),0) FROM POINT WHERE USER_NO = ? AND DELETE_YN = 'N') WHERE USER_NO = ?", [$user_no, $user_no]);
    // 결과값  
	$gt = array();
    return $this->respond($cc->rtndata("200","포인트처리 취소 되었습니다.",$gt));
  }

  // 포인트 검색
  public function searchPoint(){

    $cc = new Customclass();
    // 토큰 체크
    if(!$cc->checkAuth()){
      return $this->respond($cc->rtndata("999","토큰 에러",null));
    }

    // 파라메터 정의
    $point_no = $this->request->getVar('POINT_NO');
    $admin_id =$this->request->getVar('ADMIN_ID');
    $st_date =$this->request->getVar('ST_DATE');
    $ed_date =$this->request->getVar('ED_DATE');
    $machine_no =$this->request->getVar('MACHINE_NO');
    $user_no = $this->request->getVar('USER_NO');
    $search_key =$this->request->getVar('SEARCH_KEY');
    $page =intval($this->request->getVar('PAGE'));
    $row_cnt =intval($this->request->getVar('ROW_CNT'));

    // 페이지가 없을때 페이지 번호는 0  검색 카운트는 20 으로 고정함
    if($page == null){
      $page = 0;
    }
    if($row_cnt == null){
      $row_cnt = 50;
    }
    
    $db = \Config\Database::connect("default");
    // 검색쿼리 생성
    $sql = "SELECT A.`POINT_NO`, A.`USER_NO`, A.`MACHINE_NO`, A.`ADMIN_ID`, A.`POINT_TP`, A.`POINT`, A.`DELETE_YN`, A.`DELETE_NOTE`, A.`DELETE_TIME`, A.`CREATE_TIME`, A.`LAST_POINT`,B.USER_NM,B.PHONE_NO FROM POINT A INNER JOIN USER B ON A.USER_NO = B.USER_NO WHERE 1=1   ";
    
    if(!is_null($point_no) && strlen($point_no) > 0 && strlen($point_no) != ''){  // 키워드가 있을떄만 조회함
      $sql = $sql."AND A.`POINT_NO` = '".$point_no."'";
    }
    if(!is_null($user_no) && strlen($user_no) > 0 && strlen($user_no) != ''){  // 키워드가 있을떄만 조회함
      $sql = $sql."AND A.`USER_NO` = '".$user_no."'";
    }
    if(!is_null($search_key) && strlen($search_key) > 0 && strlen($search_key) != ''){  // 키워드가 있을떄만 조회함
      $sql = $sql."AND (B.`SEARCH_NO`= '".$search_key."' OR B.`USER_NM` LIKE '%".$search_key."%')";
    }
    if(!is_null($machine_no) && strlen($machine_no) > 0 && strlen($machine_no) != ''){  // 키워드가 있을떄만 조회함
      $sql = $sql."AND A.`MACHINE_NO` = '".$machine_no."'";
    }
    if(!is_null($admin_id) && strlen($admin_id) > 0 && strlen($admin_id) != ''){  // 키워드가 있을떄만 조회함
      $sql = $sql."AND A.`ADMIN_ID` LIKE '%".$admin_id."%'";
    }
    if(!is_null($st_date) && strlen($st_date) > 0){  // 키워드가 있을떄만 조회함
      $sql = $sql."AND A.`CREATE_TIME` BETWEEN '".$st_date."'  AND  '".$ed_date."'";
    }
	$sql = $sql." ORDER BY A.`POINT_NO` DESC ";
    $sql = $sql."LIMIT ".$page*$row_cnt.",".$row_cnt;
    
    $query = $db->query($sql);
    $results = $query->getResultArray();

    // 결과값   
	$gt = array();
    $gt['POINT_LIST'] = $results;     
    return $this->respond($cc->rtndata("200","",$gt));
  }

  // 포인트 총합 검색
  public function searchSumPoint(){

    $cc = new Customclass();
    // 토큰 체크
    if(!$cc->checkAuth()){
      return $this->respond($cc->rtndata("999","토큰 에러",null));
    }

    // 파라메터 정의
    $point_no = $this->request->getVar('POINT_NO');
    $st_date =$this->request->getVar('ST_DATE');
    $ed_date =$this->request->getVar('ED_DATE');
    $machine_no =$this->request->getVar('MACHINE_NO');
    $user_no = $this->request->getVar('USER_NO');
    $search_key =$this->request->getVar('SEARCH_KEY');
    
    $db = \Config\Database::connect("default");
    // 검색쿼리 생성
    $sql = "SELECT 
    SUM(CASE WHEN POINT > 0 THEN A.POINT ELSE 0 END) AS PLUS_POINT 
    ,SUM(CASE WHEN POINT < 0 THEN A.POINT ELSE 0 END) AS MINUS_POINT 
    ,SUM(A.POINT) AS TOTAL 
    FROM `POINT` A INNER JOIN USER B ON A.USER_NO = B.USER_NO WHERE 1=1 AND A.DELETE_YN = 'N' ";
    
    if(!is_null($point_no) && strlen($point_no) > 0){  // 키워드가 있을떄만 조회함
      $sql = $sql."AND A.`POINT_NO` = '".$point_no."'";
    }
    if(!is_null($user_no) && strlen($user_no) > 0){  // 키워드가 있을떄만 조회함
      $sql = $sql."AND A.`USER_NO` = '".$user_no."'";
    }
    if(!is_null($search_key) && strlen($search_key) > 0){  // 키워드가 있을떄만 조회함
      $sql = $sql."AND (B.`SEARCH_NO`= '".$search_key."' OR B.`USER_NM` LIKE '%".$search_key."%')";
    }
    if(!is_null($machine_no) && strlen($machine_no) > 0){  // 키워드가 있을떄만 조회함
      $sql = $sql."AND A.`MACHINE_NO` = '".$machine_no."'";
    }
    if(!is_null($st_date) && strlen($st_date) > 0){  // 키워드가 있을떄만 조회함
      $sql = $sql."AND A.`CREATE_TIME` BETWEEN '".$st_date."'  AND  '".$ed_date."'";
    }
    
    $query = $db->query($sql);
    $results = $query->getResultArray();
    // 결과값      
	$gt = array();
    $gt['SUM_POINT'] = $results;    
    return $this->respond($cc->rtndata("200","",$gt));

  }


  // 예약 등록
  public function insertBook(){
    $cc = new Customclass();
    // 토큰 체크
    if(!$cc->checkAuth()){
      return $this->respond($cc->rtndata("999","토큰 에러",null));
    }

    // 파라메터 정의
    $user_no = $this->request->getVar('USER_NO');
    $machine_no =$this->request->getVar('MACHINE_NO');
    $point =$this->request->getVar('POINT');
    $book_day =$this->request->getVar('BOOK_DAY');
    $note =$this->request->getVar('NOTE');
    $admin_id =$this->request->getVar('ADMIN_ID');

    // 쿼리
    $db = \Config\Database::connect("default");
    $sql = "INSERT INTO BOOK (`BOOK_NO`, `USER_NO`, `MACHINE_NO`, `BOOK_DAY`, `POINT`, `NOTE`, `CREATE_ID`, `UPDATE_ID`, `END`, `CREATE_TIME`, `UPDATE_TIME`)";
    $sql = $sql." VALUES (NULL,?,?,?,?,?,?,?,'N',NOW(),NOW())";
    $query = $db->query($sql, [$user_no, $machine_no, $book_day , $point, $note, $admin_id,$admin_id]);

    // 결과값  
	$gt = array();
    return $this->respond($cc->rtndata("200","예약 되었습니다.",$gt));
  }

  // 예약 삭제
  public function deleteBook(){
    $cc = new Customclass();
    // 토큰 체크
    if(!$cc->checkAuth()){
      return $this->respond($cc->rtndata("999","토큰 에러",null));
    }

    // 파라메터 정의
    $book_no = $this->request->getVar('BOOK_NO');
    
    // 쿼리
    $db = \Config\Database::connect("default");
    $sql = "DELETE FROM BOOK WHERE END ='N' AND BOOK_NO = ? ";
    $query = $db->query($sql, [$book_no]);

    // 결과값  
	$gt = array();
    return $this->respond($cc->rtndata("200","예약 취소 되었습니다.",$gt));
  }

  // 예약 종료 (해당 포인트를 고객 포인트에서 차감함)
  public function endBook(){
    $cc = new Customclass();
    // 토큰 체크
    if(!$cc->checkAuth()){
      return $this->respond($cc->rtndata("999","토큰 에러",null));
    }

    // 파라메터 정의
    $book_no = $this->request->getVar('BOOK_NO');
    $admin_id =$this->request->getVar('ADMIN_ID');

    // 쿼리
    $db = \Config\Database::connect("default");

    // 해당 포인트 차감
    $query = $db->query("INSERT INTO POINT (`POINT_NO`,`USER_NO`,`ADMIN_ID`,`MACHINE_NO`,`POINT_TP`,`POINT`,`CREATE_ID`,`UPDATE_ID`,`CREATE_TIME`,`UPDATE_TIME`)
    SELECT NULL,USER_NO,?, MACHINE_NO, 0, (POINT*-1),?,?,NOW(),NOW() FROM `BOOK` WHERE BOOK_NO =? AND END = 'N'",[$admin_id,$admin_id,$admin_id,$book_no]);

    $sql = "UPDATE BOOK SET END ='Y', END_TIME = NOW() WHERE BOOK_NO = ? AND END='N' ";
    $query = $db->query($sql, [$book_no]);

    // 최종 포인트 업데이트
    $query = $db->query("UPDATE USER SET LAST_POINT = (SELECT IFNULL(SUM(POINT),0) FROM POINT WHERE USER_NO = (SELECT USER_NO FROM BOOK WHERE BOOK_NO = ?) AND DELETE_YN = 'N') WHERE USER_NO = (SELECT USER_NO FROM BOOK WHERE BOOK_NO = ?)", [$book_no, $book_no]);
    
    // 결과값  
	$gt = array();
    return $this->respond($cc->rtndata("200","예약 완료 되었습니다.",$gt));
  }

  // 예약 검색
  public function searchBook(){

    $cc = new Customclass();
    // 토큰 체크
    if(!$cc->checkAuth()){
      return $this->respond($cc->rtndata("999","토큰 에러",null));
    }

    // 파라메터 정의
    $book_no = $this->request->getVar('BOOK_NO');
    $book_day =$this->request->getVar('BOOK_DAY');
    $machine_no =$this->request->getVar('MACHINE_NO');
    $search_key =$this->request->getVar('SEARCH_KEY');
    $page =intval($this->request->getVar('PAGE'));
    $row_cnt =intval($this->request->getVar('ROW_CNT'));

    // 페이지가 없을때 페이지 번호는 0  검색 카운트는 20 으로 고정함
    if($page == null){
      $page = 0;
    }
    if($row_cnt == null){
      $row_cnt = 20;
    }
    
    $db = \Config\Database::connect("default");
    // 검색쿼리 생성
    $sql = "SELECT A.`BOOK_NO`, A.`USER_NO`, A.`MACHINE_NO`, A.`BOOK_DAY`, A.`POINT`, A.`END`, A.`NOTE`, B.USER_NM, B.PHONE_NO FROM `BOOK` A INNER JOIN USER B ON A.USER_NO = B.USER_NO WHERE 1=1 ";
    
    if(!is_null($book_no) && strlen($book_no) > 0){  // 키워드가 있을떄만 조회함
      $sql = $sql."AND A.`BOOK_NO` = '".$book_no."'";
    }
    if(!is_null($search_key) && strlen($search_key) > 0){  // 키워드가 있을떄만 조회함
      $sql = $sql."AND (B.`SEARCH_NO`= '".$search_key."' OR B.`USER_NM` LIKE '%".$search_key."%')";
    }
    if(!is_null($machine_no) && strlen($machine_no) > 0){  // 키워드가 있을떄만 조회함
      $sql = $sql."AND A.`MACHINE_NO` = '".$machine_no."'";
    }
    if(!is_null($book_day) && strlen($book_day) > 0){  // 키워드가 있을떄만 조회함
      $sql = $sql."AND A.`BOOK_DAY` = '".$book_day."'";
    }

    $sql = $sql."LIMIT ".$page*$row_cnt.",".$row_cnt;
    
    $query = $db->query($sql);
    $results = $query->getResultArray();
	
    // 결과값      
	$gt = array();
    $gt['RESERVE_LIST'] = $results;    
    return $this->respond($cc->rtndata("200","",$gt));
  }
}
