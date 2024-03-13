<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Books extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'author',
        'publication_date',
        'isbn',
        'genre',
        'description',
        'price',
        'quantity_in_stock',
        'picture_path',
        'deleted_at'
    ];

    public function getCreatedAtAttribute($val)
    {
        return Carbon::parse($val)->timestamp;
    }


    public function getUpdatedAtAttribute($val)
    {
        return Carbon::parse($val)->timestamp;
    }

    public function getPicturePathAttribute()
    {
        return url('').Storage::url($this->attributes['picture_path']);
    }

}
