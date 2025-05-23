<?php
use App\Models\SystemModel;
use App\Models\UsersModel;

$SystemModel = new SystemModel();
$UsersModel = new UsersModel();
$session = \Config\Services::session();
$router = service('router');
$usession = $session->get('sup_username');
$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
?>

<script src="https://kendo.cdn.telerik.com/2022.1.301/js/kendo.core.min.js"></script>
<script src="https://kendo.cdn.telerik.com/2022.1.301/js/kendo.editor.min.js"></script>
<script src="https://kendo.cdn.telerik.com/2022.1.301/js/kendo.data.min.js"></script>
<script src="https://kendo.cdn.telerik.com/2022.1.301/js/kendo.treeview.min.js"></script>
<script src="https://kendo.cdn.telerik.com/2022.1.301/js/kendo.all.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">
	//$(document).ready(function(){
	jQuery("#treeview_r1").kendoTreeView({
		checkboxes: {
			checkChildren: true,
			template: "<label class='custom-control custom-checkbox' ><input type='checkbox' class='#= item.class #' name='role_resources[]' value='#= item.value #'><span class='custom-control-label'>#= item.text #</span></label>"
		},
		check: onCheck,
		dataSource: [

			//Projects
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_projects'); ?>", add_info: "", value: "hr_projects", items: [
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_projects'); ?>", value: "project1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "project1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "project2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "project3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "project4", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?php echo "copy"; ?>", value: "project14", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_update_status'); ?>", value: "project5", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Projects.xin_discussion'); ?>", value: "project6", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Projects.xin_bugs'); ?>", value: "project7", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Projects.xin_tasks'); ?>", value: "project8", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Projects.xin_attach_files'); ?>", value: "project9", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Projects.xin_note'); ?>", value: "project10", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_project_timelogs'); ?>", value: "project11", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?php echo "mile stone"; ?>", value: "project12", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?php echo "invoice"; ?>", value: "project13", }
						]
					},
				]
			},
			//Tasks
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_tasks'); ?>", add_info: "", value: "hr_tasks", items: [
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_tasks'); ?>", value: "task1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "task1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "task2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "task3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "task4", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_update_status'); ?>", value: "task5", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Projects.xin_discussion'); ?>", value: "task6", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Projects.xin_attach_files'); ?>", value: "task7", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Projects.xin_note'); ?>", value: "task8", }
						]
					},

				]
			},
			//MileStone
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?php echo "Mile Stone"; ?>", add_info: "", value: "hr_milestone", items: [
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?php echo "milestone"; ?>", value: "milestone1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "milestone1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "milestone2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "milestone3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "milestone4", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_update_status'); ?>", value: "milestone5", },
						]
					},

				]
			},

			// Attendance
			{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_attendance'); ?>", value: "attendance", },

			// Mannual Attendance
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_update_attendance'); ?>", value: "upattendance1", items: [
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "upattendance1", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "upattendance2", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "upattendance3", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "upattendance4", },
				]
			},
			// Monthly Attendance
			{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_month_timesheet_title'); ?>", value: "monthly_time", },

			//Holidays
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_holidays'); ?>", add_info: "", value: "hr_holidays", items: [
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_holidays'); ?>", value: "holiday1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "holiday1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "holiday2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "holiday3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "holiday4", },
						]
					},
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Employees.xin_holidays_calendar'); ?>", value: "holidays_calendar", },
				]
			},


			//Manage Leave
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Leave.left_leave_request'); ?>", add_info: "", value: "hr_leave", items: [
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_manage_leaves'); ?>", value: "leave1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "leave2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "leave3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "leave4", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "leave6", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_update_status'); ?>", value: "leave7", },
						]
					},
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Leave.xin_leave_calendar'); ?>", value: "leave_calendar", },
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Leave.xin_leave_type'); ?>", value: "leave_type1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "leave_type1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "leave_type2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "leave_type3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "leave_type4", },
						]
					},
				]
			},

			//Travel
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_travels'); ?>", add_info: "", value: "hr_travel", items: [
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_travels'); ?>", value: "travel1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "travel1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "travel2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "travel3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "travel4", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_update_status'); ?>", value: "travel5", },
						]
					},
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Employees.xin_arragement_type'); ?>", value: "travel_type1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "travel_type1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "travel_type2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "travel_type3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "travel_type4", }
						]
					},
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Employees.xin_travel_calendar'); ?>", value: "travel_calendar", },
				]
			},
			// Overtime Request
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_overtime_request'); ?>", value: "overtime_req1", items: [
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "overtime_req1", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "overtime_req2", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "overtime_req3", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "overtime_req4", },
				]
			},
			//Complaints
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_complaints'); ?>", value: "hr_complaints", items: [
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "complaint1", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "complaint2", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "complaint3", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "complaint4", },
				]
			},
			//Resignations
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_resignations'); ?>", value: "hr_resignations", items: [
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "resignation1", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "resignation2", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "resignation3", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "resignation4", },
				]
			},
			//Disciplinary Cases 
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_warnings'); ?>", add_info: "", value: "hr_disciplinary", items: [
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_warnings'); ?>", value: "disciplinary1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "disciplinary1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "disciplinary2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "disciplinary3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "disciplinary5", }
						]
					},
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Employees.xin_case_type'); ?>", value: "case_type1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "case_type1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "case_type2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "case_type3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "case_type4", },
						]
					},
				]
			},
			//Transfers 
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_transfers'); ?>", value: "hr_transfers", items: [
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "transfers1", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "transfers2", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "transfers3", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "transfers4", },
				]
			},
			//Payroll
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_payroll'); ?>", add_info: "", value: "hr_payroll", items: [
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Payroll.xin_setup_payroll'); ?>", value: "pay1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_payroll_list'); ?>", value: "pay1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_generate_payslip'); ?>", value: "pay2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "pay3", },
						]
					},
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_payslip_history'); ?>", value: "pay_history", },
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_advance_salary'); ?>", value: "hradvance_salary", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "advance_salary1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_request_advance_salary'); ?>", value: "advance_salary2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "advance_salary3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "advance_salary4", },
						]
					},
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_loan'); ?>", value: "hrloan", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "loan1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_request_advance_salary'); ?>", value: "loan2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "loan3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "loan4", },
						]
					},
				]
			},
			//Helpdesk
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.dashboard_helpdesk'); ?>", value: "hr_helpdesk", items: [
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "helpdesk1", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_create_ticket'); ?>", value: "helpdesk2", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "helpdesk3", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_view_ticket'); ?>", value: "helpdesk4", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "helpdesk5", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_update_status'); ?>", value: "helpdesk6", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Projects.xin_attach_files'); ?>", value: "helpdesk7", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Projects.xin_note'); ?>", value: "helpdesk8", }
				]
			},
			//Training Sessions
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_training'); ?>", add_info: "", value: "hr_training", items: [
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_training'); ?>", value: "training1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "training2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "training3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "training4", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "training6", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Projects.xin_note'); ?>", value: "training5", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_update_status'); ?>", value: "training7", },
						]
					},
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_trainers'); ?>", value: "trainer1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "trainer1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "trainer2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "trainer3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "trainer4", },
						]
					},
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_training_skills'); ?>", value: "training_skill1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "training_skill1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "training_skill2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "training_skill3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "training_skill4", },
						]
					},
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_training_calendar'); ?>", value: "training_calendar", },
				]
			},
			//Assets
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_assets'); ?>", add_info: "", value: "hr_assets", items: [
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_assets'); ?>", value: "asset1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "asset1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "asset2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "asset3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "asset4", },
						]
					},
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_category'); ?>", value: "asset_cat1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "asset_cat1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "asset_cat2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "asset_cat3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "asset_cat4", }
						]
					},
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Asset.xin_brands'); ?>", value: "asset_brand1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "asset_brand1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "asset_brand2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "asset_brand3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "asset_brand4", }
						]
					},
				]
			},
			//Awards
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_awards'); ?>", add_info: "", value: "hr_awards", items: [
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_awards'); ?>", value: "award1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "award1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "award2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "award3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "award4", },
						]
					},
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Employees.xin_award_type'); ?>", value: "award_type1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "award_type1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "award_type2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "award_type3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "award_type4", }
						]
					},
				]
			},

			//Moms 
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Mom.xin_mom'); ?>", value: "hr_moms", items: [
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "mom_1", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "mom_2", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "mom_3", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "mom_4", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?php echo "view"; ?>", value: "mom_5", },
				]
			},

			//end1st
		]
	});

	jQuery("#treeview_r2").kendoTreeView({
		checkboxes: {
			checkChildren: true,
			template: "<label class='custom-control custom-checkbox'><input type='checkbox' #= item.check# class='#= item.class #' name='role_resources[]' value='#= item.value #'><span class='custom-control-label'>#= item.text #</span></label>"
		},
		check: onCheck,
		dataSource: [

			// Annual Planning
			{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "Annual Planning", value: "annual_planning", },
			//Employees
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.dashboard_employees'); ?>", add_info: "", value: "hr_staff", items: [
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.dashboard_employees'); ?>", value: "staff2", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "staff2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "staff3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_view'); ?>", value: "staff4", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "staff5", },

						]
					},

					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_manage_shifts'); ?>", value: "shift1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "shift1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "shift2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "shift3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "shift4", }
						]
					},

					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_employees_exit'); ?>", value: "staffexit1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "staffexit1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "staffexit2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "staffexit3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "staffexit4", },
							<?php /*?>	{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_view');?>", value: "staffexit5",}<?php */ ?>
						]
					},

					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Employees.xin_exit_type'); ?>", value: "exit_type1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "exit_type1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "exit_type2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "exit_type3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "exit_type4", },
						]
					},

				]
			},
			//Core Hr
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.dashboard_core_hr'); ?>", add_info: "", value: "core_hr", items: [
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_department'); ?>", value: "department1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "department1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "department2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "department3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "department4", },
						]
					},
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_designation'); ?>", value: "designation1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "designation1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "designation2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "designation3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "designation4", },
						]
					},
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.header_policies'); ?>", value: "policy1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "policy1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "policy2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "policy3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "policy4", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_view_policies'); ?>", value: "policy5", }
						]
					},
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "Make Announcement", value: "news1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "news1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "news2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "news3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "news4", },
						]
					},

					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_org_chart_title'); ?>", value: "org_chart", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "Conference Booking", value: "booking", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "Documents Manager", value: "manager", },
				]
			},
			
			//Finance
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_hr_finance'); ?>", add_info: "", value: "hr_finance", items: [

					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Finance.xin_accounts'); ?>", value: "accounts1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "accounts1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "accounts2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "accounts3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "accounts4", },
						]
					},
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_acc_deposit'); ?>", value: "deposit1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "deposit1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "deposit2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "deposit3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "deposit4", },
						]
					},
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_acc_expense'); ?>", value: "expense1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "expense1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "expense2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "expense3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "expense4", },
						]
					},
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_acc_transactions'); ?>", value: "transaction1", },
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Finance.xin_dep_categories'); ?>", value: "dep_cat1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "dep_cat1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "dep_cat2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "dep_cat3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "dep_cat4", },
						]
					},
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Finance.xin_exp_categories'); ?>", value: "exp_cat1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "exp_cat1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "exp_cat2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "exp_cat3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "exp_cat4", },
						]
					},
				]
			},


			//Recruitment
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Recruitment.xin_recruitment_ats'); ?>", add_info: "", value: "hr_ats", items: [
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Recruitment.xin_new_opening'); ?>", value: "ats2", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Recruitment.xin_jobs_list'); ?>", value: "ats2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "ats3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "ats4", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "ats5", },
						]
					},
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Recruitment.xin_candidates'); ?>", value: "candidate", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Recruitment.xin_interviews'); ?>", value: "interview", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_promotions'); ?>", value: "promotion", },

				]
			},

			//Performance (PMS)
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_talent_management'); ?>", add_info: "", value: "hr_talent", items: [
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_performance_indicator'); ?>", value: "indicator1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "indicator1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "indicator2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "indicator3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "indicator4", },
						]
					},
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.left_performance_appraisal'); ?>", value: "appraisal1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "appraisal1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "appraisal2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "appraisal3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "appraisal4", },
						]
					},
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Performance.xin_competencies'); ?>", value: "competency1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "competency1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "competency2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "competency3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "competency4", },
						]
					},

					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_hr_goal_tracking'); ?>", value: "tracking1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "tracking1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "tracking2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "tracking3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "tracking4", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Performance.xin_update_rating'); ?>", value: "tracking5", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_view'); ?>", value: "tracking6", },
						]
					},
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_hr_goal_tracking_type'); ?>", value: "track_type1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "track_type1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "track_type2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "track_type3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "track_type4", },
						]
					},
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Performance.xin_goals_calendar'); ?>", value: "track_calendar", },
				]
			},

			//Clients
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Projects.xin_manage_clients'); ?>", value: "hr_clients", items: [
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "client1", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "client2", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "client3", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "client4", },
				]
			},

			//Opportunity 
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?php echo "Opportunity"; ?>", value: "hr_opportunity", items: [
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "opportunity1", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "opportunity2", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "opportunity3", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "opportunity4", },
				]
			},

			//Leads
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_leads'); ?>", value: "hr_leads", items: [
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "leads1", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "leads2", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "leads3", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "leads4", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_change_to_client'); ?>", value: "leads5", },
				]
			},

			//Invoices
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_invoices_title'); ?>", add_info: "", value: "hr_invoices", items: [
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Invoices.xin_billing_invoices'); ?>", value: "invoice1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "invoice2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Invoices.xin_create_new_invoices'); ?>", value: "invoice3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Invoices.xin_edit_invoice'); ?>", value: "invoice4", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "invoice5", },
						]
					},
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_acc_invoice_payments'); ?>", value: "invoice_payments", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_acc_calendar'); ?>", value: "invoice_calendar", },

					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_invoice_tax_type'); ?>", value: "tax_type1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "tax_type1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "tax_type2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "tax_type3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "tax_type4", },
						]
					},
				]
			},
			//Estimates
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_estimates'); ?>", value: "estimate1", items: [
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "estimate2", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_create_new_estimate'); ?>", value: "estimate3", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit_estimate'); ?>", value: "estimate4", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "estimate5", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_convert_estimate_to_invoice'); ?>", value: "estimate6", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_cancel_estimate'); ?>", value: "estimate7", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_quote_calendar'); ?>", value: "estimates_calendar", },
				]
			},
			//System Calendar
			{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_system_calendar'); ?>", value: "system_calendar", },
			//Events
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_hr_events'); ?>", add_info: "", value: "hr_events", items: [
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_hr_events'); ?>", value: "hr_event1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "hr_event1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "hr_event2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "hr_event3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "hr_event4", },
						]
					},
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Conference.xin_events_calendar'); ?>", value: "events_calendar", },
				]
			},
			
			//Visitor Book
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_visitor_book'); ?>", value: "hr_visitors", items: [
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "visitor1", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "visitor2", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "visitor3", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "visitor4", },
				]
			},
			
			//Planning Configuration
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?php echo "Planning Configuration"; ?>", add_info: "", value: "hr_planning_configuration", items: [
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?php echo "Planning Configuration"; ?>", value: "planning_configuration1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "planning_configuration1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "planning_configuration2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "planning_configuration3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "planning_configuration4", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_update_status'); ?>", value: "planning_configuration5", },

						]
					},

					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?php echo "Year Planning"; ?>", value: "year_planning1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "year_planning1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "year_planning2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "year_planning3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "year_planning4", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_update_status'); ?>", value: "year_planning5", }
						]
					},
					{
						id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?php echo "Monthly Planning" ; ?>", value: "monthly_planning1", items: [
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_role_enable'); ?>", value: "monthly_planning1", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_add'); ?>", value: "monthly_planning2", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_edit'); ?>", value: "monthly_planning3", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_delete'); ?>", value: "monthly_planning4", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_view'); ?>", value: "monthly_planning5", },
							{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_update_status'); ?>", value: "monthly_planning6", },
						]
					},

				]
			},

			//Todo List
			{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_todo_ist'); ?>", value: "todo_ist", },
			
			//System Reports
			{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Dashboard.xin_system_reports'); ?>", value: "system_reports", },
			
			//Settings 
			{
				id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.left_settings'); ?>", value: "hr_settings", items: [
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.left_settings'); ?>", value: "settings1", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.left_constants'); ?>", value: "settings2", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.left_email_templates'); ?>", value: "settings3", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_multi_language'); ?>", value: "settings4", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.header_db_log'); ?>", value: "settings5", },
					{ id: "", class: "role-checkbox custom-control-input input-light-primary", text: "<?= lang('Main.xin_currency_converter'); ?>", value: "settings6", },
				]
			},
		]
	});
	//});
	// show checked node IDs on datasource change
	function onCheck() {
		var checkedNodes = [],
			treeView = jQuery("#treeview_r1").data("kendoTreeView"),
			message;
		jQuery("#result").html(message);
	}
</script>