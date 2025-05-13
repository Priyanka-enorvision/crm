<?php

use App\Models\SystemModel;
use App\Models\UsersModel;
use App\Models\LanguageModel;
use App\Models\Form_model;

// Initialize models
$systemModel = new SystemModel();
$usersModel = new UsersModel();
$languageModel = new LanguageModel();
$formModel = new Form_model();

$session = \Config\Services::session();
$usession = $session->get('sup_username');
$router = service('router');

$xin_system = $systemModel->where('setting_id', 1)->first();
$user_info = $usersModel->where('user_id', $usession['sup_user_id'])->first();

// Fetch leads
$get_web_leads = $formModel->orderBy('id', 'desc')->findAll();

$locale = service('request')->getLocale();

?>
