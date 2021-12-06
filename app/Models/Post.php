<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    //use SoftDeletes;

    public $table = 'posts';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        //'user_id',
        'post',
        'image',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'post' => 'string',
        'image' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
}
