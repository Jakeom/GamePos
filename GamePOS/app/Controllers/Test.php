<?php namespace App\Controllers;
use App\Models\LoginModel;


class Test extends BaseController
{

        private $login = '' ;
        public function __construct(){
        
                $this->login = new LoginModel();       
        }

	public function index(){
               
                // $headers = apache_request_headers();

                // foreach ($headers as $header => $value) {
                // echo "$header: $value <br />";
                // }

                // $db = \Config\Database::connect("default");
                // $query = $db->query('SELECT * FROM ADMIN');
                // $results = $query->getResultArray();
                // echo "<xmp>";
                // print_r($results);
                // echo "</xmp>";
                $session = session();  
                $session->setFlashdata('msg', '111');
				
				$newdata = array(
                    'username' => 'username',
                    'email' =>'email',
                    'logged_in' => TRUE
                );

				// 로그인
				//$session-> set('user',$newdata);
				// 로그아웃
				$session-> set('user');
				
				
				
				var_dump($session->get('user'));
				echo session('msg');
                return view('test');
                //return view('login');
		}
	
		public function test(){
			
		}
		
		public function logout(){
			$session = session();  
			$session-> set('user');
			return redirect()->to(base_url().'/');
		}

        public function login(){
                
                $data = array('ADMIN_ID'=>$this->request->getVar('user_id'),'ADMIN_PASSWORD'=>md5($this->request->getVar('password')));       
                $user =  $this->login->where($data); 
                $rows = $this->login->countAllResults();
                $session = session();          
                if($rows==1){
                    return view('success');
                }else{
                    $session->setFlashdata('msg', 'Invalid User');
                    return view('login');
                }
             }
}
