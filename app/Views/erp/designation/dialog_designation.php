<?php

use App\Models\SystemModel;
use App\Models\RolesModel;
use App\Models\UsersModel;
use App\Models\DepartmentModel;
use App\Models\DesignationModel;

$session = \Config\Services::session();
$usession = $session->get('sup_username');
$request = \Config\Services::request();
$UsersModel = new UsersModel();
$DepartmentModel = new DepartmentModel();
$DesignationModel = new DesignationModel();
$get_animate = '';
if ($request->getGet('data') === 'designation' && $request->getGet('field_id')) {
	$field_id = $request->getGet('field_id');
	$ifield_id = udecode($field_id);
	$result = $DesignationModel->where('designation_id', $ifield_id)->first();
	$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
	if ($user_info['user_type'] == 'staff') {
		$main_department = $DepartmentModel->where('company_id', $user_info['company_id'])->findAll();
	} else {
		$main_department = $DepartmentModel->where('company_id', $usession['sup_user_id'])->findAll();
	}
?>

	<div class="modal-header">
		<h5 class="modal-title">
			<?= lang('Dashboard.left_edit_designation'); ?>
			<span class="font-weight-light">
				<?= lang('Main.xin_information'); ?>
			</span> <br>
			<small class="text-muted">
				<?= lang('Main.xin_below_required_info'); ?>
			</small>
		</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="<?= lang('Main.xin_close'); ?>"> <span aria-hidden="true">×</span> </button>
	</div>
	<?php $attributes = ['name' => 'edit_designation', 'id' => 'edit_designation', 'autocomplete' => 'off', 'class' => 'm-b-1']; ?>
	<?php $hidden = ['_method' => 'EDIT', 'token' => $field_id]; ?>
	<?= form_open('erp/update_designation', $attributes, $hidden); ?>

	<div class="modal-body">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label for="name">
						<?= lang('Dashboard.left_department'); ?>
						<span class="text-danger">*</span> </label>
					<select class="form-control" data-plugin="select_hrm" data-placeholder="<?= lang('Dashboard.left_department'); ?>" name="department">
						<option value=""></option>
						<?php foreach ($main_department as $idepartment) { ?>
							<option value="<?= $idepartment['department_id'] ?>" <?php if ($idepartment['department_id'] == $result['department_id']): ?> selected="selected" <?php endif; ?>>
								<?= $idepartment['department_name'] ?>
							</option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label for="name">
						<?= lang('Dashboard.left_designation_name'); ?> <span class="text-danger">*</span>
					</label>
					<input type="text" class="form-control" name="designation_name" placeholder="<?= lang('Dashboard.left_designation_name'); ?>" value="<?= $result['designation_name']; ?>">
				</div>
				<div class="form-group">
					<label for="description">
						<?= lang('Main.xin_description'); ?>
					</label>
					<textarea type="text" class="form-control" name="description" placeholder="<?= lang('Main.xin_description'); ?>"><?= $result['description']; ?></textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-light" data-dismiss="modal">
			<?= lang('Main.xin_close'); ?>
		</button>
		<button type="submit" class="btn btn-primary ladda-button" data-style="expand-right">
			<?= lang('Main.xin_update'); ?>
		</button>
	</div>
	<?= form_close(); ?>

	<script type="text/javascript">
		$(document).ready(function() {
			// Initialize select2
			$('[data-plugin="select_hrm"]').select2({
				width: '100%',
				dropdownParent: $('#ajaxModal')
			});

			// Form submission handler
			$("#edit_designation").on('submit', function(e) {
				e.preventDefault();
				e.stopImmediatePropagation(); // Add this line

				var obj = $(this);
				var l = Ladda.create(obj.find('.ladda-button')[0]);
				l.start();

				$.ajax({
					type: "POST",
					url: obj.attr('action'),
					data: obj.serialize() + "&is_ajax=1&type=edit_record&form=edit_designation",
					dataType: "json",
					cache: false,
					success: function(response) {
						if (response.error) {
							toastr.error(response.error);
							if (response.csrf_hash) {
								$('input[name="csrf_token"]').val(response.csrf_hash);
							}
							l.stop();
							return;
						}

						toastr.success(response.result);
						obj.closest('.modal').modal('hide');

						// First check if DataTable is properly initialized
						if (typeof xin_table !== 'undefined' && $.fn.dataTable.isDataTable('#xin_table')) {
							xin_table.api().ajax.reload(null, false); // false keeps current paging
						} else {
							console.error('DataTable not properly initialized');

						}

						l.stop();
					},
					error: function(xhr) {
						var errorMsg = 'An error occurred';
						try {
							var json = JSON.parse(xhr.responseText);
							errorMsg = json.error || errorMsg;
						} catch (e) {
							errorMsg = xhr.statusText || 'Request failed';
						}
						toastr.error(errorMsg);
						l.stop();
					}
				});
				return false; // Additional prevention
			});
		});
	</script>
<?php } ?>