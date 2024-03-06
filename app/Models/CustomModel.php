<?php

namespace App\Models;

use App\Traits\BindsDynamically;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomModel extends Model
{
    use HasUuids, SoftDeletes, BindsDynamically;

    public $timestamps = true;

//    protected $fillable = [
//        'updated_at',
//        'created_at',
//    ];

    protected $guarded = [];
}
