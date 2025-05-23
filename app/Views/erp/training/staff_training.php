<?php
use App\Models\RolesModel;
use App\Models\UsersModel;
use App\Models\SystemModel;
use App\Models\CountryModel;
use App\Models\TrainersModel;
use App\Models\ConstantsModel;
//$encrypter = \Config\Services::encrypter();
$SystemModel = new SystemModel();
$RolesModel = new RolesModel();
$UsersModel = new UsersModel();
$CountryModel = new CountryModel();
$TrainersModel = new TrainersModel();
$ConstantsModel = new ConstantsModel();

$session = \Config\Services::session();
$usession = $session->get('sup_username');
$request = \Config\Services::request();
$xin_system = erp_company_settings();
$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
if($user_info['user_type'] == 'company'){
	$trainer = $TrainersModel->where('company_id', $usession['sup_user_id'])->orderBy('trainer_id','ASC')->findAll();
	$training_types = $ConstantsModel->where('company_id',$usession['sup_user_id'])->where('type','training_type')->orderBy('constants_id', 'ASC')->findAll();
} else {
	$trainer = $TrainersModel->where('company_id', $user_info['company_id'])->orderBy('trainer_id','ASC')->findAll();
	$training_types = $ConstantsModel->where('company_id',$user_info['company_id'])->where('type','training_type')->orderBy('constants_id', 'ASC')->findAll();
}
?>
<?php if(in_array('training2',staff_role_resource()) || in_array('trainer1',staff_role_resource()) || in_array('training_skill1',staff_role_resource()) || in_array('training_calendar',staff_role_resource()) || $user_info['user_type'] == 'company') { ?>

<hr class="border-light m-0 mb-3">
<?php } ?>
<?php if(in_array('training3',staff_role_resource()) || $user_info['user_type'] == 'company') { ?>
<div id="add_form" class="collapse add-form" data-parent="#accordion" style="">
  <div class="card mb-2">
    <div id="accordion">
      <div class="card-header">
        <h5>
          <?= lang('Main.xin_add_new');?>
          <?= lang('Dashboard.left_training');?>
        </h5>
        <div class="card-header-right"> <a  data-toggle="collapse" href="#add_form" aria-expanded="false" class="collapsed btn btn-sm waves-effect waves-light btn-primary m-0"> <i data-feather="minus"></i>
          <?= lang('Main.xin_hide');?>
          </a> </div>
      </div>
      <?php $attributes = array('name' => 'add_training', 'id' => 'xin-form', 'autocomplete' => 'off');?>
      <?php $hidden = array('_user' => '1');?>
      <?php echo form_open('erp/add-training', $attributes, $hidden);?>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="trainer">
                <?= lang('Dashboard.left_trainer');?> <span class="text-danger">*</span>
              </label>
              <select class="form-control" name="trainer" data-plugin="select_hrm" data-placeholder="<?= lang('Dashboard.left_trainer');?>">
                <option value=""></option>
                <?php foreach($trainer as $staff) {?>
                <option value="<?= $staff['trainer_id']?>">
                <?= $staff['first_name'].' '.$staff['last_name'] ?>
                </option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="training_type">
                <?= lang('Dashboard.left_training_skill');?> <span class="text-danger">*</span>
              </label>
              <select class="form-control" name="training_type" data-plugin="select_hrm" data-placeholder="<?= lang('Dashboard.left_training_skill');?>">
                <option value=""></option>
                <?php foreach($training_types as $itraining_type):?>
                <option value="<?= $itraining_type['constants_id'];?>">
                <?= $itraining_type['category_name'];?>
                </option>
                <?php endforeach;?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="training_cost">
                <?= lang('Main.xin_training_cost');?>
              </label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">
                  <?= $xin_system['default_currency'];?>
                  </span></div>
                <input class="form-control" placeholder="<?= lang('Main.xin_training_cost');?>" name="training_cost" type="text" value="">
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <?php if($user_info['user_type'] == 'company'){?>
          <?php $istaff_info = $UsersModel->where('company_id', $usession['sup_user_id'])->where('user_type','staff')->findAll();?>
          <input type="hidden" value="0" name="employee_id[]" />
          <div class="col-md-6">
            <div class="form-group">
              <label for="employee" class="control-label">
                <?= lang('Dashboard.dashboard_employees');?>
              </label>
              <select multiple class="form-control" name="employee_id[]" data-plugin="select_hrm" data-placeholder="<?= lang('Dashboard.dashboard_employees');?>">
                <option value=""></option>
                <?php foreach($istaff_info as $istaff) {?>
                <option value="<?= $istaff['user_id']?>">
                <?= $istaff['first_name'].' '.$istaff['last_name'] ?>
                </option>
                <?php } ?>
              </select>
            </div>
          </div>
          <?php } ?>
          <div class="col-md-3">
            <div class="form-group">
              <label for="start_date">
                <?= lang('Projects.xin_start_date');?> <span class="text-danger">*</span>
              </label>
              <div class="input-group">
                <input class="form-control date" placeholder="<?= lang('Projects.xin_start_date');?>" name="start_date" type="text" value="">
                <div class="input-group-append"><span class="input-group-text"><i class="fas fa-calendar-alt"></i></span></div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="end_date">
                <?= lang('Projects.xin_end_date');?> <span class="text-danger">*</span>
              </label>
              <div class="input-group">
                <input class="form-control date" placeholder="<?= lang('Projects.xin_end_date');?>" name="end_date" type="text" value="">
                <div class="input-group-append"><span class="input-group-text"><i class="fas fa-calendar-alt"></i></span></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="description">
                <?= lang('Main.xin_description');?>
              </label>
              <textarea class="form-control editor" placeholder="<?= lang('Main.xin_description');?>" name="description" rows="3" id="description"></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="reset" class="btn btn-light" href="#add_form" data-toggle="collapse" aria-expanded="false">
        <?= lang('Main.xin_reset');?>
        </button>
        &nbsp;
        <button type="submit" class="btn btn-primary">
        <?= lang('Main.xin_save');?>
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
      <?= lang('Main.xin_list_all');?>
      <?= lang('Dashboard.left_training');?>
    </h5>
    <?php if(in_array('training3',staff_role_resource()) || $user_info['user_type'] == 'company') { ?>
    <div class="card-header-right"> <a  data-toggle="collapse" href="#add_form" aria-expanded="false" class="collapsed btn waves-effect waves-light btn-primary btn-sm m-0"> <i data-feather="plus"></i>
      <?= lang('Main.xin_add_new');?>
      </a> </div>
    <?php } ?>
  </div>
  <div class="card-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
          <tr>
            <th><?= lang('Dashboard.left_training_skill');?></th>
            <th><?= lang('Dashboard.left_trainer');?></th>
            <th><i class="far fa-calendar-alt small"></i>
              <?= lang('Projects.xin_start_date');?></th>
            <th><i class="far fa-calendar-alt small"></i>
              <?= lang('Projects.xin_end_date');?></th>
            <th><i class="fa fa-user small"></i>
              <?= lang('Dashboard.dashboard_employees');?></th>
            <th><?= lang('Main.xin_training_cost');?></th>
            <th><?= lang('Main.dashboard_xin_status');?></th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
