<?php 
namespace App\Controllers;
use App\Models\LoginModel;
use CodeIgniter\Controller;

class LoginController extends BaseController
{
    private $login = '' ;
    public function __construct(){
      
        $this->login = new LoginModel();       
    }
    
    // show login form
    public function index()    {  

        $session = session();  
        
		if($session-> get('user') == NULL){
			$session-> get('msg');
			$data['emsg'] = $session-> get('emsg');
			if($data['emsg'] == NULL){
				$err_data['msg'] = '';
				$err_data['adminId'] = '';
				$data['emsg'] = $err_data;
			}
			$session-> set('emsg');
	        return view('login',$data);
		}else{
			 return redirect()->to(base_url().'/home');
		}
    }      

    //check user is exist or not
    public function login(){
        $adminId = $this->request->getVar('adminId');
		$adminPw = $this->request->getVar('adminPw');
        // $data = array('ADMIN_ID'=>$adminId,'ADMIN_PASSWORD'=>md5($this->request->getVar('adminPw')));       
        // $user =  $this->login->where($data); 
        // $rows = $this->login->countAllResults();
        
      $sql = "SELECT A.*, B.COMPANY_NM,B.COMPANY_BIZ_NO,B.COMPANY_PHONE_NO,B.MACHINE_CNT,B.CAL_TIME FROM ADMIN A
				INNER JOIN COMPANY B ON A.COMPANY_NO = B.COMPANY_NO
				WHERE A.ADMIN_ID = ? AND ADMIN_PASSWORD = md5(?) AND WEB_ID_YN = 'Y' ";

	  $db = \Config\Database::connect("default");      
	  $query = $db->query($sql, [$adminId,$adminPw]);
      
      $rows = $query->getResultArray();
		
		$session = session();
		
        if(count($rows)==1){
        	$session-> set('user', $rows[0]);
            return redirect()->to(base_url().'/home');
        }else{
        	
			$err_data['msg'] = '로그인 실패!';
			$err_data['adminId'] = $adminId;
			$session-> set('emsg', $err_data);
            return redirect()->to(base_url().'/');
        } 
     }
	
	public function logout(){
			$session = session();  
			$session-> set('user');
			return redirect()->to(base_url().'/');
	}
}