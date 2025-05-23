<?php

use App\Models\SystemModel;
use App\Models\RolesModel;
use App\Models\UsersModel;
use App\Models\DepartmentModel;
use App\Models\AnnouncementModel;

$session = \Config\Services::session();
$usession = $session->get('sup_username');
$request = \Config\Services::request();
$UsersModel = new UsersModel();
$DepartmentModel = new DepartmentModel();
$AnnouncementModel = new AnnouncementModel();
$get_animate = '';
if ($request->getGet('data') === 'announcement' && $request->getGet('field_id')) {
  $ifield_id = udecode($field_id);
  $result = $AnnouncementModel->where('announcement_id', $ifield_id)->first();
  $user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
  if ($user_info['user_type'] == 'staff') {
    $main_department = $DepartmentModel->where('company_id', $user_info['company_id'])->findAll();
  } else {
    $main_department = $DepartmentModel->where('company_id', $usession['sup_user_id'])->findAll();
  }
?>

  <div class="modal-header">
    <h5 class="modal-title">
      <?= lang('Dashboard.xin_edit_news'); ?>
      <span class="font-weight-light">
        <?= lang('Main.xin_information'); ?>
      </span> <br>
      <small class="text-muted">
        <?= lang('Main.xin_below_required_info'); ?>
      </small>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  </div>
  <?php $attributes = ['name' => 'edit_announcement', 'id' => 'edit_announcement', 'autocomplete' => 'off', 'class' => 'm-b-1']; ?>
  <?php $hidden = ['_method' => 'EDIT', 'token' => $field_id]; ?>
  <?php echo form_open_multipart('erp/update-announcements', $attributes, $hidden); ?>
  <div class="modal-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="title">
            <?= lang('Dashboard.xin_title'); ?> <span class="text-danger">*</span>
          </label>
          <input class="form-control" placeholder="<?= lang('Dashboard.xin_title'); ?>" name="title" type="text" value="<?php echo $result['title']; ?>">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="start_date">
            <?= lang('Projects.xin_start_date'); ?> <span class="text-danger">*</span>
          </label>
          <div class="input-group">
            <input class="form-control d_date" name="start_date" type="text" placeholder="<?= lang('Projects.xin_start_date'); ?>" value="<?php echo $result['start_date']; ?>">
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
            <input class="form-control d_date" placeholder="<?= lang('Projects.xin_end_date'); ?>" name="end_date" type="text" value="<?php echo $result['end_date']; ?>">
            <div class="input-group-append"><span class="input-group-text"><i class="fas fa-calendar-alt"></i></span></div>
          </div>
        </div>
      </div>


      <div class="col-md-12">
        <?php $department_ids = explode(',', $result['department_id']); ?>
        <input type="hidden" value="0" name="department_id[]" />
        <div class="form-group">
          <label for="department" class="control-label">
            <?= lang('Dashboard.left_department'); ?>
          </label>
          <select class="form-control" multiple="multiple" name="department_id[]" data-plugin="select_hrm" data-placeholder="<?= lang('Dashboard.left_department'); ?>">
            <option value=""></option>
            <?php foreach ($main_department as $idepartment) { ?>
              <option value="<?= $idepartment['department_id'] ?>" <?php if (in_array($idepartment['department_id'], $department_ids)): ?> selected <?php endif; ?>>
                <?= $idepartment['department_name'] ?>
              </option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="summary">
            <?= lang('Main.xin_summary'); ?> <span class="text-danger">*</span>
          </label>
          <textarea class="form-control" placeholder="<?= lang('Main.xin_summary'); ?>" name="summary" cols="30" rows="1" id="summary"><?php echo $result['summary']; ?></textarea>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="description">
            <?= lang('Main.xin_description'); ?>
          </label>
          <textarea class="form-control meditor" placeholder="<?= lang('Main.xin_description'); ?>" name="description" cols="30" rows="5"><?php echo $result['description']; ?></textarea>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-light" data-dismiss="modal">
      <?= lang('Main.xin_close'); ?>
    </button>
    <button type="submit" class="btn btn-primary">
      <?= lang('Main.xin_update'); ?>
    </button>
  </div>
  <?php echo form_close(); ?>
  <script type="text/javascript">
    $(document).ready(function() {
      // Initialize select2
      $('[data-plugin="select_hrm"]').select2({
        width: '100%'
      });

      $("#edit_announcement").submit(function(e) {
        e.preventDefault();
        var obj = $(this);
        var l = Ladda.create(document.querySelector('.ladda-button'));
        l.start();

        $.ajax({
          type: "POST",
          url: obj.attr('action'),
          data: obj.serialize() + "&is_ajax=1&type=edit_record&form=edit_announcement",
          dataType: "json",
          cache: false,
          success: function(JSON) {
            if (JSON.error != '') {
              toastr.error(JSON.error);
            } else {
              toastr.success(JSON.result);
              $('input[name="csrf_token"]').val(JSON.csrf_hash);
              window.location.href = main_url + 'news-list';
            }
          },
          error: function(xhr, status, error) {
            toastr.error('Something went wrong. Please try again.');
            if (xhr.responseJSON && xhr.responseJSON.csrf_hash) {
              $('input[name="csrf_token"]').val(xhr.responseJSON.csrf_hash);
            }
          }
        });
      });
    });
  </script>
<?php }
?>