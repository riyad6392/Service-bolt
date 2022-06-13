<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManageChecklist extends Model
{
    protected $table = 'adminchecklist';
    protected $fillable = ['checklist','image','status'];
}
