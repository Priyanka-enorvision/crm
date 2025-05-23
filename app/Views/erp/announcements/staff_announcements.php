<?php

use App\Models\RolesModel;
use App\Models\UsersModel;
use App\Models\SystemModel;
use App\Models\DepartmentModel;
//$encrypter = \Config\Services::encrypter();
$SystemModel = new SystemModel();
$RolesModel = new RolesModel();
$UsersModel = new UsersModel();
$DepartmentModel = new DepartmentModel();
$session = \Config\Services::session();
$usession = $session->get('sup_username');
$request = \Config\Services::request();

$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
if ($user_info['user_type'] == 'staff') {
  $main_department = $DepartmentModel->where('company_id', $user_info['company_id'])->findAll();
} else {
  $main_department = $DepartmentModel->where('company_id', $usession['sup_user_id'])->findAll();
}
?>
<?php if (in_array('news1', staff_role_resource()) || in_array('department1', staff_role_resource()) || in_array('designation1', staff_role_resource()) || in_array('policy1', staff_role_resource()) || $user_info['user_type'] == 'company') { ?>


  <hr class="border-light m-0 mb-3">
<?php } ?>
<?php if (in_array('news2', staff_role_resource()) || $user_info['user_type'] == 'company') { ?>
  <div id="add_form" class="collapse add-form" data-parent="#accordion" style="">
    <div class="card mb-2">
      <div id="accordion">
        <div class="card-header">
          <h5>
            <?= lang('Main.xin_add_new'); ?>
            <?= lang('Dashboard.left_announcement'); ?>
          </h5>
          <div class="card-header-right"> <a data-toggle="collapse" href="#add_form" aria-expanded="false" class="collapsed btn btn-sm waves-effect waves-light btn-primary m-0"> <i data-feather="minus"></i>
              <?= lang('Main.xin_hide'); ?>
            </a> </div>
        </div>
        <?php $attributes = array('name' => 'add_announcement', 'id' => 'xin-form', 'autocomplete' => 'off'); ?>
        <?php $hidden = array('user_id' => '1'); ?>
        <?php echo form_open('erp/add-announcements', $attributes, $hidden); ?>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="title">
                  <?= lang('Dashboard.xin_title'); ?> <span class="text-danger">*</span>
                </label>
                <input class="form-control" placeholder="<?= lang('Dashboard.xin_title'); ?>" name="title" type="text" value="">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="start_date">
                  <?= lang('Projects.xin_start_date'); ?> <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <input class="form-control date" placeholder="<?= lang('Projects.xin_start_date'); ?>" name="start_date" type="text" value="">
                  <div class="input-group-append"><span class="input-group-text"><i class="fas fa-calendar-alt"></i></span></div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="end_date">
                  <?= lang('Projects.xin_end_date'); ?> <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <input class="form-control date" placeholder="<?= lang('Projects.xin_end_date'); ?>" name="end_date" type="text" value="">
                  <div class="input-group-append"><span class="input-group-text"><i class="fas fa-calendar-alt"></i></span></div>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group" id="department_ajax">
                <input type="hidden" value="0" name="department_id[]" />
                <label for="department" class="control-label">
                  <?= lang('Dashboard.left_department'); ?>
                </label>
                <select class="form-control" multiple="multiple" name="department_id[]" data-plugin="select_hrm" data-placeholder="<?= lang('Dashboard.left_department'); ?>">
                  <option value="">
                    <?= lang('xin_department'); ?>
                  </option>
                  <?php foreach ($main_department as $idepartment) { ?>
                    <option value="<?= $idepartment['department_id'] ?>">
                      <?= $idepartment['department_name'] ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="summary">
                  <?= lang('Main.xin_summary'); ?> <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" placeholder="<?= lang('Main.xin_summary'); ?>" name="summary" id="summary">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="description">
                  <?= lang('Main.xin_description'); ?>
                </label>
                <textarea class="form-control editor" placeholder="<?= lang('Main.xin_description'); ?>" name="description" cols="8" rows="5" id="description"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer text-right">
          <button type="reset" class="btn btn-light" href="#add_form" data-toggle="collapse" aria-expanded="false">
            <?= lang('Main.xin_reset'); ?>
          </button>
          &nbsp;
          <button type="submit" class="btn btn-primary">
            <?= lang('Main.xin_save'); ?>
          </button>
        </div>
        <?= form_close(); ?>
      </div>
    </div>
  </div>
<?php } ?>
<div class="card user-profile-list">
  <div class="card-header">
    <h5>
      <?= lang('Main.xin_list_all'); ?>
      <?= lang('Dashboard.left_announcements'); ?>
    </h5>
    <?php if (in_array('news2', staff_role_resource()) || $user_info['user_type'] == 'company') { ?>
      <div class="card-header-right"> <a data-toggle="collapse" href="#add_form" aria-expanded="false" class="collapsed btn waves-effect waves-light btn-primary btn-sm m-0"> <i data-feather="plus"></i>
          <?= lang('Main.xin_add_new'); ?>
        </a> </div>
    <?php } ?>
  </div>
  <div class="card-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
          <tr>
            <th><?= lang('Dashboard.xin_title'); ?></th>
            <th><?= lang('Dashboard.left_department'); ?></th>
            <th><i class="fa fa-calendar small"></i>
              <?= lang('Projects.xin_start_date'); ?></th>
            <th><i class="fa fa-calendar small"></i>
              <?= lang('Projects.xin_end_date'); ?></th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>