<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;


use App\Models\RolesModel;
use App\Models\UsersModel;
use App\Models\AwardsModel;
use App\Models\SystemModel;
use App\Models\ConstantsModel;
use App\Models\EmailtemplatesModel;

class Awards extends BaseController
{

	public function index()
	{
		$RolesModel = new RolesModel();
		$UsersModel = new UsersModel();
		$SystemModel = new SystemModel();
		//$AssetsModel = new AssetsModel();
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
			if (!in_array('award1', staff_role_resource())) {
				$session->setFlashdata('unauthorized_module', lang('Dashboard.xin_error_unauthorized_module'));
				return redirect()->to(site_url('erp/desk'));
			}
		}
		$xin_system = $SystemModel->where('setting_id', 1)->first();
		$data['title'] = lang('Dashboard.left_awards') . ' | ' . $xin_system['application_name'];
		$data['path_url'] = 'awards';
		$data['breadcrumbs'] = lang('Dashboard.left_awards');

		$data['subview'] = view('erp/awards/key_awards', $data);
		return view('erp/layout/layout_main', $data); //page load
	}
	public function award_view()
	{
		$RolesModel = new RolesModel();
		$UsersModel = new UsersModel();
		$SystemModel = new SystemModel();
		//$AssetsModel = new AssetsModel();
		$session = \Config\Services::session();
		$usession = $session->get('sup_username');
		$AwardsModel = new AwardsModel();
		$request = \Config\Services::request();
		$ifield_id = udecode($request->getUri()->getSegment(3));
		$isegment_val = $AwardsModel->where('award_id', $ifield_id)->first();

		$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
		if (!$session->has('sup_username')) {
			$session->setFlashdata('err_not_logged_in', lang('Dashboard.err_not_logged_in'));
			return redirect()->to(site_url('/'));
		}

		$xin_system = $SystemModel->where('setting_id', 1)->first();
		$data['title'] = lang('Employees.xin_view_award') . ' | ' . $xin_system['application_name'];
		$data['path_url'] = 'award_details';
		$data['breadcrumbs'] = lang('Employees.xin_view_award');

		$data['subview'] = view('erp/awards/key_award_view', $data);
		return view('erp/layout/layout_main', $data); //page load
	}
	// |||add record|||
	public function add_awards()
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
				'award_type_id' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Success.xin_award_type_field_error')
					]
				],
				'award_date' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text')
					]
				],
				'month_year' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text')
					]
				],
				'cash' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text')
					]
				],
				'award_information' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text')
					]
				],
				'award_picture' => [
					'rules'  => 'uploaded[award_picture]|mime_in[award_picture,image/jpg,image/jpeg,image/gif,image/png]|max_size[award_picture,3072]',
					'errors' => [
						'uploaded' => lang('Main.xin_error_field_text'),
						'mime_in' => 'wrong size'
					]
				]
			];
			if (!$this->validate($rules)) {
				$ruleErrors = [
					"award_type_id" => $validation->getError('award_type_id'),
					"award_date" => $validation->getError('award_date'),
					"month_year" => $validation->getError('month_year'),
					"cash" => $validation->getError('cash'),
					"award_information" => $validation->getError('award_information'),
					"award_picture" => $validation->getError('award_picture')
				];
				foreach ($ruleErrors as $err) {
					$Return['error'] = $err;
					if ($Return['error'] != '') {
						return $this->response->setJSON($Return);
					}
				}
			} else {
				// upload file
				$award_picture = $this->request->getFile('award_picture');
				$file_name = $award_picture->getName();
				$award_picture->move('uploads/awards/');

				$award_type_id = $this->request->getPost('award_type_id', FILTER_SANITIZE_STRING);
				$award_date = $this->request->getPost('award_date', FILTER_SANITIZE_STRING);
				$gift = $this->request->getPost('gift', FILTER_SANITIZE_STRING);
				$cash = $this->request->getPost('cash', FILTER_SANITIZE_STRING);
				$month_year = $this->request->getPost('month_year', FILTER_SANITIZE_STRING);
				$description = $this->request->getPost('description', FILTER_SANITIZE_STRING);
				$award_information = $this->request->getPost('award_information', FILTER_SANITIZE_STRING);

				$UsersModel = new UsersModel();
				$SystemModel = new SystemModel();
				$ConstantsModel = new ConstantsModel();
				$EmailtemplatesModel = new EmailtemplatesModel();
				$xin_system = $SystemModel->where('setting_id', 1)->first();
				$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
				if ($user_info['user_type'] == 'staff') {
					$staff_id = $usession['sup_user_id'];
					$company_id = $user_info['company_id'];
					$company_info = $UsersModel->where('company_id', $company_id)->first();
				} else {
					$staff_id = $this->request->getPost('employee_id', FILTER_SANITIZE_STRING);
					$company_id = $usession['sup_user_id'];
					$company_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
				}

				$data = [
					'company_id' => $company_id,
					'employee_id'  => $staff_id,
					'award_type_id'  => $award_type_id,
					'gift_item'  => $gift,
					'cash_price'  => $cash,
					'award_photo'  => $file_name,
					'award_month_year'  => $month_year,
					'award_information'  => $award_information,
					'description'  => $description,
					'created_at' => $award_date
				];
				$AwardsModel = new AwardsModel();
				$result = $AwardsModel->insert($data);
				$Return['csrf_hash'] = csrf_hash();
				if ($result == TRUE) {
					$Return['result'] = lang('Success.ci_award_added_msg');
					if ($xin_system['enable_email_notification'] == 1) {
						// Send mail start
						$itemplate = $EmailtemplatesModel->where('template_id', 11)->first();
						$istaff_info = $UsersModel->where('user_id', $staff_id)->first();
						// award type
						$category_info = $ConstantsModel->where('constants_id', $award_type_id)->where('type', 'award_type')->first();
						$category_name = $category_info['category_name'];
						$isubject = $itemplate['subject'];
						$ibody = html_entity_decode($itemplate['message']);
						$fbody = str_replace(array("{site_name}", "{award_name}"), array($company_info['company_name'], $category_name), $ibody);
						timehrm_mail_data($company_info['email'], $company_info['company_name'], $istaff_info['email'], $isubject, $fbody);
						// Send mail end
					}
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
	public function update_award()
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
				'award_type_id' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Success.xin_award_type_field_error')
					]
				],
				'award_date' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text')
					]
				],
				'month_year' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text')
					]
				],
				'cash' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text')
					]
				],
				'award_information' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text')
					]
				]
			];
			if (!$this->validate($rules)) {
				$ruleErrors = [
					"award_type_id" => $validation->getError('award_type_id'),
					"award_date" => $validation->getError('award_date'),
					"month_year" => $validation->getError('month_year'),
					"cash" => $validation->getError('cash'),
					"award_information" => $validation->getError('award_information')
				];
				foreach ($ruleErrors as $err) {
					$Return['error'] = $err;
					if ($Return['error'] != '') {
						return $this->response->setJSON($Return);
					}
				}
			} else {
				// upload file
				$validated = $this->validate([
					'award_picture' => [
						'rules'  => 'uploaded[award_picture]|mime_in[award_picture,image/jpg,image/jpeg,image/gif,image/png]|max_size[award_picture,3072]',
						'errors' => [
							'uploaded' => lang('Main.xin_error_field_text'),
							'mime_in' => 'wrong size'
						]
					]
				]);
				if ($validated) {
					$award_picture = $this->request->getFile('award_picture');
					$file_name = $award_picture->getName();
					$award_picture->move('uploads/awards/');
				}

				$award_type_id = $this->request->getPost('award_type_id', FILTER_SANITIZE_STRING);
				$award_date = $this->request->getPost('award_date', FILTER_SANITIZE_STRING);
				$gift = $this->request->getPost('gift', FILTER_SANITIZE_STRING);
				$cash = $this->request->getPost('cash', FILTER_SANITIZE_STRING);
				$month_year = $this->request->getPost('month_year', FILTER_SANITIZE_STRING);
				$description = $this->request->getPost('description', FILTER_SANITIZE_STRING);
				$award_information = $this->request->getPost('award_information', FILTER_SANITIZE_STRING);
				$associated_goals = implode(',', $this->request->getPost('associated_goals', FILTER_SANITIZE_STRING));
				$id = $this->request->getPost('token');

				if ($validated) {
					$data = [
						'award_type_id'  => $award_type_id,
						'gift_item'  => $gift,
						'cash_price'  => $cash,
						'award_photo'  => $file_name,
						'award_month_year'  => $month_year,
						'award_information'  => $award_information,
						'created_at'  => $award_date,
						'description'  => $description,
						'associated_goals'  => $associated_goals
					];
				} else {
					$data = [
						'award_type_id'  => $award_type_id,
						'gift_item'  => $gift,
						'cash_price'  => $cash,
						'award_month_year'  => $month_year,
						'award_information'  => $award_information,
						'created_at'  => $award_date,
						'description'  => $description,
						'associated_goals'  => $associated_goals
					];
				}
				$AwardsModel = new AwardsModel();
				$result = $AwardsModel->update($id, $data);
				$Return['csrf_hash'] = csrf_hash();
				if ($result == TRUE) {
					$Return['result'] = lang('Success.ci_award_updated_msg');
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
	public function awards_list()
	{

		$session = \Config\Services::session();
		$usession = $session->get('sup_username');
		if (!$session->has('sup_username')) {
			return redirect()->to(site_url('/'));
		}
		$RolesModel = new RolesModel();
		$UsersModel = new UsersModel();
		$SystemModel = new SystemModel();
		$AwardsModel = new AwardsModel();
		$ConstantsModel = new ConstantsModel();
		$xin_system = erp_company_settings();
		$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
		if ($user_info['user_type'] == 'staff') {
			$get_data = $AwardsModel->where('employee_id', $user_info['user_id'])->orderBy('award_id', 'ASC')->findAll();
		} else {
			$get_data = $AwardsModel->where('company_id', $usession['sup_user_id'])->orderBy('award_id', 'ASC')->findAll();
		}
		$data = array();

		foreach ($get_data as $r) {

			if (in_array('award4', staff_role_resource()) || $user_info['user_type'] == 'company') { //delete
				$delete = '<span data-toggle="tooltip" data-placement="top" data-state="danger" title="' . lang('Main.xin_delete') . '"><button type="button" class="btn icon-btn btn-sm btn-light-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . uencode($r['award_id']) . '"><i class="feather icon-trash-2"></i></button></span>';
			} else {
				$delete = '';
			}
			$view = '<span data-toggle="tooltip" data-placement="top" data-state="primary" title="' . lang('Main.xin_view_details') . '"><a href="' . site_url() . 'erp/award-view/' . $r['award_id'] . '"><button type="button" class="btn icon-btn btn-sm btn-light-primary waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span>';
			// awards month year
			$d = explode('-', $r['award_month_year']);
			$get_month = date('F', mktime(0, 0, 0, $d[1], 10));
			$award_date = $get_month . ', ' . $d[0];
			// user info
			$iuser_info = $UsersModel->where('user_id', $r['employee_id'])->first();
			// award type
			$category_info = $ConstantsModel->where('constants_id', $r['award_type_id'])->where('type', 'award_type')->first();
			$combhr = $view . $delete;
			// award photo
			$cname = $category_info['category_name'];
			// award cash
			$cash_price = number_to_currency($r['cash_price'], $xin_system['default_currency'], null, 2);
			$icname = '
				' . $cname . '
				<div class="overlay-edit">
					' . $combhr . '
				</div>';
			$data[] = array(
				$icname,
				$iuser_info['first_name'] . ' ' . $iuser_info['last_name'],
				$r['gift_item'],
				$cash_price,
				$award_date,
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
	public function read_award()
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
			return view('erp/awards/dialog_award', $data);
		} else {
			return redirect()->to(site_url('/'));
		}
	}
	// delete record
	public function delete_award()
	{

		if ($this->request->getPost('type') == 'delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$session = \Config\Services::session();
			$request = \Config\Services::request();
			$usession = $session->get('sup_username');
			$id = udecode($this->request->getPost('_token', FILTER_SANITIZE_STRING));
			$Return['csrf_hash'] = csrf_hash();
			$AwardsModel = new AwardsModel();
			$result = $AwardsModel->where('award_id', $id)->delete($id);
			if ($result == TRUE) {
				$Return['result'] = lang('Success.ci_award_deleted_msg');
			} else {
				$Return['error'] = lang('Main.xin_error_msg');
			}
			return $this->response->setJSON($Return);
		}
	}
}
