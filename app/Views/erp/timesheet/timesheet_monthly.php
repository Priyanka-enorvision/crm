<?php

use App\Models\SystemModel;
use App\Models\UsersModel;
use App\Models\LanguageModel;
use App\Models\DepartmentModel;

$SystemModel = new SystemModel();
$UsersModel = new UsersModel();
$LanguageModel = new LanguageModel();
$DepartmentModel = new DepartmentModel();

$session = \Config\Services::session();
$usession = $session->get('sup_username');
$router = service('router');
$xin_system = $SystemModel->where('setting_id', 1)->first();
$user = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
if ($user['user_type'] == 'staff') {
  $staff_info = $UsersModel->where('company_id', $user['company_id'])->where('user_type', 'staff')->findAll();
} else {
  $staff_info = $UsersModel->where('company_id', $usession['sup_user_id'])->where('user_type', 'staff')->findAll();
}
$locale = service('request')->getLocale();
?>
<?php if (in_array('attendance', staff_role_resource()) || in_array('upattendance1', staff_role_resource()) || in_array('monthly_time', staff_role_resource()) || in_array('overtime_req1', staff_role_resource()) || $user['user_type'] == 'company') { ?>


  <hr class="border-light m-0 mb-3">
<?php } ?>
<div class="row">
  <div class="col-sm-12">
    <div class="card mb-2">
      <div class="card-body">
        <?php $attributes = array('name' => 'monthly_attendance_report', 'id' => 'monthly_attendance', 'method' => 'GET', 'target' => '_blank'); ?>
        <?php $hidden = array('token' => uencode(date('Y-m'))); ?>
        <?php echo form_open('erp/monthly-attendance-view', $attributes, $hidden); ?>
        <div class="row justify-content-center">
          <div class="col-sm-12">
            <div class="row align-items-center">
              <div class="col">
                <label for="department">
                  <?= lang('Dashboard.dashboard_employee'); ?>
                </label>
                <?php if ($user['user_type'] == 'staff') { ?>
                  <select id="S" class="form-control" data-plugin="select_hrm" data-placeholder="<?= lang('Dashboard.dashboard_employee'); ?>" name="S">
                    <?php foreach ($staff_info as $_user) { ?>
                      <?php if ($usession['sup_user_id'] == $_user['user_id']): ?>
                        <option value="<?= uencode($_user['user_id']) ?>">
                          <?= $_user['first_name'] . ' ' . $_user['last_name']; ?>
                        </option>
                      <?php endif; ?>
                    <?php } ?>
                  </select>
                <?php } else { ?>
                  <select id="S" class="form-control" data-plugin="select_hrm" data-placeholder="<?= lang('Dashboard.dashboard_employee'); ?>" name="S">
                    <?php foreach ($staff_info as $_user) { ?>
                      <option value="<?= uencode($_user['user_id']) ?>">
                        <?= $_user['first_name'] . ' ' . $_user['last_name']; ?>
                      </option>
                    <?php } ?>
                  </select>
                <?php } ?>
              </div>
              <div class="col">
                <label class="form-label">
                  <?= lang('Payroll.xin_select_month'); ?>
                </label>
                <input class="form-control hr_month_year" placeholder="<?= lang('Payroll.xin_select_month'); ?>" name="M" type="text" value="<?= date('Y-m'); ?>">
              </div>
              <div class="col">
                <label class="form-label">&nbsp;</label>
                <br />
                <button type="submit" class="btn btn-primary"><i data-feather="search"></i></button>
              </div>
            </div>
          </div>
        </div>
        <?= form_close(); ?>
      </div>
    </div>
  </div>
</div>
<style type="text/css">
  .hide-calendar .ui-datepicker-calendar {
    display: none !important;
  }

  .hide-calendar .ui-priority-secondary {
    display: none !important;
  }
</style>