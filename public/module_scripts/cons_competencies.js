$(document).ready(function () {
	var xin_table = $('#xin_table').DataTable({
		"bDestroy": true,
		"ajax": {
			url: main_url + "competencies-Datalist",
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
	var xin_org_table = $('#xin_org_table').dataTable({
		"bDestroy": true,
		"ajax": {
			url: main_url + "types/org_competencies_list",
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
					toastr.success(JSON.result);
					window.location.href = main_url + 'competencies';
					$('input[name="csrf_token"]').val(JSON.csrf_hash);

					Ladda.stopAll();


				}
			},
			error: function (xhr, error, thrown) {
				console.log("AJAX Error: ", xhr.responseText);
			}
		});
	});

	// edit
	$('.view-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var field_id = button.data('field_id');
		var data_option = button.data('comp_option');
		var modal = $(this);
		$.ajax({
			url: main_url + "read-competencies",
			type: "GET",
			data: 'jd=1&data=competencies&field_id=' + field_id + '&data_option=' + data_option,
			success: function (response) {
				if (response) {
					$("#ajax_view_modal").html(response);
				}
			}
		});
	});
	/* Add data */ /*Form Submit*/
	$("#xin-form").submit(function (e) {
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
				} else {
					toastr.success(JSON.result);
					$('input[name="csrf_token"]').val(JSON.csrf_hash);
					window.location.href = main_url + 'competencies';
				}
			},
			error: function (xhr, status, error) {
				toastr.error('Something went wrong. Please try again.');
				if (xhr.responseJSON && xhr.responseJSON.csrf_hash) {
					$('input[name="csrf_token"]').val(xhr.responseJSON.csrf_hash);
				}
			}
		});
	});
	/* Add data */ /*Form Submit*/
	$("#add_competencies2").submit(function (e) {
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
					xin_org_table.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_token"]').val(JSON.csrf_hash);
					$('#add_competencies2')[0].reset(); // To reset form fields
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
});
$(document).on("click", ".delete", function () {
	$('input[name=_token]').val($(this).data('record-id'));
	$('#delete_record').attr('action', main_url + 'delete-type/' + $(this).data('record-id'));
});