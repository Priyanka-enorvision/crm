<?php

use App\Models\RolesModel;
use App\Models\UsersModel;
use App\Models\SystemModel;
//$encrypter = \Config\Services::encrypter();
$SystemModel = new SystemModel();
$RolesModel = new RolesModel();
$UsersModel = new UsersModel();

$session = \Config\Services::session();
$usession = $session->get('sup_username');
$request = \Config\Services::request();

$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<?php if (in_array('attendance', staff_role_resource()) || in_array('upattendance1', staff_role_resource()) || in_array('monthly_time', staff_role_resource()) || in_array('overtime_req1', staff_role_resource()) || $user_info['user_type'] == 'company') { ?>


  <hr class="border-light m-0 mb-3">
<?php } ?>
<div class="row m-b-1">
  <div class="col-md-4">
    <div class="card">
      <div class="card-header with-elements">
        <h5>
          <?= lang('Main.xin_filter'); ?>
          <?= lang('Dashboard.left_attendance'); ?>
        </h5>
      </div>
      <?php $attributes = array('name' => 'update_attendance_report', 'id' => 'xin-form', 'autocomplete' => 'off'); ?>
      <?php $hidden = array('user_id' => '1'); ?>
      <?php echo form_open('erp/update-attendance-list', $attributes, $hidden); ?>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="date">
                    <?= lang('Main.xin_e_details_date'); ?>
                  </label>
                  <div class="input-group">
                    <input class="form-control " placeholder="<?= lang('Main.xin_e_details_date'); ?>" id="attendance_date" name="attendance_date" type="date" value="<?php echo date('Y-m-d'); ?>">
                  </div>
                </div>
              </div>
            </div>
            <?php if ($user_info['user_type'] == 'company') { ?>
              <?php $staff_info = $UsersModel->where('company_id', $usession['sup_user_id'])->where('user_type', 'staff')->where('is_active', 1)->findAll(); ?>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="first_name">
                      <?= lang('Dashboard.dashboard_employee'); ?>
                    </label>
                    <select class="form-control" name="employee_id" id="employee_id" data-plugin="select_hrm" data-placeholder="<?= lang('Dashboard.dashboard_employee'); ?>">
                      <?php foreach ($staff_info as $staff) { ?>
                        <option value="<?= $staff['user_id'] ?>">
                          <?= $staff['first_name'] . ' ' . $staff['last_name'] ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="submit" class="btn btn-primary">
          <?= lang('Main.xin_filter'); ?>
        </button>
      </div>
      <?= form_close(); ?>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card user-profile-list mb-4">
      <div class="card-header">
        <h5>
          <?= lang('Main.xin_view'); ?>
          <?= lang('Dashboard.left_attendance'); ?>
        </h5>
        <?php if (in_array('upattendance2', staff_role_resource()) || $user_info['user_type'] == 'company') { ?>
          <div class="card-header-right">
            <button type="button" class="btn btn-sm btn-primary" id="add_attendance_btn" data-toggle="modal" data-target=".view-modal-data"><i data-feather="plus"></i>
              <?= lang('Main.xin_add_new'); ?>
            </button>
          </div>
        <?php } ?>
      </div>
      <div class="card-body">
        <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered" id="xin_table">
            <thead>
              <tr>
                <th><?= lang('Dashboard.dashboard_employee'); ?></th>
                <th><?= lang('Main.xin_e_details_date'); ?></th>
                <th><?= lang('Employees.xin_shift_in_time'); ?></th>
                <th><?= lang('Employees.xin_shift_out_time'); ?></th>
                <th><?= lang('Attendance.dashboard_total_work'); ?></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>