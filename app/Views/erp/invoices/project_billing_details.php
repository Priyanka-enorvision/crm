<?php

use App\Models\SystemModel;
use App\Models\UsersModel;
use App\Models\InvoicesModel;
use App\Models\ProjectsModel;
use App\Models\ConstantsModel;
use App\Models\InvoiceitemsModel;


$request = \Config\Services::request();
$SystemModel = new SystemModel();
$UsersModel = new UsersModel();
$InvoicesModel = new InvoicesModel();
$ProjectsModel = new ProjectsModel();
$ConstantsModel = new ConstantsModel();
$InvoiceitemsModel = new InvoiceitemsModel();

/* Company Details view
*/
$session = \Config\Services::session();
$usession = $session->get('sup_username');
$request = \Config\Services::request();
$segment_id = $ifield_id;
// $segment_id = $request->getUri()->getSegment(3);
/////
$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();

$logo_details = $SystemModel->where('company_id', $user_info['company_id'])->first();
// $invoice_id = udecode($segment_id);
$invoice_id = $segment_id;
$xin_system = erp_company_settings();
$result = $InvoicesModel->where('invoice_id', $invoice_id)->first();
$company_info = $UsersModel->where('user_id', $result['client_id'])->where('user_type', 'customer')->first();

$invoice_items = $InvoiceitemsModel->where('project_id', $result['project_id'])->where('invoice_id', $invoice_id)->findAll();

$ci_erp_settings = $SystemModel->where('setting_id', 1)->first();

$address_1 = $company_info['address_1'] . ' ' . $company_info['address_2'];
$csz = $company_info['city'] . ' ' . $company_info['state'] . ' ' . $company_info['zipcode'];
if ($result['status'] == 0) {
  $status = '<span class="badge badge-light-danger">' . lang('Invoices.xin_unpaid') . '</span>';
} else if ($result['status'] == 1) {
  $status = '<span class="badge badge-light-success">' . lang('Invoices.xin_paid') . '</span>';
} else {
  $status = '<span class="badge badge-light-info">' . lang('Projects.xin_project_cancelled') . '</span>';
}
$_payment_method = $ConstantsModel->where('type', 'payment_method')->where('constants_id', $result['payment_method'])->first();
?>

<div class="row justify-content-md-center print-invoice">
  <!-- [ basic-alert ] start -->
  <div class="col-md-10">
    <!-- [ Invoice ] start -->
    <div class="container">
      <div>
        <div class="card" id="printTable">
          <div class="card-header">
            <h5>
              <?= lang('Invoices.xin_view_invoice'); ?>
            </h5>
            <div class="card-header-right">
              <!-- <a href="<?= site_url('erp/print-invoice/') . $segment_id; ?>" target="_blank" class="collapsed btn btn-sm waves-effect waves-light btn-success m-0">
                <?= lang('Invoices.xin_print_download_invoice'); ?>
              </a> -->
              <a target="_blank" class="collapsed print-download waves-effect waves-light m-0" data-toggle="tooltip" title="Print & Download" style="margin-right: 10px;">
                <i class="fa fa-print"></i>
              </a>

              <?php if ($user_info['user_type'] != 'customer') { ?>
                <?php if ($result['status'] == 0) { ?>
                  <?php if (in_array('invoice4', staff_role_resource()) || $user_info['user_type'] == 'company') { ?>
                    <button type="button" class="btn btn-sm " data-toggle="modal" data-invoice_id="<?php echo $segment_id; ?>" data-target=".view-modal-data"
                      style="background-color:#3d8bdc;color:white;">
                      <?= lang('Invoices.xin_pay_invoice'); ?>
                    </button>
                  <?php } ?>
                <?php } ?>
              <?php } ?>
            </div>
          </div>
          <div class="card-body" id="printArea">
            <div class="row ">
              <div class="col-md-8 invoice-contact">
                <div class="invoice-box row">
                  <div class="col-sm-12">
                    <table class="table table-responsive invoice-table table-borderless">
                      <tbody>
                        <tr>
                          <td>
                            <a href="#!">
                              <img class="img-fluid" width="171" height="30"
                                src="<?= base_url(); ?>uploads/logo/other/<?= !empty($logo_details['other_logo']) ? $logo_details['other_logo'] : 'images.png'; ?>"
                                alt="<?= isset($xin_system['company_name']) ? $xin_system['company_name'] : ''; ?>">
                            </a>
                          </td>

                        </tr>
                        <tr>
                          <td><?= isset($xin_system['company_name']) ? $xin_system['company_name'] : ''; ?></td>
                        </tr>
                        <tr>
                          <td>
                            <?= isset($xin_system['address_1']) ? $xin_system['address_1'] : ''; ?>
                            <br>
                            <?= isset($xin_system['address_2']) ? $xin_system['address_2'] : ''; ?>
                          </td>
                        </tr>
                        <tr>
                          <td><a class="text-secondary" href="mailto:<?= isset($xin_system['email']) ? $xin_system['email'] : ''; ?>" target="_top">
                              <?= isset($xin_system['email']) ? $xin_system['email'] : ''; ?>
                            </a></td>
                        </tr>
                        <tr>
                          <td><?= isset($xin_system['contact_number']) ? $xin_system['contact_number'] : ''; ?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="col-md-4"></div>
            </div>
            <div class="row invoive-info d-print-inline-flex">
              <div class="col-sm-4 invoice-client-info">
                <h6>
                  <?= lang('Main.xin_client_info'); ?>
                  :</h6>
                <h6 class="m-0">
                  <?= $company_info['first_name'] . ' ' . $company_info['last_name']; ?>
                </h6>
                <p class="m-0 m-t-10">
                  <?= $address_1; ?>
                  <br>
                  <?= $csz; ?>
                </p>
                <p class="m-0">
                  <?= $company_info['contact_number']; ?>
                </p>
                <p><a class="text-secondary" href="mailto:<?= $company_info['email']; ?>" target="_top">
                    <?= $company_info['email']; ?>
                  </a></p>
              </div>
              <div class="col-sm-4">
                <h6>
                  <?= lang('Main.xin_order_info'); ?>
                  :</h6>
                <table class="table table-responsive invoice-table invoice-order table-borderless">
                  <tbody>
                    <tr>
                      <th><?= lang('Main.xin_e_details_date'); ?>
                        :</th>
                      <td><?= $result['created_at']; ?></td>
                    </tr>
                    <tr>
                      <th><?= lang('Main.dashboard_xin_status'); ?>
                        :</th>
                      <td><?= $status; ?></td>
                    </tr>
                    <?php if ($result['status'] == 1) { ?>
                      <tr>
                        <th><?= lang('Invoices.xin_payment'); ?>
                          :</th>
                        <td><?= $_payment_method['category_name']; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <div class="col-sm-4">
                <h6 class="m-b-20">
                  <?= lang('Invoices.xin_invoice_number'); ?>
                  <span>#
                    <?= $result['invoice_number']; ?>
                  </span>
                </h6>
                <h6 class="text-uppercase text-primary">
                  <?= lang('Main.xin_total'); ?>
                  : <span>
                    <?= number_to_currency($result['grand_total'], $xin_system['default_currency'], null, 2); ?>
                  </span> </h6>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="table-responsive">
                  <table class="table invoice-detail-table">
                    <thead>
                      <tr class="thead-default">
                        <th><?= lang('Main.xin_description'); ?></th>
                        <th><?= lang('Main.xin_qty'); ?></th>
                        <th><?= lang('Invoices.xin_amount'); ?></th>
                        <th><?= lang('Main.xin_total'); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($invoice_items as $item) { ?>
                        <tr>
                          <td>
                            <h6>
                              <?= $item['item_name']; ?>
                            </h6>
                          </td>
                          <td><?= $item['item_qty']; ?></td>
                          <td><?= number_to_currency($item['item_unit_price'], $xin_system['default_currency'], null, 2); ?></td>
                          <td><?= number_to_currency($item['item_sub_total'], $xin_system['default_currency'], null, 2); ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <table class="table table-responsive invoice-table invoice-total">
                  <tbody>
                    <tr>
                      <th><?= lang('Invoices.xin_subtotal'); ?>
                        :</th>
                      <td><?= number_to_currency($result['sub_total_amount'], $xin_system['default_currency'], null, 2); ?></td>
                    </tr>
                    <tr>
                      <th><?= lang('Invoices.xin_tax'); ?>
                        (0%) :</th>
                      <td><?= number_to_currency($result['total_tax'], $xin_system['default_currency'], null, 2); ?></td>
                    </tr>
                    <tr>
                      <th><?= lang('Invoices.xin_discount'); ?>
                        (0%) :</th>
                      <td><?= number_to_currency($result['total_discount'], $xin_system['default_currency'], null, 2); ?></td>
                    </tr>
                    <tr class="text-info">
                      <td>
                        <hr />
                        <h5 class="text-primary m-r-10">
                          <?= lang('Main.xin_total'); ?>
                          :</h5>
                      </td>
                      <td>
                        <hr />
                        <h5 class="text-primary">
                          <?= number_to_currency($result['grand_total'], $xin_system['default_currency'], null, 2); ?>
                        </h5>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <h6>
                  <?= lang('Invoices.xin_terms_condition'); ?>
                  :</h6>
                <p>
                  <?= $xin_system['invoice_terms_condition']; ?>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- [ Invoice ] end -->
  </div>
  <!-- [ basic-alert ] end -->
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $('.print-download').on('click', function() {
    var printContents = document.getElementById('printArea').innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
  });
</script>