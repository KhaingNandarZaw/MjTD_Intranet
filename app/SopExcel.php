<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SopExcel extends Model
{
    protected $fillable=['id','description','jobtype','timeframe','pic','participant','reportto','remark'];
}
