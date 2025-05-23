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
use App\Models\DepartmentModel;
use App\Models\AnnouncementModel;


class Announcements extends BaseController
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
			if (!in_array('news1', staff_role_resource())) {
				$session->setFlashdata('unauthorized_module', lang('Dashboard.xin_error_unauthorized_module'));
				return redirect()->to(site_url('erp/desk'));
			}
		}
		$xin_system = $SystemModel->where('setting_id', 1)->first();
		$data['title'] = lang('Dashboard.left_announcements') . ' | ' . $xin_system['application_name'];
		$data['path_url'] = 'announcements';
		$data['breadcrumbs'] = lang('Dashboard.left_announcements');

		$data['subview'] = view('erp/announcements/staff_announcements', $data);
		return view('erp/layout/layout_main', $data); //page load
	}
	public function announcement_view($id)
	{
		$RolesModel = new RolesModel();
		$UsersModel = new UsersModel();
		$SystemModel = new SystemModel();
		$request = \Config\Services::request();
		$session = \Config\Services::session();

		$usession = $session->get('sup_username');
		$AnnouncementModel = new AnnouncementModel();
		$ifield_id = udecode($id);
		$isegment_val = $AnnouncementModel->where('announcement_id', $ifield_id)->first();
		if (!$isegment_val) {
			$session->setFlashdata('unauthorized_module', lang('Dashboard.xin_error_unauthorized_module'));
			return redirect()->to(site_url('erp/desk'));
		}
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
			if (!in_array('news1', staff_role_resource())) {
				$session->setFlashdata('unauthorized_module', lang('Dashboard.xin_error_unauthorized_module'));
				return redirect()->to(site_url('erp/desk'));
			}
		}
		$xin_system = $SystemModel->where('setting_id', 1)->first();
		$data['title'] = lang('Main.xin_view_announcement') . ' | ' . $xin_system['application_name'];
		$data['path_url'] = 'announcements';
		$data['breadcrumbs'] = lang('Main.xin_view_announcement');

		$data['subview'] = view('erp/announcements/staff_announcement_view', $data);
		return view('erp/layout/layout_main', $data); //page load
	}
	// record list
	public function announcement_list()
	{

		$session = \Config\Services::session();
		$usession = $session->get('sup_username');
		if (!$session->has('sup_username')) {
			return redirect()->to(site_url('/'));
		}
		$RolesModel = new RolesModel();
		$UsersModel = new UsersModel();
		$SystemModel = new SystemModel();
		$DepartmentModel = new DepartmentModel();
		$AnnouncementModel = new AnnouncementModel();
		$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
		if ($user_info['user_type'] == 'staff') {
			$get_data = $AnnouncementModel->where('company_id', $user_info['company_id'])->orderBy('announcement_id', 'ASC')->findAll();
		} else {
			$get_data = $AnnouncementModel->where('company_id', $usession['sup_user_id'])->orderBy('announcement_id', 'ASC')->findAll();
		}
		$data = [];
		$i = 1;
		foreach ($get_data as $r) {

			/* get Employee info*/
			if ($r['department_id'] == '') {
				$ol = '--';
			} else {
				$ol = '';
				foreach (explode(',', $r['department_id']) as $uid) {
					$department = $DepartmentModel->where('department_id', $uid)->first();
					if ($department) {
						$ol .= $i . ': ' . $department['department_name'] . '<br>';
					} else {
						$ol .= '';
					}
				}
				$ol .= '';
			}
			if (in_array('news3', staff_role_resource()) || $user_info['user_type'] == 'company') { //edit
				$edit = '<span data-toggle="tooltip" data-placement="top" data-state="primary" title="' . lang('Main.xin_edit') . '"><button type="button" class="btn icon-btn btn-sm btn-light-primary waves-effect waves-light" data-toggle="modal" data-target=".edit-modal-data" data-field_id="' . uencode($r['announcement_id']) . '"><i class="feather icon-edit"></i></button></span>';
			} else {
				$edit = '';
			}
			if (in_array('news4', staff_role_resource()) || $user_info['user_type'] == 'company') { //delete
				$delete = '<span data-toggle="tooltip" data-placement="top" data-state="danger" title="' . lang('Main.xin_delete') . '"><button type="button" class="btn icon-btn btn-sm btn-light-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . uencode($r['announcement_id']) . '"><i class="feather icon-trash-2"></i></button></span>';
			} else {
				$delete = '';
			}
			$view = '<span data-toggle="tooltip" data-placement="top" data-state="primary" title="' . lang('Main.xin_view_details') . '"><a href="' . site_url() . 'erp/announcement-view/' . uencode($r['announcement_id']) . '"><button type="button" class="btn icon-btn btn-sm btn-light-primary waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span>';
			$start_date = set_date_format($r['start_date']);
			$end_date = set_date_format($r['end_date']);
			$ititle = $r['title'];
			$combhr = $view . $edit . $delete;
			$atitle = '
			' . $ititle . '
			<div class="overlay-edit">
				' . $combhr . '
			</div>';
			$data[] = array(
				$atitle,
				$ol,
				$start_date,
				$end_date
			);
			$i++;
		}
		$output = [
			//"draw" => $draw,
			"data" => $data
		];
		echo json_encode($output);
		exit();
	}
	// |||add record|||
	public function add_announcement()
	{
		$validation =  \Config\Services::validation();
		$session = \Config\Services::session();
		$request = \Config\Services::request();
		$usession = $session->get('sup_username');
		if ($this->request->getPost('type') === 'add_record') {
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = csrf_hash();
			// set rules
			$rules = [
				'title' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text')
					]
				],
				'start_date' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text')
					]
				],
				'end_date' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text')
					]
				],
				'summary' => [
					'rules'  => 'required',
					'errors' => [
						'required' => lang('Main.xin_error_field_text')
					]
				]
			];
			if (!$this->validate($rules)) {
				$ruleErrors = [
					"title" => $validation->getError('title'),
					"start_date" => $validation->getError('start_date'),
					"end_date" => $validation->getError('end_date'),
					"summary" => $validation->getError('summary')
				];
				foreach ($ruleErrors as $err) {
					$Return['error'] = $err;
					if ($Return['error'] != '') {
						$this->output($Return);
					}
				}
			} else {
				$title = $this->request->getPost('title', FILTER_SANITIZE_STRING);
				$start_date = $this->request->getPost('start_date', FILTER_SANITIZE_STRING);
				$end_date = $this->request->getPost('end_date', FILTER_SANITIZE_STRING);
				$summary = $this->request->getPost('summary', FILTER_SANITIZE_STRING);
				$description = $this->request->getPost('description', FILTER_SANITIZE_STRING);
				$dep_id = $this->request->getPost('department_id', FILTER_SANITIZE_STRING);
				$department_ids = implode(',', $dep_id);
				$department_id = $department_ids;

				$UsersModel = new UsersModel();
				$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
				if ($user_info['user_type'] == 'staff') {
					$company_id = $user_info['company_id'];
				} else {
					$company_id = $usession['sup_user_id'];
				}
				$data = [
					'company_id'  => $company_id,
					'department_id' => $department_id,
					'title'  => $title,
					'start_date'  => $start_date,
					'end_date'  => $end_date,
					'published_by'  => $usession['sup_user_id'],
					'summary'  => $summary,
					'description'  => $description,
					'is_active'  => 1,
					'created_at' => date('d-m-Y h:i:s')
				];
				$AnnouncementModel = new AnnouncementModel();
				$result = $AnnouncementModel->insert($data);
				$Return['csrf_hash'] = csrf_hash();
				if ($result == TRUE) {
					$Return['result'] = lang('Success.ci_news_added_msg');
				} else {
					$Return['error'] = lang('Main.xin_error_msg');
				}
				return $this->response->setJSON($Return);
				exit;
			}
		} else {
			$Return['error'] = lang('Main.xin_error_msg');
			return $this->response->setJSON($Return);
			exit;
		}
	}
	// |||edit record|||
	public function update_announcement()
	{
		$validation = \Config\Services::validation();
		$session = \Config\Services::session();
		$request = \Config\Services::request();
		$usession = $session->get('sup_username');

		if ($this->request->getPost()) {
			$Return = ['result' => '', 'error' => '', 'csrf_hash' => csrf_hash()];

			// Validation rules
			$rules = [
				'title' => 'required',
				'start_date' => 'required',
				'end_date' => 'required',
				'summary' => 'required',
				'department_id' => 'required',
				'token' => 'required'
			];

			// Validate input
			if (!$this->validate($rules)) {
				$Return['error'] = implode(' ', $validation->getErrors());
				return $this->response->setJSON($Return);
			}

			$title = $this->request->getPost('title', FILTER_SANITIZE_STRING);
			$start_date = $this->request->getPost('start_date', FILTER_SANITIZE_STRING);
			$end_date = $this->request->getPost('end_date', FILTER_SANITIZE_STRING);
			$summary = $this->request->getPost('summary', FILTER_SANITIZE_STRING);
			$description = $this->request->getPost('description', FILTER_SANITIZE_STRING);
			$dep_id = $this->request->getPost('department_id');
			$department_id = is_array($dep_id) ? implode(',', $dep_id) : '';
			$id = udecode($this->request->getPost('token', FILTER_SANITIZE_STRING));

			// Check if ID is valid
			if (!$id) {
				$Return['error'] = lang('Main.xin_error_invalid_id');
				return $this->response->setJSON($Return);
			}

			// Prepare data
			$data = [
				'department_id' => $department_id,
				'title' => $title,
				'start_date' => $start_date,
				'end_date' => $end_date,
				'summary' => $summary,
				'description' => $description,
				'is_active' => 1
			];

			// Update database
			$AnnouncementModel = new AnnouncementModel();
			$result = $AnnouncementModel->update($id, $data);
			if ($result) {
				$Return['result'] = lang('Success.ci_news_updated_msg');
			} else {
				$Return['error'] = lang('Main.xin_error_msg');
			}
			return $this->response->setJSON($Return);
		} else {
			$Return['error'] = lang('Main.xin_error_msg');
			return $this->response->setJSON($Return);
		}
	}
	// read record
	public function read_announcement()
	{
		$session = \Config\Services::session();
		$request = \Config\Services::request();
		if (!$session->has('sup_username')) {
			return redirect()->to(site_url('erp/login'));
		}
		$id = $request->getGet('field_id');
		$data = [
			'field_id' => $id,
		];
		if ($session->has('sup_username')) {
			return view('erp/announcements/dialog_announcement', $data);
		} else {
			return redirect()->to(site_url('/'));
		}
	}
	// delete record
	public function delete_announcement()
	{

		if ($this->request->getPost('type') == 'delete_record') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$session = \Config\Services::session();
			$request = \Config\Services::request();
			$usession = $session->get('sup_username');
			$id = udecode($this->request->getPost('_token', FILTER_SANITIZE_STRING));
			$Return['csrf_hash'] = csrf_hash();
			$AnnouncementModel = new AnnouncementModel();
			$result = $AnnouncementModel->where('announcement_id', $id)->delete($id);
			if ($result == TRUE) {
				$Return['result'] = lang('Success.ci_news_deleted_msg');
			} else {
				$Return['error'] = lang('Main.xin_error_msg');
			}
			return $this->response->setJSON($Return);
		}
	}
}
