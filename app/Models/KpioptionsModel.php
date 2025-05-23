<?php

namespace App\Models;

use CodeIgniter\Model;

class KpioptionsModel extends Model
{

	protected $table = 'ci_performance_indicator_options';

	protected $primaryKey = 'performance_indicator_options_id';

	// get all fields of table
	protected $allowedFields = ['company_id', 'indicator_id', 'indicator_type', 'indicator_option_id', 'indicator_option_value', 'remarks', 'total_rating'];

	protected $validationRules = [];
	protected $validationMessages = [];
	protected $skipValidation = false;
}
