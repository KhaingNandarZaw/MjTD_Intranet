<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable=['id','title','description','icon','startdate','enddate','name','file','mime','size'];

   
}
