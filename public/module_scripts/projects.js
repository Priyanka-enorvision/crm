$(document).ready(function () {

	var xin_table = $('#xin_table').DataTable({
		"bDestroy": true,
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: main_url + "projects/projects_list",
			type: 'GET',
			data: function (d) {
				d.status = $('#status_filter').val();
				d.assigned_to = $('#assigned_to_filter').val();
				d.expert_to = $('#expert_to_filter').val();
			}
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
			}
		},
		"drawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
	// Reload table when filters are changed
	$('#status_filter, #assigned_to_filter, #expert_to_filter').on('change', function () {
		xin_table.ajax.reload();
	});
	/* Delete data */
	$("#delete_record").submit(function (e) {
		e.preventDefault();  // Prevent form submission

		$.ajax({
			type: "POST",
			url: e.target.action,
			data: $(this).serialize() + "&type=delete_record",
			dataType: "json",
			success: function (response) {
				if (response.result) {
					toastr.success(response.result);
					$('.delete-modal').modal('toggle');

					setTimeout(function () {
						window.location.href = response.redirect_url;
					}, 1000);

				} else if (response.error) {
					toastr.error(response.error);
				}
				$('input[name="csrf_token"]').val(response.csrf_hash);
			},
			error: function (xhr, status, error) {
				console.error("Error deleting project: ", error);
				toastr.error('An error occurred while deleting the project.');
				setTimeout(function () {
					window.location.href = response.redirect_url;
				}, 2000);
			}
		});
	});


	// edit
	$('.edit-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var field_id = button.data('field_id');
		var modal = $(this);
		$.ajax({
			url: main_url + "projects/read_project",
			type: "GET",
			data: 'jd=1&is_ajax=1&mode=modal&data=meeting&field_id=' + field_id,
			success: function (response) {
				if (response) {
					$("#ajax_modal").html(response);
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
					$('input[name="csrf_token"]').val(JSON.csrf_hash);
					Ladda.stopAll();
				} else {
					toastr.success(JSON.result);
					$('input[name="csrf_token"]').val(JSON.csrf_hash);
					$('#xin-form')[0].reset();
					$('.add-form').removeClass('show');
					Ladda.stopAll();

					// Redirect to projects list page after success
					if (JSON.redirect_url) {
						window.location.href = JSON.redirect_url;
					}
				}
			},
			error: function () {
				toastr.error("An error occurred. Please try again.");
				$('input[name="csrf_token"]').val(JSON.csrf_hash);
				Ladda.stopAll();
			}
		});
	});
});
$(document).on("click", ".delete", function () {
	$('input[name=_token]').val($(this).data('record-id'));
	$('#delete_record').attr('action', main_url + 'projects/delete_project');
});

