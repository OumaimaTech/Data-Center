<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'category_id',
        'manager_id',
        'specifications',
        'status',
        'location',
        'description',
        'is_active'
    ];

    protected $casts = [
        'specifications' => 'array',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function maintenancePeriods()
    {
        return $this->hasMany(MaintenancePeriod::class);
    }
}
