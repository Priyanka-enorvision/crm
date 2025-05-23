$(document).ready(function () {
	var xin_table = $('#xin_table').dataTable({
		"bDestroy": true,
		"ajax": {
			url: main_url + "clients/leads_followup_list?xlead_id=" + $('#xlead_id').val(),
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
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	$("#followup_info").submit(function (e) {
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 1);
		fd.append("type", 'add_record');
		fd.append("form", action);
		e.preventDefault();
		$.ajax({
			url: e.target.action,
			type: "POST",
			data: fd,
			contentType: false,
			cache: false,
			processData: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_token"]').val(JSON.csrf_hash);
					Ladda.stopAll();
				} else {
					xin_table.api().ajax.reload(function () {
						toastr.success(JSON.result);
						$('input[name="csrf_token"]').val(JSON.csrf_hash);
					}, true);
					jQuery('#followup_info')[0].reset(); // To reset form fields
					Ladda.stopAll();
				}
			},
			error: function () {
				toastr.error(JSON.error);
				$('input[name="csrf_token"]').val(JSON.csrf_hash);
				Ladda.stopAll();
			}
		});
	});
	// edit
	$('.view-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var field_id = button.data('field_id');
		var modal = $(this);
		$.ajax({
			url: main_url + "clients/read_followup",
			type: "GET",
			data: 'jd=1&data=followup&field_id=' + field_id,
			success: function (response) {
				if (response) {
					$("#ajax_view_modal").html(response);
				}
			}
		});
	});
	/* Edit data */ /*Form Submit*/
	$("#update_lead").submit(function (e) {
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 1);
		fd.append("type", 'edit_record');
		fd.append("form", action);
		e.preventDefault();

		$.ajax({
			url: e.target.action,
			type: "POST",
			data: fd,
			contentType: false,
			cache: false,
			processData: false,
			success: function (response) {
				$('input[name="csrf_token"]').val(response.csrf_hash); // Update CSRF token
				if (response.error) {
					toastr.error(response.error); // Show error message
				} else {
					toastr.success(response.result); // Show success message
					if (response.redirect) { // Check for redirect URL
						setTimeout(function () {
							window.location.href = response.redirect; // Redirect to the provided URL
						}, 2000);
					}
				}
				Ladda.stopAll();
			},
			error: function (jqXHR) {
				var errorResponse = jqXHR.responseJSON || {}; // Access error response
				toastr.error(errorResponse.error || 'An error occurred.'); // Fallback message
				$('input[name="csrf_token"]').val(errorResponse.csrf_hash);
				Ladda.stopAll();
			}
		});
	});


	/* Edit data */ /*Form Submit*/
	$("#profile_photo").submit(function (e) {
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 1);
		fd.append("type", 'edit_record');
		fd.append("form", action);
		e.preventDefault();
		$.ajax({
			url: e.target.action,
			type: "POST",
			data: fd,
			contentType: false,
			cache: false,
			processData: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_token"]').val(JSON.csrf_hash);
					Ladda.stopAll();
				} else {
					toastr.success(JSON.result);
					$('input[name="csrf_token"]').val(JSON.csrf_hash);
					Ladda.stopAll();
					window.location = '';
				}
			},
			error: function () {
				toastr.error(JSON.error);
				$('input[name="csrf_token"]').val(JSON.csrf_hash);
				Ladda.stopAll();
			}
		});
	});
	/* Delete data */
	$("#delete_record").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=2&type=delete_record&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_token"]').val(JSON.csrf_hash);
					Ladda.stopAll();
				} else {
					$('.delete-modal').modal('toggle');
					xin_table.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_token"]').val(JSON.csrf_hash);
					Ladda.stopAll();
				}
			}
		});
	});
});
$(document).on("click", ".delete", function () {
	$('_token').val($(this).data('record-id'));
	$('#delete_record').attr('action', main_url + 'clients/delete_lead_followup');
});