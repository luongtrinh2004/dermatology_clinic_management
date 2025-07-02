<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'service_id',
        'date',
        'time',
        'note'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}