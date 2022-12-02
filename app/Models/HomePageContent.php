<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePageContent extends Model
{
    protected $table = 'homepagecontent';
    protected $fillable = ['title','title1','content'];
}
