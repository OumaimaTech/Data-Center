<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenancePeriod extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'resource_id',
        'start_date',
        'end_date',
        'description',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
