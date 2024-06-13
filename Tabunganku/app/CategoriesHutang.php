<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoriesHutang extends Model
{
    /**
     * @var string
     */
    protected $table = 'categories_hutang';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'name'
    ];
}
