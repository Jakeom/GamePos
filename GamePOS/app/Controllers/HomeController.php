<?php 
namespace App\Controllers;
use CodeIgniter\Controller;

class HomeController extends BaseController
{

    public function __construct(){
        
    }
    
    // show Home
    public function index()    {  

		// 세션이 없으면 팅기기
        $session = session();
        if($session-> get('user') == NULL){
        	return redirect()->to(base_url().'/logout');
		}
	    return view('home');
		
    }     
	
    public function user()    {  
		// 세션이 없으면 팅기기
        $session = session();
        if($session-> get('user') == NULL){
        	return redirect()->to(base_url().'/logout');
		}
	    return view('user');
    } 
	
    public function admin()    {  
		// 세션이 없으면 팅기기
        $session = session();
		$sessionData = $session-> get('user'); 
        if($sessionData == NULL || $sessionData['ADMIN_ID'] != 'ADMIN'){
        	return redirect()->to(base_url().'/logout');
		}

	    return view('admin');
    } 
	
    public function baseCalculate()    {  
		// 세션이 없으면 팅기기
        $session = session();
        if($session-> get('user') == NULL){
        	return redirect()->to(base_url().'/logout');
		}
		
		$user_no = $this->request->getVar('USER_NO');
		$data['USER_NO'] =$user_no;
	    return view('basecalculate',$data);
    } 
		
    public function dayCalculate()    {  
		// 세션이 없으면 팅기기
        $session = session();
        if($session-> get('user') == NULL){
        	return redirect()->to(base_url().'/logout');
		}
	    return view('daycalculate');
    } 
   
    public function machineCalculate()    {  
		// 세션이 없으면 팅기기
        $session = session();
        if($session-> get('user') == NULL){
        	return redirect()->to(base_url().'/logout');
		}
	    return view('machinecalculate');
    } 
	
    public function key()    {  
		// 세션이 없으면 팅기기
        $session = session();
        if($session-> get('user') == NULL){
        	return redirect()->to(base_url().'/logout');
		}
	    return view('key');
    } 
	
    public function setting()    {  
		// 세션이 없으면 팅기기
        $session = session();
        if($session-> get('user') == NULL){
        	return redirect()->to(base_url().'/logout');
		}
	    return view('setting');
    } 

}