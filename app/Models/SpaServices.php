<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaServices extends Model
{
    use HasFactory;

    protected $table = 'spa_services';

    protected $fillable = [
        'name',
        'description',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(SpaCategory::class, 'category_id');
    }
}