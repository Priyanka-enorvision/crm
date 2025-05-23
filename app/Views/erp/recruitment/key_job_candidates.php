<?php 
use App\Models\SystemModel;
use App\Models\RolesModel;
use App\Models\UsersModel;
use App\Models\AssetsModel;
use App\Models\AssetscategoryModel;

$session = \Config\Services::session();
$usession = $session->get('sup_username');

$UsersModel = new UsersModel();
$RolesModel = new RolesModel();
$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();

?>

<?php if(in_array('ats2',staff_role_resource()) || in_array('candidate',staff_role_resource()) || in_array('interview',staff_role_resource()) || in_array('promotion',staff_role_resource()) || $user_info['user_type'] == 'company') {?>

<hr class="border-light m-0 mb-3">
<?php } ?>
<div class="card user-profile-list">
  <div class="card-header with-elements"> <span class="card-header-title mr-2"><strong><?php echo lang('Main.xin_list_all');?></strong> <?php echo lang('Dashboard.left_job_candidates');?></span> </div>
  <div class="card-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
          <tr>
            <th><?php echo lang('Recruitment.xin_job_title');?></th>
            <th><?php echo lang('Recruitment.xin_candidate_name');?></th>
            <th><?php echo lang('Main.xin_email');?></th>
            <th><?php echo lang('Main.dashboard_xin_status');?></th>
            <th><?php echo lang('Recruitment.xin_cover_letter');?></th>
            <th><?php echo lang('Recruitment.xin_apply_date');?></th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
