<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaCategory extends Model
{
    use HasFactory;

    protected $table = 'spa_categories';

    protected $fillable = [
        'title',
        'image',
    ];

    public function services()
    {
        return $this->hasMany(SpaServices::class, 'category_id');
    }
}