<?php
/**
 * Model generated using LaraAdmin
 * Help: http://laraadmin.com
 * LaraAdmin is open-sourced software licensed under the MIT license.
 * Developed by: Dwij IT Solutions
 * Developer Website: http://dwijitsolutions.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SOP_Manual_Upload extends Model
{
    use SoftDeletes;
    
    protected $table = 'sop_manual_uploads';
    
    protected $hidden = [
    
    ];
    
    protected $guarded = [];
    
    protected $dates = ['deleted_at'];
}
