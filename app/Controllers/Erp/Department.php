<?php
namespace App\Controllers\Erp;
use App\Controllers\BaseController;
 
use App\Models\SystemModel;
use App\Models\RolesModel;
use App\Models\UsersModel;
use App\Models\DepartmentModel;
use App\Models\StaffdetailsModel;
 
class Department extends BaseController {
   
    public function corehr_dashboard()
    {      
        $RolesModel = new RolesModel();
        $UsersModel = new UsersModel();
        $SystemModel = new SystemModel();
        $request = \Config\Services::request();
        $session = \Config\Services::session();
       
        $usession = $session->get('sup_username');
        $xin_system = $SystemModel->where('setting_id', 1)->first();
        $data['title'] = lang('Dashboard.left_department').' | '.$xin_system['application_name'];
        $data['path_url'] = 'departments';
        $data['breadcrumbs'] = lang('Dashboard.left_department');
 
        $data['subview'] = view('erp/department/corehr_dashboard', $data);
        return view('erp/layout/layout_main', $data); //page load
    }
    public function index()
    {      
        $UsersModel = new UsersModel();
        $SystemModel = new SystemModel();
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
            if(!in_array('department1',staff_role_resource())) {
                $session->setFlashdata('unauthorized_module',lang('Dashboard.xin_error_unauthorized_module'));
                return redirect()->to(site_url('erp/desk'));
            }
        }
        $data['title'] = lang('Dashboard.left_department').' | '.$xin_system['application_name'];
        $data['path_url'] = 'departments';
        $data['breadcrumbs'] = lang('Dashboard.left_department');
 
        $data['subview'] = view('erp/department/staff_department', $data);
        return view('erp/layout/layout_main', $data); //page load
    }
    // record list
    public function departments_list() {
        $session = \Config\Services::session();
        $usession = $session->get('sup_username');
   
        // Handle missing session
        if (!$session->has('sup_username')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['data' => []]);
            } else {
                return redirect()->to(site_url('/'));
            }
        }
   
        $UsersModel = new UsersModel();
        $DepartmentModel = new DepartmentModel();
   
        $user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
   
        // Get department list based on user type
        if ($user_info['user_type'] == 'staff') {
            $get_data = $DepartmentModel->where('company_id', $user_info['company_id'])->orderBy('department_id', 'ASC')->findAll();
        } else {
            $get_data = $DepartmentModel->where('company_id', $usession['sup_user_id'])->orderBy('department_id', 'ASC')->findAll();
        }
   
        $data = [];
   
        foreach ($get_data as $r) {
            // Edit button
            $edit = '';
            if (in_array('department3', staff_role_resource()) || $user_info['user_type'] == 'company') {
                $edit = '<span data-toggle="tooltip" title="' . lang('Main.xin_edit') . '">
                            <button type="button" class="btn icon-btn btn-sm btn-light-primary" data-toggle="modal" data-target=".view-modal-data" data-field_id="' . uencode($r['department_id']) . '">
                              <i class="feather icon-edit"></i>
                            </button>
                        </span>';
            }
   
            // Delete button
            $delete = '';
            if (in_array('department4', staff_role_resource()) || $user_info['user_type'] == 'company') {
                $delete = '<span data-toggle="tooltip" title="' . lang('Main.xin_delete') . '">
                            <button type="button" class="btn icon-btn btn-sm btn-light-danger delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . uencode($r['department_id']) . '">
                              <i class="feather icon-trash-2"></i>
                            </button>
                        </span>';
            }
   
            // Department head name
            $d_head = $UsersModel->where('user_id', $r['department_head'])->first();
            $name = $d_head ? $d_head['first_name'] . ' ' . $d_head['last_name'] : '-';
   
            // Date formatting
            $created_at = set_date_format($r['created_at']);
   
            // Department name
            $department_name = $r['department_name'];
            $combhr = $edit . $delete;
   
            $data[] = [
                esc($department_name),
                esc($name),
                esc($created_at),
                $combhr
            ];
        }
   
        return $this->response->setHeader('Content-Type', 'application/json')->setJSON([
            'data' => $data
        ]);
    }
   
    // |||add record|||
    public function add_department() {
           
        $validation =  \Config\Services::validation();
        $session = \Config\Services::session();
        $request = \Config\Services::request();
        $usession = $session->get('sup_username');  
        if ($this->request->getPost('type') === 'add_record') {
            $Return = ['result'=>'', 'error'=>'', 'csrf_hash'=>''];
            $Return['csrf_hash'] = csrf_hash();
            // set rules
            $rules = [
                'department_name' => [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => lang('Main.xin_error_field_text')
                    ]
                ]
            ];
            if(!$this->validate($rules)){
                $ruleErrors = [
                    "department_name" => $validation->getError('department_name')
                ];
                foreach($ruleErrors as $err){
                    $Return['error'] = $err;
                    if($Return['error']!=''){
                        return $this->response->setJSON($Return);
                    }
                }
            } else {
                $department_name = $this->request->getPost('department_name',FILTER_SANITIZE_STRING);          
                $UsersModel = new UsersModel();
                $user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
                if($user_info['user_type'] == 'staff'){
                    $staff_id = 0;
                    $company_id = $user_info['company_id'];
                } else {
                    $staff_id = $this->request->getPost('employee_id',FILTER_SANITIZE_STRING);
                    $company_id = $usession['sup_user_id'];
                }
                if(empty($staff_id)){
                    $staff_id = 0;
                }
                $data = [
                    'company_id'  => $company_id,
                    'department_name' => $department_name,
                    'department_head'  => $staff_id,
                    'added_by'  => $company_id,
                    'created_at' => date('d-m-Y h:i:s')
                ];
                $DepartmentModel = new DepartmentModel();
                $result = $DepartmentModel->insert($data);  
                $Return['csrf_hash'] = csrf_hash();
                if ($result == TRUE) {
                    $Return['result'] = lang('Success.ci_department_added_msg');
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
    // |||update record|||
    public function update_department() {
           
        $validation =  \Config\Services::validation();
        $session = \Config\Services::session();
        $request = \Config\Services::request();
        $usession = $session->get('sup_username');  
        if ($this->request->getPost('type') === 'edit_record') {
            $Return = ['result'=>'', 'error'=>'', 'csrf_hash'=>''];
            $Return['csrf_hash'] = csrf_hash();
            // set rules
            $rules = [
                'department_name' => [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => lang('Main.xin_error_field_text')
                    ]
                ]
            ];
            if(!$this->validate($rules)){
                $ruleErrors = [
                    "department_name" => $validation->getError('department_name')
                ];
                foreach($ruleErrors as $err){
                    $Return['error'] = $err;
                    if($Return['error']!=''){
                        return $this->response->setJSON($Return);
                    }
                }
            } else {
                $department_name = $this->request->getPost('department_name',FILTER_SANITIZE_STRING);          
                $UsersModel = new UsersModel();
                $user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
                if($user_info['user_type'] == 'staff'){
                    $staff_id = 0;
                    $company_id = $user_info['company_id'];
                } else {
                    $staff_id = $this->request->getPost('employee_id',FILTER_SANITIZE_STRING);
                    $company_id = $usession['sup_user_id'];
                }
                if(empty($staff_id)){
                    $staff_id = 0;
                }
                $id = udecode($this->request->getPost('token',FILTER_SANITIZE_STRING));
                $data = [
                    'company_id'  => $company_id,
                    'department_name' => $department_name,
                    'department_head'  => $staff_id
                ];
                $DepartmentModel = new DepartmentModel();
                $result = $DepartmentModel->update($id, $data);
                $Return['csrf_hash'] = csrf_hash();
                if ($result == TRUE) {
                    $Return['result'] = lang('Success.ci_department_updated_msg');
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
    // read record
    public function read_department()
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
            return view('erp/department/dialog_department', $data);
        } else {
            return redirect()->to(site_url('/'));
        }
    }
    public function department_wise_chart()
    {
        $session = \Config\Services::session();
        $usession = $session->get('sup_username');
        if (!$session->has('sup_username')) {
            return redirect()->to(site_url('/'));
        }
 
        $UsersModel = new UsersModel();
        $DepartmentModel = new DepartmentModel();
        $StaffdetailsModel = new StaffdetailsModel();
        $user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
 
        if ($user_info['user_type'] == 'staff') {
            $get_data = $DepartmentModel->where('company_id', $user_info['company_id'])->orderBy('department_id', 'ASC')->findAll();
        } else {
            $get_data = $DepartmentModel->where('company_id', $usession['sup_user_id'])->orderBy('department_id', 'ASC')->findAll();
        }
 
        $data = array();
        $Return = array('iseries' => '', 'ilabels' => '');
        $title_info = array();
        $series_info = array();
        foreach ($get_data as $r) {
            $dep_info = $StaffdetailsModel->where('department_id', $r['department_id'])->first();
            $dep_count = $StaffdetailsModel->where('department_id', $r['department_id'])->countAllResults();
            if ($dep_count > 0) {
                $title_info[] = $r['department_name'];
                $series_info[] = $dep_count;
            }
        }
 
        $Return['iseries'] = $series_info;
        $Return['ilabels'] = $title_info;
        $Return['total_label'] = lang('Main.xin_total');
        return $this->response->setJSON($Return);
    }
 
    // delete record
    public function delete_department() {
       
        if($this->request->getPost('type')=='delete_record') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
            $session = \Config\Services::session();
            $request = \Config\Services::request();
            $usession = $session->get('sup_username');
            $id = udecode($this->request->getPost('_token',FILTER_SANITIZE_STRING));
            $Return['csrf_hash'] = csrf_hash();
            $DepartmentModel = new DepartmentModel();
            $result = $DepartmentModel->where('department_id', $id)->delete($id);
            if ($result == TRUE) {
                $Return['result'] = lang('Success.ci_department_deleted_msg');
            } else {
                $Return['error'] = lang('Main.xin_error_msg');
            }
            return $this->response->setJSON($Return);
        }
    }
}
 
 