<?php

namespace App;

use DB;

//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class ServiceImages extends Model
{
    protected $guarded = ["id"];
    public $timestamps = false;
    protected $table = "service_images";

}
