<?php

use App\Models\ConstantsModel;

$ConstantsModel = new ConstantsModel();
$request = \Config\Services::request();

if ($request->getGet('data') === 'currency_type' && $request->getGet('type') === 'currency_type' && $field_id) {
	$ifield_id = udecode($field_id);
	$result = $ConstantsModel->where('constants_id', $ifield_id)->where('type', 'currency_type')->first();
?>
	<div class="modal-header">
		<h5 class="modal-title">
			<?= lang('Main.xin_edit_currency_type'); ?>
			<span class="font-weight-light">
				<?= lang('Main.xin_information'); ?>
			</span> <br>
			<small class="text-muted">
				<?= lang('Main.xin_below_required_info'); ?>
			</small>
		</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
	</div>
	<?php $attributes = array('name' => 'ed_currency_type_info', 'id' => 'ed_currency_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', 'token' => $field_id); ?>
	<?php echo form_open('erp/settings/update_currency_type', $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="form-group">
			<label for="name">
				<?= lang('Main.xin_currency_name'); ?>
				<span class="text-danger">*</span></label>
			<input type="text" class="form-control" name="name" placeholder="<?= lang('Main.xin_currency_name'); ?>" value="<?php echo $result['category_name']; ?>">
		</div>
		<div class="form-group">
			<label for="name">
				<?= lang('Main.xin_currency_code'); ?>
				<span class="text-danger">*</span></label>
			<input type="text" class="form-control" name="code" placeholder="<?= lang('Main.xin_currency_code'); ?>" value="<?php echo $result['field_one']; ?>">
		</div>
		<div class="form-group">
			<label for="name">
				<?= lang('Main.xin_currency_symbol'); ?>
				<span class="text-danger">*</span></label>
			<input type="text" class="form-control" name="symbol" placeholder="<?= lang('Main.xin_currency_symbol'); ?>" value="<?php echo $result['field_two']; ?>">
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
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			Ladda.bind('button[type=submit]');
			/* Edit data */
			$("#ed_currency_type_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() + "&is_ajax=46&type=edit_record&data=ed_currency_type_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_token"]').val(JSON.csrf_hash);
							Ladda.stopAll();
						} else {
							$('.edit-modal-data').modal('toggle');
							// On page load: datatable
							var xin_table_currency_type = $('#xin_table_currency_type').dataTable({
								"bDestroy": true,
								//"bFilter": false,
								//"iDisplayLength": 10,
								"aLengthMenu": [
									[10, 30, 50, 100, -1],
									[10, 30, 50, 100, "All"]
								],
								"ajax": {
									url: "<?php echo site_url("erp/settings/currency_type_list") ?>",
									type: 'GET'
								},
								"language": {
									"lengthMenu": dt_lengthMenu,
									"zeroRecords": dt_zeroRecords,
									"info": dt_info,
									"infoEmpty": dt_infoEmpty,
									"infoFiltered": dt_infoFiltered,
									"search": dt_search,
									"paginate": {
										"first": dt_first,
										"previous": dt_previous,
										"next": dt_next,
										"last": dt_last
									},
								},
								"fnDrawCallback": function(settings) {
									$('[data-toggle="tooltip"]').tooltip();
								}
							});
							xin_table_currency_type.api().ajax.reload(function() {
								toastr.success(JSON.result);
							}, true);
							$('input[name="csrf_token"]').val(JSON.csrf_hash);
							Ladda.stopAll();
						}
					}
				});
			});
		});
	</script>
<?php } else if ($request->getGet('data') === 'ed_company_type' && $request->getGet('type') === 'ed_company_type' && $field_id) {
	$ifield_id = udecode($field_id);
	$row = $ConstantsModel->where('constants_id', $ifield_id)->where('type', 'company_type')->first();
?>
	<div class="modal-header">
		<h5 class="modal-title">
			<?= lang('Main.xin_edit_company_type'); ?>
			<span class="font-weight-light">
				<?= lang('Main.xin_information'); ?>
			</span> <br>
			<small class="text-muted">
				<?= lang('Main.xin_below_required_info'); ?>
			</small>
		</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
	</div>
	<?php $attributes = array('name' => 'ed_company_type_info', 'id' => 'ed_company_type_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', 'token' => $field_id); ?>
	<?php echo form_open('erp/settings/update_company_type', $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="form-group">
			<label for="name" class="form-control-label">
				<?= lang('Main.xin_company_type'); ?>
				<span class="text-danger">*</span></label>
			<input type="text" class="form-control" name="name" placeholder="<?= lang('Main.xin_company_type'); ?>" value="<?php echo $row['category_name']; ?>">
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
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			Ladda.bind('button[type=submit]');
			/* Edit data */
			$("#ed_company_type_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() + "&is_ajax=46&type=edit_record&data=ed_company_type_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_token"]').val(JSON.csrf_hash);
							Ladda.stopAll();
						} else {
							toastr.success(JSON.result);
							window.location.href = main_url + 'system-constants';
							$('input[name="csrf_token"]').val(JSON.csrf_hash);

							Ladda.stopAll();
						}
					},
					error: function(xhr, error, thrown) {
						console.log("AJAX Error: ", xhr.responseText);
					}
				});
			});
		});
	</script>
<?php } else if ($request->getGet('data') === 'ed_religion' && $request->getGet('type') === 'ed_religion' && $field_id) {
	$ifield_id = udecode($field_id);
	$row = $ConstantsModel->where('constants_id', $ifield_id)->where('type', 'religion')->first();
?>
	<div class="modal-header">
		<h5 class="modal-title">
			<?= lang('Main.xin_edit_religion'); ?>
			<span class="font-weight-light">
				<?= lang('Main.xin_information'); ?>
			</span> <br>
			<small class="text-muted">
				<?= lang('Main.xin_below_required_info'); ?>
			</small>
		</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
	</div>
	<?php $attributes = array('name' => 'ed_religion_info', 'id' => 'ed_religion_info', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', 'token' => $field_id); ?>
	<?php echo form_open('erp/update-religion', $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="form-group">
			<label for="name" class="form-control-label">
				<?= lang('Main.xin_ethnicity_type_title'); ?>
				<span class="text-danger">*</span></label>
			<input type="text" class="form-control" name="religion" placeholder="<?= lang('Main.xin_ethnicity_type_title'); ?>" value="<?php echo $row['category_name']; ?>">
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
			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			Ladda.bind('button[type=submit]');
			/* Edit data */
			$("#ed_religion_info").submit(function(e) {
				/*Form Submit*/
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() + "&is_ajax=46&type=edit_record&data=ed_religion_info&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_token"]').val(JSON.csrf_hash);
							Ladda.stopAll();
						} else {
							toastr.success(JSON.result);
							window.location.href = main_url + 'system-constants';
							$('input[name="csrf_token"]').val(JSON.csrf_hash);

							Ladda.stopAll();
						}
					},
					error: function(xhr, error, thrown) {
						console.log("AJAX Error: ", xhr.responseText);
					}
				});
			});
		});
	</script>
<?php } ?>