<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Send extends Model
{
    protected $table = 'values';

    protected $fillable = ['id', 'peso', 'type', 'l1', 'l2', 'l3', 'l4'];
}
