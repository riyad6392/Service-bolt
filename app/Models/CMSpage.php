<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CMSpage extends Model
{
    protected $table = 'cmspage';
    protected $fillable = ['pagename','description','status'];
}
