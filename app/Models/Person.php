<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function contacts()
    {
        return $this->hasMany(Contacts::class, 'people_id');
    }
}
