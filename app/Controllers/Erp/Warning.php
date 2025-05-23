<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;


use App\Models\RolesModel;
use App\Models\UsersModel;
use App\Models\SystemModel;
use App\Models\WarningModel;
use App\Models\ConstantsModel;

class Warning extends BaseController
{

	public function index()
	{
		$RolesModel = new RolesModel();
		$UsersModel = new UsersModel();
		$SystemModel = new SystemModel();
		$request = \Config\Services::request();
		$session = \Config\Services::session();

		$usession = $session->get('sup_username');
		$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
		if (!$session->has('sup_username')) {
			$session->setFlashdata('err_not_logged_in', lang('Dashboard.err_not_logged_in'));
			return redirect()->to(site_url('/'));
		}
		if ($user_info['user_type'] != 'company' && $user_info['user_type'] != 'staff') {
			$session->setFlashdata('unauthorized_module', lang('Dashboard.xin_error_unauthorized_module'));
			return redirect()->to(site_url('erp/desk'));
		}
		if ($user_info['user_type'] != 'company') {
			if (!in_array('disciplinary1', staff_role_resource())) {
				$session->setFlashdata('unauthorized_module', lang('Dashboard.xin_error_unauthorized_module'));
				return redirect()->to(site_url('erp/desk'));
			}
		}
		$xin_system = $SystemModel->where('setting_id', 1)->first();
		$data['title'] = lang('Dashboard.left_warnings') . ' | ' . $xin_system['application_name'];
		$data['path_url'] = 'warning';
		$data['breadcrumbs'] = lang('Dashboard.left_warnings');

		$data['subview'] = view('erp/disciplinary_cases/key_disciplinary_cases', $data);
		return view('erp/layout/layout_main', $data); //page load
	}
	// |||add record|||
	public function add_warning()
	{

		$validation =  \Config\Services::validation();
		$session = \Config\Services::session();
		$request = \Config\Services::request();
		$usession = $session->get('sup_username');
		if (!$session->has('sup_username')) {
			return redirect()->to(site_url('/'));
		}
		if ($this->request->getPost('type') === 'add_record') {
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = csrf_hash();
			// set rules
			$rules = [
				'warning_to' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Success.xin_employee_field_error')
					]
				],
				'warning_type' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Success.xin_case_type_field_error')
					]
				],
				'subject' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text')
					]
				],
				'warning_date' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Success.xin_case_date_field_error')
					]
				],
				'description' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text')
					]
				],
				'attachment' => [
					'rules'  => 'uploaded[attachment]|mime_in[attachment,image/jpg,image/jpeg,image/gif,image/png]|max_size[attachment,3072]',
					'errors' => [
						'uploaded' => lang('Main.xin_error_field_text'),
						'mime_in' => 'wrong size'
					]
				]
			];
			if (!$this->validate($rules)) {
				$ruleErrors = [
					"warning_to" => $validation->getError('warning_to'),
					"warning_type" => $validation->getError('warning_type'),
					"subject" => $validation->getError('subject'),
					"warning_date" => $validation->getError('warning_date'),
					"description" => $validation->getError('description'),
					"attachment" => $validation->getError('attachment')
				];
				foreach ($ruleErrors as $err) {
					$Return['error'] = $err;
					if ($Return['error'] != '') {
						return $this->response->setJSON($Return);
					}
				}
			} else {
				// upload file
				$attachment = $this->request->getFile('attachment');
				$file_name = $attachment->getName();
				$attachment->move('uploads/warning/');

				$warning_type = $this->request->getPost('warning_type', FILTER_SANITIZE_STRING);
				$subject = $this->request->getPost('subject', FILTER_SANITIZE_STRING);
				$warning_date = $this->request->getPost('warning_date', FILTER_SANITIZE_STRING);
				$description = $this->request->getPost('description', FILTER_SANITIZE_STRING);
				$warning_to = $this->request->getPost('warning_to', FILTER_SANITIZE_STRING);

				$UsersModel = new UsersModel();
				$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
				if ($user_info['user_type'] == 'staff') {
					$staff_id = $usession['sup_user_id'];
					$company_id = $user_info['company_id'];
				} else {
					$staff_id = $usession['sup_user_id'];
					$company_id = $usession['sup_user_id'];
				}

				$data = [
					'company_id' => $company_id,
					'warning_to'  => $warning_to,
					'warning_by'  => $staff_id,
					'warning_date'  => $warning_date,
					'warning_type_id'  => $warning_type,
					'attachment'  => $file_name,
					'subject'  => $subject,
					'description'  => $description,
					'created_at' => date('d-m-Y h:i:s')
				];
				$WarningModel = new WarningModel();
				$result = $WarningModel->insert($data);
				$Return['csrf_hash'] = csrf_hash();
				if ($result == TRUE) {
					$Return['result'] = lang('Success.ci_warning_added_msg');
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
	public function update_warning()
	{
		$validation =  \Config\Services::validation();
		$session = \Config\Services::session();
		$request = \Config\Services::request();
		$usession = $session->get('sup_username');
		if (!$session->has('sup_username')) {
			return redirect()->to(site_url('/'));
		}
		if ($this->request->getPost()) {
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = csrf_hash();
			// set rules
			$rules = [
				'subject' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text')
					]
				],
				'warning_date' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Success.xin_case_type_field_error')
					]
				],
				'description' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text')
					]
				]
			];
			if (!$this->validate($rules)) {
				$ruleErrors = [
					"subject" => $validation->getError('subject'),
					"warning_date" => $validation->getError('warning_date'),
					"description" => $validation->getError('description')
				];
				foreach ($ruleErrors as $err) {
					$Return['error'] = $err;
					if ($Return['error'] != '') {
						$this->output($Return);
					}
				}
			} else {
				$subject = $this->request->getPost('subject', FILTER_SANITIZE_STRING);
				$warning_date = $this->request->getPost('warning_date', FILTER_SANITIZE_STRING);
				$description = $this->request->getPost('description', FILTER_SANITIZE_STRING);
				$id = udecode($this->request->getPost('token', FILTER_SANITIZE_STRING));

				$data = [
					'warning_date'  => $warning_date,
					'subject'  => $subject,
					'description'  => $description
				];
				$WarningModel = new WarningModel();
				$result = $WarningModel->update($id, $data);
				$Return['csrf_hash'] = csrf_hash();
				if ($result == TRUE) {
					$Return['result'] = lang('Success.ci_warning_updated_msg');
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
	// record list
	public function warning_list()
	{
		$session = \Config\Services::session();
		$usession = $session->get('sup_username');
		if (!$session->has('sup_username')) {
			return redirect()->to(site_url('erp/login'));
		}
		$RolesModel = new RolesModel();
		$UsersModel = new UsersModel();
		$SystemModel = new SystemModel();
		$WarningModel = new WarningModel();
		$ConstantsModel = new ConstantsModel();
		$xin_system = $SystemModel->where('setting_id', 1)->first();
		$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
		if ($user_info['user_type'] == 'staff') {
			$get_data = $WarningModel->where('company_id',$user_info['company_id'])
			->groupStart()
			->where('warning_to', $user_info['user_id'])
			->orWhere('warning_by', $user_info['user_id'])
			->groupEnd()
			->orderBy('warning_id', 'ASC')->findAll();
		} else {
			$get_data = $WarningModel->where('company_id', $usession['sup_user_id'])->orderBy('warning_id', 'ASC')->findAll();
		}
		$data = array();

		foreach ($get_data as $r) {

			if (in_array('disciplinary3', staff_role_resource()) || $user_info['user_type'] == 'company') { //edit
				$edit = '<span data-toggle="tooltip" data-placement="top" data-state="primary" title="' . lang('Main.xin_edit') . '"><button type="button" class="btn icon-btn btn-sm btn-light-primary waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . uencode($r['warning_id']) . '"><i class="feather icon-edit"></i></button></span>';
			} else {
				$edit = '';
			}
			if (in_array('disciplinary5', staff_role_resource()) || $user_info['user_type'] == 'company') { //delete
				$delete = '<span data-toggle="tooltip" data-placement="top" data-state="danger" title="' . lang('Main.xin_delete') . '"><button type="button" class="btn icon-btn btn-sm btn-light-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . uencode($r['warning_id']) . '"><i class="feather icon-trash-2"></i></button></span>';
			} else {
				$delete = '';
			}
			$view = '<span data-toggle="tooltip" data-placement="top" data-state="primary" title="' . lang('Main.xin_view') . '"><button type="button" class="btn icon-btn btn-sm btn-light-success waves-effect waves-light" data-toggle="modal" data-target=".view-modal-data" data-field_id="' . uencode($r['warning_id']) . '"><span class="fa fa-eye"></span></button></span>';
			// warning type
			$category_info = $ConstantsModel->where('constants_id', $r['warning_type_id'])->where('type', 'warning_type')->first();
			// user info|||| warning tp
			$warning_to = $UsersModel->where('user_id', $r['warning_to'])->first();

			$name = $warning_to['first_name'] . ' ' . $warning_to['last_name'];
			$uname = '<div class="d-inline-block align-middle">
				<img src="' . base_url() . 'uploads/warning/' . $warning_to['profile_photo'] . '" alt="user image" class="img-radius align-top m-r-15" style="width:40px;">
				<div class="d-inline-block">
					<h6 class="m-b-0">' . $name . '</h6>
					<p class="m-b-0">' . $warning_to['email'] . '</p>
				</div>
			</div>';
			// get warning date
			$warning_date = set_date_format($r['warning_date']);
			// user info|||| warning by
			$warning_by = $UsersModel->where('user_id', $r['warning_by'])->first();
			$added_by = $warning_by['first_name'] . ' ' . $warning_by['last_name'];

			$combhr = $edit . $view . $delete;
			$wcname = '
				' . $uname . '
				<div class="overlay-edit">
					' . $combhr . '
				</div>';
			$data[] = array(
				$wcname,
				$category_info['category_name'],
				$warning_date,
				$r['subject'],
				$added_by,
			);
		}
		$output = array(
			//"draw" => $draw,
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}
	// read record
	public function read_warning()
	{
		$session = \Config\Services::session();
		$request = \Config\Services::request();
		if (!$session->has('sup_username')) {
			return redirect()->to(site_url('/'));
		}
		$id = $request->getGet('field_id');
		$data = [
			'field_id' => $id,
		];
		if ($session->has('sup_username')) {
			return view('erp/disciplinary_cases/dialog_warning', $data);
		} else {
			return redirect()->to(site_url('/'));
		}
	}
	// delete record
	public function delete_warning()
	{

		if ($this->request->getPost('type') == 'delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$session = \Config\Services::session();
			$request = \Config\Services::request();
			$usession = $session->get('sup_username');
			$id = udecode($this->request->getPost('_token', FILTER_SANITIZE_STRING));
			$Return['csrf_hash'] = csrf_hash();
			$WarningModel = new WarningModel();
			$result = $WarningModel->where('warning_id', $id)->delete($id);
			if ($result == TRUE) {
				$Return['result'] = lang('Success.ci_warning_deleted_msg');
			} else {
				$Return['error'] = lang('Main.xin_error_msg');
			}
			return $this->response->setJSON($Return);
		}
	}
}
