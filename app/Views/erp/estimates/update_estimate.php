<?php

use App\Models\SystemModel;
use App\Models\RolesModel;
use App\Models\UsersModel;
use App\Models\EstimatesModel;
use App\Models\ConstantsModel;

$session = \Config\Services::session();
$usession = $session->get('sup_username');
$request = \Config\Services::request();
$UsersModel = new UsersModel();
$EstimatesModel = new EstimatesModel();
$ConstantsModel = new ConstantsModel();

if ($request->getGet('data') === 'cancel_estimates' && $request->getGet('field_id')) {
	$invoice_id = $field_id;
	$result = $EstimatesModel->where('estimate_id', $invoice_id)->first();
	$payment_method = $ConstantsModel->where('type', 'payment_method')->orderBy('constants_id', 'ASC')->findAll();


?>

	<div class="modal-header">
		<h5 class="modal-title">
			<?= lang('Main.xin_cancel_estimate') . ' #' . $result['estimate_number']; ?>
		</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
	</div>
	<?php $attributes = array('name' => 'cancel_estimate_record', 'id' => 'cancel_estimate_record', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', 'token' => $field_id); ?>
	<?= form_open('erp/estimates/cancel_estimate_record', $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="alert alert-danger" role="alert">
			<?= lang('Main.xin_are_you_sure_cancel_estimate'); ?>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-light" data-dismiss="modal">
			<?= lang('Main.xin_close'); ?>
		</button>
		<button type="submit" class="btn btn-success">
			<?= lang('Main.xin_cancel_estimate'); ?>
		</button>
	</div>
	<?= form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {

			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			Ladda.bind('button[type=submit]');

			/* Edit data */
			$("#cancel_estimate_record").submit(function(e) {
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() + "&is_ajax=1&type=cancel_estimate_record&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_token"]').val(JSON.csrf_hash);
							Ladda.stopAll();
						} else {
							toastr.success(JSON.result);
							$('input[name="csrf_token"]').val(JSON.csrf_hash);
							$('.view-modal-data').modal('toggle');
							Ladda.stopAll();
							window.location = '';
						}
					}
				});
			});
		});
	</script>
<?php } else if ($request->getGet('data') === 'convert_estimates' && $request->getGet('field_id')) {
	$invoice_id = $field_id;
	$result = $EstimatesModel->where('estimate_id', $invoice_id)->first();
	$payment_method = $ConstantsModel->where('type', 'payment_method')->orderBy('constants_id', 'ASC')->findAll();
?>

	<div class="modal-header">
		<h5 class="modal-title">
			<?= lang('Main.xin_estimate_number') . ' #' . $result['estimate_number']; ?>
		</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
	</div>
	<?php $attributes = array('name' => 'convert_estimate_record', 'id' => 'convert_estimate_record', 'autocomplete' => 'off', 'class' => 'm-b-1'); ?>
	<?php $hidden = array('_method' => 'EDIT', 'token' => $field_id); ?>
	<?= form_open('erp/estimates/convert_estimate_record', $attributes, $hidden); ?>
	<div class="modal-body">
		<div class="alert alert-success" role="alert">
			<?= lang('Main.xin_are_you_sure_convert_estimate_to_invoice'); ?>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-light" data-dismiss="modal">
			<?= lang('Main.xin_close'); ?>
		</button>
		<button type="submit" class="btn btn-success">
			<?= lang('Main.xin_convert_estimate_to_invoice'); ?>
		</button>
	</div>
	<?= form_close(); ?>
	<script type="text/javascript">
		$(document).ready(function() {

			$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
			$('[data-plugin="select_hrm"]').select2({
				width: '100%'
			});
			Ladda.bind('button[type=submit]');

			/* Edit data */
			$("#convert_estimate_record").submit(function(e) {
				e.preventDefault();
				var obj = $(this),
					action = obj.attr('name');
				$.ajax({
					type: "POST",
					url: e.target.action,
					data: obj.serialize() + "&is_ajax=1&type=convert_estimate_record&form=" + action,
					cache: false,
					success: function(JSON) {
						if (JSON.error != '') {
							toastr.error(JSON.error);
							$('input[name="csrf_token"]').val(JSON.csrf_hash);
							Ladda.stopAll();
						} else {
							toastr.success(JSON.result);
							$('input[name="csrf_token"]').val(JSON.csrf_hash);
							$('.payroll-modal-data').modal('toggle');
							Ladda.stopAll();
							window.location = '';
						}
					}
				});
			});
		});
	</script>
<?php }
?>