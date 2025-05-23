<?php
namespace App\Controllers\Erp;
use App\Controllers\BaseController;

use App\Models\SystemModel;
use App\Models\RolesModel;
use App\Models\UsersModel;
use App\Models\MainModel;
use App\Models\TrainingModel;
use App\Models\TrainersModel;
use App\Models\ConstantsModel;
use App\Models\TrainingnotesModel;

class Training extends BaseController {

	public function index()
	{		
		$RolesModel = new RolesModel();
		$UsersModel = new UsersModel();
		$SystemModel = new SystemModel();
		$request = \Config\Services::request();
		$session = \Config\Services::session();
		$usession = $session->get('sup_username');
		$xin_system = $SystemModel->where('setting_id', 1)->first();
		$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
		if(!$session->has('sup_username')){ 
			$session->setFlashdata('err_not_logged_in',lang('Dashboard.err_not_logged_in'));
			return redirect()->to(site_url('/'));
		}
		if($user_info['user_type'] != 'company' && $user_info['user_type']!='staff'){
			$session->setFlashdata('unauthorized_module',lang('Dashboard.xin_error_unauthorized_module'));
			return redirect()->to(site_url('erp/desk'));
		}
		if($user_info['user_type'] != 'company'){
			if(!in_array('training2',staff_role_resource())) {
				$session->setFlashdata('unauthorized_module',lang('Dashboard.xin_error_unauthorized_module'));
				return redirect()->to(site_url('erp/desk'));
			}
		}
		$data['title'] = lang('Dashboard.left_training').' | '.$xin_system['application_name'];
		$data['path_url'] = 'training';
		$data['breadcrumbs'] = lang('Dashboard.left_training');

		$data['subview'] = view('erp/training/staff_training', $data);
		return view('erp/layout/layout_main', $data); //page load
	}
	public function training_calendar()
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
			return redirect()->to(site_url('/'));
		}
		if($user_info['user_type'] != 'company' && $user_info['user_type']!='staff'){
			$session->setFlashdata('unauthorized_module',lang('Dashboard.xin_error_unauthorized_module'));
			return redirect()->to(site_url('erp/desk'));
		}
		if($user_info['user_type'] != 'company'){
			if(!in_array('training_calendar',staff_role_resource())) {
				$session->setFlashdata('unauthorized_module',lang('Dashboard.xin_error_unauthorized_module'));
				return redirect()->to(site_url('erp/desk'));
			}
		}
		$xin_system = $SystemModel->where('setting_id', 1)->first();
		$data['title'] = lang('Dashboard.xin_acc_calendar').' | '.$xin_system['application_name'];
		$data['path_url'] = 'training';
		$data['breadcrumbs'] = lang('Dashboard.xin_acc_calendar');

		$data['subview'] = view('erp/training/calendar_training', $data);
		return view('erp/layout/layout_main', $data); //page load
	}
	public function training_details($ifield_id)
	{		
		$RolesModel = new RolesModel();
		$UsersModel = new UsersModel();
		$SystemModel = new SystemModel();
		$TrainingModel = new TrainingModel();
		$request = \Config\Services::request();
		$session = \Config\Services::session();
		$usession = $session->get('sup_username');
		// $ifield_id_segement = $request->uri->getSegment(3);
		// $ifield_id = udecode($ifield_id_segement);
		$training_val = $TrainingModel->where('training_id', $ifield_id)->first();
		if(!$training_val){
			$session->setFlashdata('unauthorized_module',lang('Dashboard.xin_error_unauthorized_module'));
			return redirect()->to(site_url('erp/desk'));
		}
		$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
		if(!$session->has('sup_username')){ 
			$session->setFlashdata('err_not_logged_in',lang('Dashboard.err_not_logged_in'));
			return redirect()->to(site_url('/'));
		}
		if($user_info['user_type'] != 'company' && $user_info['user_type']!='staff'){
			$session->setFlashdata('unauthorized_module',lang('Dashboard.xin_error_unauthorized_module'));
			return redirect()->to(site_url('erp/desk'));
		}
		if($user_info['user_type'] != 'company'){
			if(!in_array('training2',staff_role_resource())) {
				$session->setFlashdata('unauthorized_module',lang('Dashboard.xin_error_unauthorized_module'));
				return redirect()->to(site_url('erp/desk'));
			}
		}
		$xin_system = $SystemModel->where('setting_id', 1)->first();
		$data['title'] = lang('Dashboard.left_training_details').' | '.$xin_system['application_name'];
		$data['path_url'] = 'training_details';
		$data['breadcrumbs'] = lang('Dashboard.left_training_details');
		$data['ifield_id'] = $ifield_id;

		$data['subview'] = view('erp/training/training_details', $data);
		return view('erp/layout/layout_main', $data); //page load
	}
	// record list
	public function training_list() {

		$session = \Config\Services::session();
		$usession = $session->get('sup_username');
		if(!$session->has('sup_username')){ 
			return redirect()->to(site_url('/'));
		}		
		$RolesModel = new RolesModel();
		$UsersModel = new UsersModel();
		$SystemModel = new SystemModel();
		$ConstantsModel = new ConstantsModel();
		$TrainingModel = new TrainingModel();
		$TrainersModel = new TrainersModel();
		$xin_system = erp_company_settings();
		$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
		if($user_info['user_type'] == 'staff'){
			$get_data = assigned_staff_training($usession['sup_user_id']);
		} else {
			$get_data = $TrainingModel->where('company_id',$usession['sup_user_id'])->orderBy('training_id', 'ASC')->findAll();
		}
		$data = array();
		
          foreach($get_data as $r) {
			  
			//assigned user
			$assigned_to = explode(',',$r['employee_id']);
			$multi_users = multi_user_profile_photo($assigned_to);
			if(in_array('training4',staff_role_resource()) || $user_info['user_type'] == 'company') { //edit
				$edit = '<span data-toggle="tooltip" data-placement="top" data-state="primary" title="'.lang('Main.xin_edit').'"><button type="button" class="btn icon-btn btn-sm btn-light-primary waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="'. $r['training_id'] . '"><i class="feather icon-edit"></i></button></span>';
			} else {
				$edit = '';
			}
			$view = '<span data-toggle="tooltip" data-placement="top" data-state="primary" title="'.lang('Main.xin_view_details').'"><a href="'.site_url().'erp/training-details/'.$r['training_id'].'"><button type="button" class="btn icon-btn btn-sm btn-light-primary waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span>';
			
			if(in_array('training6',staff_role_resource()) || $user_info['user_type'] == 'company') { //delete
				$delete = '<span data-toggle="tooltip" data-placement="top" data-state="danger" title="'.lang('Main.xin_delete').'"><button type="button" class="btn icon-btn btn-sm btn-light-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r['training_id'] . '"><i class="feather icon-trash-2"></i></button></span>';
			} else {
				$delete = '';
			}
			
			$start_date = set_date_format($r['start_date']);
			$finish_date = set_date_format($r['finish_date']);
			//status
			if($r['training_status']==0):
				$status = '<span class="badge badge-light-warning">'.lang('Main.xin_pending').'</span>';
			elseif($r['training_status']==1):
				$status = '<span class="badge badge-light-info">'.lang('Projects.xin_started').'</span>';
			elseif($r['training_status']==2):
				$status = '<span class="badge badge-light-success">'.lang('Projects.xin_completed').'</span>';
			else:
				$status = '<span class="badge badge-light-danger">'.lang('Main.xin_rejected').'</span>'; endif;
			/// trainer
			$trainer = $TrainersModel->where('trainer_id', $r['trainer_id'])->first();
			/// trainer type
			$training_types = $ConstantsModel->where('constants_id',$r['training_type_id'])->first();
			/////
			$itype = $training_types['category_name'];
			// training date
			$start_date = set_date_format($r['start_date']);
			$finish_date = set_date_format($r['finish_date']);
			// set currency
			$training_cost = number_to_currency($r['training_cost'], $xin_system['default_currency'],null,2);
			$combhr = $edit.$view.$delete;	
			$t_type = '
				'.$itype.'
				<div class="overlay-edit">
					'.$combhr.'
				</div>';
			$data[] = array(
				$t_type,
				$trainer['first_name'].' '.$trainer['last_name'],
				$start_date,
				$finish_date,
				$multi_users,
				$training_cost,
				$status
			);
			
		}
          $output = array(
               //"draw" => $draw,
			   "data" => $data
            );
          echo json_encode($output);
          exit();
     }	
	// |||add record|||
	public function add_training() {
			
		$validation =  \Config\Services::validation();
		$session = \Config\Services::session();
		$request = \Config\Services::request();
		$usession = $session->get('sup_username');	
		if ($this->request->getPost('type') === 'add_record') {
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = csrf_hash();
			// set rules
			$rules = [
				'trainer' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text'),
					]
				],
				'training_type' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text'),
					]
				],
				'start_date' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text'),
					]
				],
				'end_date' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text'),
					]
				]
			];
			if(!$this->validate($rules)){
				$ruleErrors = [
                    "trainer" => $validation->getError('trainer'),
					"training_type" => $validation->getError('training_type'),
					"start_date" => $validation->getError('start_date'),
					"end_date" => $validation->getError('end_date'),
					//"trainer" => $validation->getError('trainer'),
                ];
				foreach($ruleErrors as $err){
					$Return['error'] = $err;
					if($Return['error']!=''){
						return $this->response->setJSON($Return);
					}
				}
			} else {
				$trainer = $this->request->getPost('trainer',FILTER_SANITIZE_STRING);
				$training_type = $this->request->getPost('training_type',FILTER_SANITIZE_STRING);
				$training_cost = $this->request->getPost('training_cost',FILTER_SANITIZE_STRING);
				$description = $this->request->getPost('description',FILTER_SANITIZE_STRING);
				$start_date = $this->request->getPost('start_date',FILTER_SANITIZE_STRING);
				$end_date = $this->request->getPost('end_date',FILTER_SANITIZE_STRING);
				$UsersModel = new UsersModel();
				$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
				if($user_info['user_type'] == 'staff'){
					$staff_id = $usession['sup_user_id'];
					$company_id = $user_info['company_id'];
				} else {
					$employee_ids = implode(',',$this->request->getPost('employee_id',FILTER_SANITIZE_STRING));
					$staff_id = $employee_ids;
					$company_id = $usession['sup_user_id'];
				}
				$data = [
					'company_id'  => $company_id,
					'employee_id' => $staff_id,
					'training_type_id'  => $training_type,
					'trainer_id'  => $trainer,
					'start_date'  => $start_date,
					'finish_date'  => $end_date,
					'training_cost'  => $training_cost,
					'description'  => $description,
					'training_status'  => 1,
					'performance'  => 0,
					'remarks'  => '',
					'created_at' => date('d-m-Y h:i:s')
				];
				$TrainingModel = new TrainingModel();
				$result = $TrainingModel->insert($data);	
				$Return['csrf_hash'] = csrf_hash();	
				if ($result == TRUE) {
					$Return['result'] = lang('Success.ci_training_added_msg');
				} else {
					$Return['error'] = lang('Main.xin_error_msg');
				}
				return $this->response->setJSON($Return);

			}
		} else {

			$Return['error'] = lang('Main.xin_error_msg');
			return $this->response->setJSON($Return);
		}
	}
	// |||edit record|||
	public function update_training()
	{
		$validation = \Config\Services::validation();
		$session = \Config\Services::session();
		$request = \Config\Services::request();
		$usession = $session->get('sup_username');
		
		// Initialize response
		$Return = [
			'result' => '',
			'error' => '',
			'csrf_hash' => csrf_hash(),
			'redirect' => site_url('erp/training-list') // Default redirect URL
		];

		// Validate request method
		if (!$request->isAJAX() || !$request->is('post')) {
			$Return['error'] = lang('Main.xin_invalid_request');
			return $this->response->setJSON($Return);
		}

		// Set validation rules
		$rules = [
			'trainer' => [
				'rules' => 'required',
				'errors' => ['required' => lang('Main.xin_error_field_text')]
			],
			'training_type' => [
				'rules' => 'required',
				'errors' => ['required' => lang('Main.xin_error_field_text')]
			],
			'start_date' => [
				'rules' => 'required',
				'errors' => ['required' => lang('Main.xin_error_field_text')]
			],
			'end_date' => [
				'rules' => 'required',
				'errors' => ['required' => lang('Main.xin_error_field_text')]
			]
		];

		// Run validation
		if (!$this->validate($rules)) {
			$errors = $validation->getErrors();
			$Return['error'] = implode('<br>', $errors);
			return $this->response->setJSON($Return);
		}

		try {
			// Get form data
			$id = $request->getPost('token', FILTER_SANITIZE_NUMBER_INT);
			$data = [
				'training_type_id' => $request->getPost('training_type', FILTER_SANITIZE_NUMBER_INT),
				'trainer_id' => $request->getPost('trainer', FILTER_SANITIZE_NUMBER_INT),
				'start_date' => $request->getPost('start_date'),
				'finish_date' => $request->getPost('end_date'),
				'training_cost' => $request->getPost('training_cost', FILTER_SANITIZE_NUMBER_FLOAT),
				'description' => $request->getPost('description', FILTER_SANITIZE_STRING),
				'associated_goals' => implode(',', (array)$request->getPost('associated_goals'))
			];

			// Handle employee assignment based on user type
			$UsersModel = new UsersModel();
			$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
			
			if ($user_info['user_type'] == 'staff') {
				$data['employee_id'] = $usession['sup_user_id'];
			} else {
				$employee_ids = (array)$request->getPost('employee_id');
				$data['employee_id'] = implode(',', array_map('intval', $employee_ids));
			}

			// Update training record
			$TrainingModel = new TrainingModel();
			$result = $TrainingModel->update($id, $data);

			if ($result) {
				$Return['result'] = lang('Success.ci_training_updated_msg');
				
				// You could conditionally set different redirect URLs here if needed
				// $Return['redirect'] = site_url('erp/specific-page');
			} else {
				$Return['error'] = lang('Main.xin_error_msg');
			}

		} catch (\Exception $e) {
			log_message('error', 'Training update failed: ' . $e->getMessage());
			$Return['error'] = lang('Main.xin_error_msg');
		}

		return $this->response->setJSON($Return);
	}
	// |||update record|||
	public function update_training_status() {
			
		$validation =  \Config\Services::validation();
		$session = \Config\Services::session();
		$request = \Config\Services::request();
		$usession = $session->get('sup_username');	
		if ($this->request->getPost('type') === 'edit_record') {
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = csrf_hash();
			// set rules
			$rules = [
				'remarks' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text')
					]
				]
			];
			if(!$this->validate($rules)){
				$ruleErrors = [
                    "remarks" => $validation->getError('remarks')
                ];
				foreach($ruleErrors as $err){
					$Return['error'] = $err;
					if($Return['error']!=''){
						return $this->response->setJSON($Return);
					}
				}
			} else {
				$performance = $this->request->getPost('performance',FILTER_SANITIZE_STRING);	
				$status = $this->request->getPost('status',FILTER_SANITIZE_STRING);
				$remarks = $this->request->getPost('remarks',FILTER_SANITIZE_STRING);
				$id = $this->request->getPost('token',FILTER_SANITIZE_STRING);
				$data = [
					'training_status'  => $status,
					'performance' => $performance,
					'remarks'  => $remarks
				];
				$TrainingModel = new TrainingModel();
				$result = $TrainingModel->update($id, $data);
				$Return['csrf_hash'] = csrf_hash();	
				if ($result == TRUE) {
					$Return['result'] = lang('Success.ci_training_status_updated_msg');
				} else {
					$Return['error'] = lang('Main.xin_error_msg');
				}
				return $this->response->setJSON($Return);
			}
		} else {
			$Return['error'] = lang('Main.xin_error_msg');
			return $this->response->setJSON($Return);
		}
	}
	// |||update record|||
	public function add_note() {
			
		$validation =  \Config\Services::validation();
		$session = \Config\Services::session();
		$request = \Config\Services::request();
		$usession = $session->get('sup_username');	
		if ($this->request->getPost('type') === 'add_record') {
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$Return['csrf_hash'] = csrf_hash();
			// set rules
			$rules = [
				'description' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Success.xin_note_field_error')
					]
				]
			];
			if(!$this->validate($rules)){
				$ruleErrors = [
                    "description" => $validation->getError('description')
                ];
				foreach($ruleErrors as $err){
					$Return['error'] = $err;
					if($Return['error']!=''){
						return $this->response->setJSON($Return);
					}
				}
			} else {
				$UsersModel = new UsersModel();
				$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
				if($user_info['user_type'] == 'staff'){
					$company_id = $user_info['company_id'];
				} else {
					$company_id = $usession['sup_user_id'];
				}
				$description = $this->request->getPost('description',FILTER_SANITIZE_STRING);
				$id = $this->request->getPost('token',FILTER_SANITIZE_STRING);
				$data = [
					'company_id' => $company_id,
					'training_id' => $id,
					'employee_id'  => $usession['sup_user_id'],
					'training_note'  => $description,
					'created_at' => date('d-m-Y h:i:s')
				];
				$TrainingnotesModel = new TrainingnotesModel();
				$result = $TrainingnotesModel->insert($data);	
				$Return['csrf_hash'] = csrf_hash();	
				if ($result == TRUE) {
					$Return['result'] = lang('Success.ci_training_note_added_msg');
				} else {
					$Return['error'] = lang('Main.xin_error_msg');
				}
				
				return $this->response->setJSON($Return);
			}
		} else {
			$Return['error'] = lang('Main.xin_error_msg');
			return $this->response->setJSON($Return);
		}
	}
	// read record
	public function read_training()
	{
		$session = \Config\Services::session();
		$request = \Config\Services::request();
		if(!$session->has('sup_username')){ 
			return redirect()->to(site_url('/'));
		}
		$id = $request->getGet('field_id');
		$data = [
				'field_id' => $id,
			];
		if($session->has('sup_username')){
			return view('erp/training/dialog_training', $data);
		} else {
			return redirect()->to(site_url('/'));
		}
	}
	// delete record
	public function delete_training() {
		
		if($this->request->getPost('type')=='delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$session = \Config\Services::session();
			$request = \Config\Services::request();
			$usession = $session->get('sup_username');
			$id = $this->request->getPost('_token',FILTER_SANITIZE_STRING);
			$Return['csrf_hash'] = csrf_hash();
			$TrainingModel = new TrainingModel();
			$result = $TrainingModel->where('training_id', $id)->delete($id);
			if ($result == TRUE) {
				$Return['result'] = lang('Success.ci_training_deleted_msg');
			} else {
				$Return['error'] = lang('Main.xin_error_msg');
			}
			return $this->response->setJSON($Return);
		}
	}
	// delete record
	public function delete_training_note() {
		
		if($this->request->getVar('field_id')) {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
			$session = \Config\Services::session();
			$request = \Config\Services::request();
			$usession = $session->get('sup_username');
			$id = $this->request->getVar('field_id',FILTER_SANITIZE_STRING);
			$Return['csrf_hash'] = csrf_hash();
			$TrainingnotesModel = new TrainingnotesModel();
			$result = $TrainingnotesModel->where('training_note_id', $id)->delete($id);
			if ($result == TRUE) {
				$Return['result'] = lang('Success.ci_training_note_deleted_msg');
			} else {
				$Return['error'] = lang('Main.xin_error_msg');
			}
			return $this->response->setJSON($Return);
		}
	}
}
