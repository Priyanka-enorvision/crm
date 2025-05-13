<?php
namespace App\Controllers\Erp;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\I18n\Time;
 
use App\Models\SystemModel;
use App\Models\RolesModel;
use App\Models\UsersModel;
use App\Models\MainModel;
use App\Models\ConstantsModel;
use App\Models\EventsModel;
use App\Models\MomsModel;
use App\Models\EmailtemplatesModel;
use App\Models\MomdiscussionModel;

class Moms extends BaseController {
	
	public function index()
	{		
		$RolesModel = new RolesModel();
		$UsersModel = new UsersModel();
		$SystemModel = new SystemModel();
		$request = \Config\Services::request();
		$session = \Config\Services::session();
		
		$usession = $session->get('sup_username');
		$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
		if(!$session->has('sup_username')){ 
			$session->setFlashdata('err_not_logged_in',lang('Dashboard.err_not_logged_in'));
			return redirect()->to(site_url('erp/login'));
		}
		if($user_info['user_type']!='staff' && $user_info['user_type']!='company' ){
			$session->setFlashdata('unauthorized_module',lang('Dashboard.xin_error_unauthorized_module'));
			return redirect()->to(site_url('erp/desk'));
		}
		$xin_system = $SystemModel->where('setting_id', 1)->first();
		$data['title'] = lang('Dashboard.left_moms').' | '.$xin_system['application_name'];
		$data['path_url'] = 'moms';
		$data['breadcrumbs'] = lang('Dashboard.left_moms').$user_id;
		$data['subview'] = view('erp/moms/staff_mom', $data);
		return view('erp/layout/layout_main', $data); //page load
	}


	// public function moms_list() {

	// 	$session = \Config\Services::session();
	// 	$usession = $session->get('sup_username');
	// 	if(!$session->has('sup_username')){ 
	// 		return redirect()->to(site_url('erp/login'));
	// 	}		
	// 	$RolesModel = new RolesModel();
	// 	$UsersModel = new UsersModel();
	// 	$SystemModel = new SystemModel();
	// 	$MomsModel = new MomsModel();
	// 	$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
	// 	if($user_info['user_type'] == 'staff'){
	// 		// $get_data = assigned_staff_tasks($usession['sup_user_id']);
	// 		$get_data = $MomsModel->where('company_id',$user_info['company_id'])->orderBy('id', 'ASC')->findAll();
	// 	} else {
	// 		$get_data = $MomsModel->where('company_id',$usession['sup_user_id'])->orderBy('id', 'ASC')->findAll();
	// 	}
	// 	$data = array();
		
    //       foreach($get_data as $r) {
			  
	// 		if(in_array('mom4',staff_role_resource()) || $user_info['user_type'] == 'company') { //delete
	// 			$delete = '<span data-toggle="tooltip" data-placement="top" data-state="danger" title="'.lang('Main.xin_delete').'"><button type="button" class="btn icon-btn btn-sm btn-light-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. uencode($r['id']) . '"><i class="feather icon-trash-2"></i></button></span>';
	// 		} else {
	// 			$delete = '';
	// 		}
	// 		$view = '<span data-toggle="tooltip" data-placement="top" data-state="primary" title="'.lang('Main.xin_view_details').'"><a href="'.site_url('erp/mom-detail').'/'.uencode($r['id']).'"><button type="button" class="btn icon-btn btn-sm btn-light-primary waves-effect waves-light"><i class="feather icon-arrow-right"></i></button></a></span>';
	// 		$title = $r['title'];
	// 		$summary = $r['summary'];
	// 		$description = $r['des$description'];
	// 		$combhr = $view.$delete;
	// 		$itask_name = '
	// 			'.$r['title'].'
	// 			<div class="overlay-edit">
	// 				'.$combhr.'
	// 			</div>';
	// 		$data[] = array(
	// 			$itask_name,
	// 			$summary,
	// 			$description,
	// 			$combhr
	// 		);
			
	// 	}
    //       $output = array(
    //            //"draw" => $draw,
	// 		   "data" => $data
    //         );
    //       echo json_encode($output);
    //       exit();
    // }


    public function moms_calendar()
	{		
		$RolesModel = new RolesModel();
		$UsersModel = new UsersModel();
		$SystemModel = new SystemModel();
		$request = \Config\Services::request();
		$session = \Config\Services::session();
		
		$usession = $session->get('sup_username');
		$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
		if(!$session->has('sup_username')){ 
			$session->setFlashdata('err_not_logged_in',lang('Dashboard.err_not_logged_in'));
			return redirect()->to(site_url('erp/login'));
		}
		if($user_info['user_type'] != 'company' && $user_info['user_type']!='staff'){
			$session->setFlashdata('unauthorized_module',lang('Dashboard.xin_error_unauthorized_module'));
			return redirect()->to(site_url('erp/desk'));
		}
		if($user_info['user_type'] != 'company'){
			if(!in_array('moms_calendar',staff_role_resource())) {
				$session->setFlashdata('unauthorized_module',lang('Dashboard.xin_error_unauthorized_module'));
				return redirect()->to(site_url('erp/desk'));
			}
		}
		$xin_system = $SystemModel->where('setting_id', 1)->first();
		$data['title'] = lang('Dashboard.xin_acc_calendar').' | '.$xin_system['application_name'];
		$data['path_url'] = 'moms';
		$data['breadcrumbs'] = lang('Dashboard.xin_acc_calendar').$user_id;

		$data['subview'] = view('erp/moms/calendar_moms', $data);
		return view('erp/layout/layout_main', $data); //page load
	}


    public function add_mom() {

		$validation = \Config\Services::validation();
		$session = \Config\Services::session();
		$request = \Config\Services::request();
		
		$usession = $session->get('sup_username');
	
		if ($request->getPost()) {
			
			// Validation Rules
			$rules = [
				'title' => 'required',
				'summary' => 'required|min_length[10]',
				'description' => 'required|min_length[10]',
				'meeting_date' => 'required'
			];
	
			$validation->setRules($rules);
	
			if (!$validation->withRequest($this->request)->run()) {
				$errors = $validation->getErrors();
				$session->setFlashdata('error', reset($errors));
				return redirect()->to(site_url('erp/moms-grid'));
			} else {
				// Sanitize Inputs
				$title = $request->getPost('title', FILTER_SANITIZE_STRING);
				$summary = $request->getPost('summary', FILTER_SANITIZE_STRING);
				$meeting_date = $request->getPost('meeting_date', FILTER_SANITIZE_STRING);
				$description = $request->getPost('description', FILTER_SANITIZE_STRING);
	
				$project_ids = $this->request->getPost('project_id');
				if (is_array($project_ids)) {
					$filtered_project_ids = array_filter($project_ids, function($id) {
						return $id !== '0' && $id !== ''; 
					});
					$project_id = implode(',', $filtered_project_ids);
				} else {
					$project_id = '';
				}
	
				$assigned_ids = $this->request->getPost('assigned_to');
				if (is_array($assigned_ids)) {
					$filtered_assigned_ids = array_filter($assigned_ids, function($id) {
						return $id !== '0' && $id !== ''; 
					});
					$employee_ids = implode(',', $filtered_assigned_ids);
				} else {
					$employee_ids = ''; 
				}
	
				$UsersModel = new UsersModel();
				$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
	
				if ($user_info && ($user_info['user_type'] == 'staff' || $user_info['user_type'] == 'customer')) {
					$company_id = $user_info['company_id'];
				} else {
					$company_id = $usession['sup_user_id'];
				}
	
				$data = [
					'company_id' => $company_id,
					'title' => $title,
					'summary' => $summary,
					'project_id' => $project_id,
					'assigned_to' => $employee_ids,
					'meeting_date' => $meeting_date,
					'description' => $description,
					'created_at' => date('Y-m-d H:i:s')
				];

				$MomsModel = new MomsModel();
				$result = $MomsModel->insert($data);
	
				if ($result) {
					$session->setFlashdata('success', lang('Success.ci_mom_added_msg'));
					return redirect()->to(site_url('erp/moms-grid'));
				} else {
					print_r($MomsModel->errors());
	
					$session->setFlashdata('error', lang('Main.xin_error_msg'));
					return redirect()->to(site_url('erp/moms-grid'));
				}
			}
		} else {
			$session->setFlashdata('error', lang('Main.xin_error_msg'));
			return redirect()->to(site_url('erp/moms-grid'));
		}
	}
	

      // |||update record|||
	  public function update_mom() {

		$validation = \Config\Services::validation();
		$session = \Config\Services::session();
		$request = \Config\Services::request();
		$usession = $session->get('sup_username');    
	
		if ($request->getPost()) {
			
			// Set validation rules
			$rules = [
				'title' => 'required',
				'summary' => 'required|min_length[10]',
				'description' => 'required|min_length[10]',
				'meeting_date' => 'required'
			];
			
			$validation->setRules($rules);
	
			if (!$validation->withRequest($this->request)->run()) {
				$errors = $validation->getErrors();
				$session->setFlashdata('error', reset($errors));
				return redirect()->to(site_url('erp/moms-grid'));
			} else {
				
				$title = $this->request->getPost('title');
				$summary = $this->request->getPost('summary');
				$description = $this->request->getPost('description');
				$meeting_date = $this->request->getPost('meeting_date');
				$id = udecode($this->request->getPost('token'));
	
				$associated_goals = $this->request->getPost('associated_goals') ?? [];
				$associated_goals = is_array($associated_goals) ? implode(',', $associated_goals) : '';
	
				$project_ids = $this->request->getPost('project_id') ?? [];
				$filtered_project_ids = array_filter($project_ids, function($id) {
					return $id !== '0' && $id !== '';
				});
				$project_id = implode(',', $filtered_project_ids);
	
				
				$assigned_ids = $this->request->getPost('assigned_to') ?? [];
				$filtered_assigned_ids = array_filter($assigned_ids, function($id) {
					return $id !== '0' && $id !== '';
				});
				$employee_ids = implode(',', $filtered_assigned_ids);
				
				
				$data = [
					'title' => $title,
					'summary' => $summary,
					'project_id' => $project_id,
					'assigned_to' => $employee_ids,
					'description' => $description,
					'meeting_date' => $meeting_date,
					'associated_goals' => $associated_goals
				];
	
	
				// Models
				$MomsModel = new MomsModel();
				$UsersModel = new UsersModel();
				$SystemModel = new SystemModel();
				$EmailtemplatesModel = new EmailtemplatesModel();
	
				$xin_system = $SystemModel->where('setting_id', 1)->first();
	
				$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
	
				if ($user_info['user_type'] == 'company') {
					$company_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
				} else {
					$company_id = $user_info['company_id']; 
					$company_info = $UsersModel->where('company_id', $company_id)->first();
				}
	
				$result = $MomsModel->update($id, $data);
			
				if ($result) {
					$session->setFlashdata('success', lang('Success.ci_mom_updated_msg'));
				} else {
					
					print_r($MomsModel->errors());
			
	
					$session->setFlashdata('error', lang('Main.xin_error_msg'));
				}
				
				return redirect()->to(site_url('erp/moms-grid'));
			}
		} else {
			$session->setFlashdata('error', lang('Main.xin_error_msg'));
			return redirect()->to(site_url('erp/moms-grid'));
		}
	}
	
	
    public function moms_grid()
	{		
		$RolesModel = new RolesModel();
		$UsersModel = new UsersModel();
		$SystemModel = new SystemModel();
		//$AssetsModel = new AssetsModel();
		$session = \Config\Services::session();
		$usession = $session->get('sup_username');
		$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
		
		if(!$session->has('sup_username')){ 
			$session->setFlashdata('err_not_logged_in',lang('Dashboard.err_not_logged_in'));
			return redirect()->to(site_url('erp/login'));
		}
		if($user_info['user_type'] != 'company' && $user_info['user_type']!='staff'){
			$session->setFlashdata('unauthorized_module',lang('Dashboard.xin_error_unauthorized_module'));
			return redirect()->to(site_url('erp/desk'));
		}
		if($user_info['user_type'] != 'company'){
			if(!in_array('task1',staff_role_resource())) {
				$session->setFlashdata('unauthorized_module',lang('Dashboard.xin_error_unauthorized_module'));
				return redirect()->to(site_url('erp/desk'));
			}
		}
		$usession = $session->get('sup_username');
		$xin_system = $SystemModel->where('setting_id', 1)->first();
		$data['title'] = lang('Dashboard.left_moms').' | '.$xin_system['application_name'];
		$data['path_url'] = 'moms_grid';
		$data['breadcrumbs'] = lang('Dashboard.left_moms');
		$data['subview'] = view('erp/moms/moms_grid', $data);
		return view('erp/layout/layout_main', $data); 
	}


	public function moms_delete()
	{
		if ($this->request->getPost('type') == 'delete_record') {
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$session = \Config\Services::session();
			$request = \Config\Services::request();
			$usession = $session->get('sup_username');
			$id = udecode($this->request->getPost('_token', FILTER_SANITIZE_STRING));
			$Return['csrf_hash'] = csrf_hash();
			$MomsModel = new MomsModel();
			$result = $MomsModel->where('id', $id)->delete($id);
			if ($result == TRUE) {
				$Return['result'] = "Mom Remove SuccessFully.";
			} else {
				$Return['error'] = lang('Main.xin_error_msg');
			}
			$this->output($Return);
		}
	}
    
    public function mom_details()
	{		
		$RolesModel = new RolesModel();
		$UsersModel = new UsersModel();
		$SystemModel = new SystemModel();
		$MomsModel = new MomsModel();
		$request = \Config\Services::request();
		$session = \Config\Services::session();
		
		$usession = $session->get('sup_username');
		$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
		if(!$session->has('sup_username')){ 
			return redirect()->to(site_url('erp/login'));
		}
		if($user_info['user_type'] != 'company' && $user_info['user_type']!='staff'){
			return redirect()->to(site_url('erp/desk'));
		}
		$usession = $session->get('sup_username');
		$xin_system = $SystemModel->where('setting_id', 1)->first();
		$segment_id = $request->uri->getSegment(3);
		$ifield_id = udecode($segment_id);

		$isegment_val = $MomsModel->where('id', $ifield_id)->first();
		if(!$isegment_val){
			$session->setFlashdata('unauthorized_module',lang('Dashboard.xin_error_unauthorized_module'));
			return redirect()->to(site_url('erp/desk'));
		}
		if($user_info['user_type'] == 'staff'){
			$mom_data = $MomsModel->where('company_id',$user_info['company_id'])->where('id',$ifield_id)->first();
		} else {
			$mom_data = $MomsModel->where('company_id',$usession['sup_user_id'])->where('id', $ifield_id)->first();
		}

		$data['title'] = lang('Mom.xin_mom_details').' | '.$xin_system['application_name'];
		$data['path_url'] = 'mom_details';
		$data['breadcrumbs'] = lang('Mom.xin_mom_details').$user_id;

		$data['subview'] = view('erp/moms/mom_details', $data);
		return view('erp/layout/layout_main', $data); //page load
	}



    public function add_mom_discussion() {
        $validation = \Config\Services::validation();
        $session = \Config\Services::session();
        $request = \Config\Services::request();
        $usession = $session->get('sup_username');    
    
        if ($request->getMethod() == 'post') {
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = csrf_hash();
    
            $rules = [
                'description' => 'required'
            ];
    
            if (!$this->validate($rules)) {
				
                return redirect()->to(site_url('erp/moms-grid'));
               
            } else {
                $UsersModel = new UsersModel();
                $user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
    
                if ($user_info['user_type'] == 'staff') {
                    $company_id = $user_info['company_id'];
                } else {
                    $company_id = $usession['sup_user_id'];
                }
    
                $description = $request->getPost('description', FILTER_SANITIZE_STRING);
                $id = udecode($request->getPost('token', FILTER_SANITIZE_STRING));
    
                $data = [
                    'company_id' => $company_id,
                    'mom_id' => $id,
                    'employee_id'  => $usession['sup_user_id'],
                    'discussion_text'  => $description,
                    'created_at' => date('Y-m-d H:i:s') 
                ];
    
                $MomdiscussionModel = new MomdiscussionModel();
                $result = $MomdiscussionModel->insert($data);
    
                if ($result) {
					$session->setFlashdata('success',lang('Success.ci_task_discussion_added_msg'));
                    return redirect()->to(site_url('erp/moms-grid'));
                } else {
					$session->setFlashdata('error',lang('Main.xin_error_msg'));
                    return redirect()->to(site_url('erp/moms-grid'));
                }
    
                return redirect()->to(site_url('erp/moms-grid'));
                $Return['csrf_hash'] = csrf_hash();
                $this->output($Return);
                exit;
            }
        } else {
			$session->setFlashdata('error',lang('Main.xin_error_msg'));
            return redirect()->to(site_url('erp/moms-grid'));
            $this->output($Return);
            exit;
        }
    }
    

}
