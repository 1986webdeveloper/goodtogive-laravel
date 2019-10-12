<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Short description for class
 *
 * Long description for class (if any)...
 *
 * @copyright  2019 acquaintsoft Pvt. Ltd
 * @license    http://example.com   PHP License 1.0
 * @version    Release: @package_version@
 * @link       http://example.com
 * @since      Class available since Release 1.0.0
 */ 
class EmailTemplate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'template', 'status', 'is_deleted', 'created_at', 'updated_at'
    ];
}
