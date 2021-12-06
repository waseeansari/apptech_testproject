<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use HasFactory;
    //use SoftDeletes;

    public $table = 'notes';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'user_id',
        'note',
        'image',
        //'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'note' => 'string',
        'image' => 'string',
        //'status' => 'tinyint'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
}
