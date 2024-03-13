<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transactions extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'book_id',
        'quantity',
        'total',
        'status',
        'payment_url'
    ];

    public function books()
    {
        return $this->hasOne(Books::class, 'id', 'book_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getCreatedAtAttribute($val)
    {
        return Carbon::parse($val)->timestamp;
    }

    public function getUpdatedAtAttribute($val)
    {
        return Carbon::parse($val)->timestamp;
    }
}
