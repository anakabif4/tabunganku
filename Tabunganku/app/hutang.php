<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hutang extends Model
{
    /**
     * @var string
     */
    protected $table = 'hutang';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'category_id', 'nominal', 'description', 'credit_date'
    ];
}
