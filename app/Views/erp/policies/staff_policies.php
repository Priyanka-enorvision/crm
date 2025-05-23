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

<?php if (in_array('news1', staff_role_resource()) || in_array('department1', staff_role_resource()) || in_array('designation1', staff_role_resource()) || in_array('policy1', staff_role_resource()) || $user_info['user_type'] == 'company') { ?>


  <hr class="border-light m-0 mb-3">
<?php } ?>
<div class="row m-b-1 <?php //echo $get_animate;
                      ?>">
  <?php if (in_array('policy2', staff_role_resource()) || $user_info['user_type'] == 'company') { ?>
    <div class="col-md-4">
      <div class="card">
        <div class="card-header with-elements">
          <h5>
            <?= lang('Main.xin_add_new'); ?>
            <?= lang('Dashboard.header_policy'); ?>
          </h5>
        </div>

        <?php $attributes = ['name' => 'add_policy', 'id' => 'xin-form', 'autocomplete' => 'off', 'class' => 'your-class-here']; ?>
        <?php $hidden = ['user_id' => '1']; ?>
        <?= form_open('erp/add-policy', $attributes, $hidden); ?>


        <div class="card-body">
          <div class="form-group">
            <label for="title">
              <?= lang('Dashboard.xin_title'); ?> <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" name="title" placeholder="<?= lang('Dashboard.xin_title'); ?>">
          </div>
          <div class="form-group">
            <label for="message">
              <?= lang('Main.xin_description'); ?> <span class="text-danger">*</span>
            </label>
            <textarea class="form-control" placeholder="<?= lang('Main.xin_description'); ?>" name="description" id="description"></textarea>
          </div>
          <div class="form-group">
            <fieldset class="form-group">
              <label for="attachment">
                <?= lang('Main.xin_attachment'); ?> <span class="text-danger">*</span>
              </label>
              <input type="file" class="form-control-file" id="attachment" name="attachment">
              <small>
                <?= lang('Main.xin_company_file_type'); ?>
              </small>
            </fieldset>
          </div>
        </div>
        <div class="card-footer text-right">
          <button type="submit" class="btn btn-primary">
            <?= lang('Main.xin_save'); ?>
          </button>
        </div>
        <?= form_close(); ?>
      </div>
    </div>
    <?php $colmdval = 'col-md-8'; ?>
  <?php } else { ?>
    <?php $colmdval = 'col-md-12'; ?>
  <?php  } ?>
  <div class="<?php echo $colmdval; ?>">
    <div class="card user-profile-list">
      <div class="card-header">
        <h5>
          <?= lang('Main.xin_list_all'); ?>
          <?= lang('Dashboard.header_policies'); ?>
        </h5>
        <?php if (in_array('policy5', staff_role_resource()) || $user_info['user_type'] == 'company') { ?>
          <div class="card-header-right"> <a href="<?php echo site_url('erp/all-policies'); ?>" class="collapsed btn waves-effect waves-light btn-primary btn-sm m-0"> <i data-feather="plus"></i>
              <?= lang('Dashboard.xin_view_policies'); ?>
            </a> </div>
        <?php } ?>
      </div>
      <div class="card-body">
        <div class="box-datatable table-responsive">
          <table class="datatables-demo table table-striped table-bordered" id="xin_table">
            <thead>
              <tr>
                <th width="230"><?= lang('Dashboard.xin_title'); ?></th>
                <th width="230">File URL</th>
                <th><i class="fa fa-user"></i>
                  <?= lang('Main.xin_created_at'); ?></th>
                <th><i class="fa fa-calendar"></i>
                  <?= lang('Main.xin_added_by'); ?></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>