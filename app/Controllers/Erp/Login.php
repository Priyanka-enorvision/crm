<?php
namespace App\Controllers\Erp;
use App\Controllers\BaseController;
 
use App\Models\SystemModel;

class Login extends BaseController {

	public function index()
	{		
		$SystemModel = new SystemModel();
		$session = \Config\Services::session($config);
		if($session->has('sup_username')){ 
			return redirect()->to(site_url('erp/desk?module=dashboard'));
		}
		$xin_system = $SystemModel->where('setting_id', 1)->first();
		$data['title'] = $xin_system['application_name'].' | '.lang('Login.xin_login_title');
		return view('erp/auth/erp_login',$data);
	}
}
