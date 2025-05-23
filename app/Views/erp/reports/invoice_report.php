<?php

use App\Models\SystemModel;
use App\Models\RolesModel;
use App\Models\UsersModel;
use App\Models\MainModel;
use App\Models\InvoicesModel;
use App\Models\ProjectsModel;

$SystemModel = new SystemModel();
$RolesModel = new RolesModel();
$UsersModel = new UsersModel();
$MainModel = new MainModel();
$ProjectsModel = new ProjectsModel();
$InvoicesModel = new InvoicesModel();

$session = \Config\Services::session();
$usession = $session->get('sup_username');
$request = \Config\Services::request();

$status = $_REQUEST['P'];
$start_date = $_REQUEST['S'];
$end_date = $_REQUEST['E'];

$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
$xin_system = erp_company_settings();
$invoice_report = invoice_report($start_date,$end_date,$status);
$ci_erp_settings = $SystemModel->where('setting_id', 1)->first();
?>
<div class="row justify-content-md-center"> 
  <div class="col-md-10"> 
    <!-- [ Invoice view ] start -->
    <div class="container">
      <div>
        <div class="card" id="printTable">
          <div class="card-header">
            <h5><img class="img-fluid" width="171" height="30" src="<?= base_url();?>uploads/logo/other/<?= $ci_erp_settings['other_logo'];?>" alt=""></h5>
          </div>
          <div class="card-body pb-0">
            <div class="row invoive-info d-pdrint-inline-flex ">
              <div class="col-md-8">
                    <h6 class="text-primary">Invoice Report :</h6>
                    <table class="table table-responsive invoice-table invoice-order table-borderless">
                        <tbody>
                            <tr class="text-primary">
                                <th>From :</th>
                                <td><?= set_date_format($start_date);?></td>
                            </tr>
                            <tr class="text-primary">
                                <th>To :</th>
                                <td><?= set_date_format($end_date);?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
              <div class="col-sm-12 col-md-12">
                <div class="table-responsive">
                  <table class="table m-b-0 f-14 b-solid requid-table">
                    <thead>
                      <tr class="text-uppercase">
                        <th>Client</th>
                        <th>Invoice#</th>
                        <th>Project</th>
                        <th>Invoice Date</th>
                        <th>Due</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($invoice_report as $_invoice) { ?>
                      <?php $_client = $UsersModel->where('user_id', $_invoice->client_id)->where('user_type', 'customer')->first();?>
                      <?php $_project = $ProjectsModel->where('project_id', $_invoice->project_id)->first();?>
                      <?php
					  // status
						if($_invoice->status==1){
							$status = '<span class="text-success">'.lang('Invoices.xin_paid').'</span>';
						} else {
							$status = '<span class="text-danger">'.lang('Invoices.xin_unpaid').'</span>';
						}?>
                      <tr>
                        <td width="150"><?= $_client['first_name'].' '.$_client['last_name'];?></td>
                        <td width="200"><?= $_invoice->invoice_number;?></td>
                        <td><?= $_project['title'];?></td>
                        <td><?= set_date_format($_invoice->invoice_date);?></td>
                        <td><?= set_date_format($_invoice->invoice_due_date);?></td>
                        <td><?= $status;?></td>
                      </tr>
                    <?php } ?> 
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row text-center d-print-none">
          <div class="col-sm-12 invoice-btn-group text-center">
            <button type="button" class="btn btn-print-invoice waves-effect waves-light btn-success m-b-10">
            <?= lang('Main.xin_print');?>
            </button>
           </div>
        </div>
      </div>
    </div>
    <!-- [ Invoice view ] end --> 
  </div>
</div>
