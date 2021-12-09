<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use HasFactory;
    use SoftDeletes;

	protected $fillable = array('first_name','last_name','description');

	// protected $hidden = ['created_at','updated_at'];

    protected $dates = ['deleted_at'];

    public function profile() {
        return  $this->hasOne(Profile::class);
    }
}


