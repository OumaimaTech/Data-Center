<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function approvedReservations()
    {
        return $this->hasMany(Reservation::class, 'approved_by');
    }

    public function managedResources()
    {
        return $this->hasMany(Resource::class, 'manager_id');
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function resolvedIncidents()
    {
        return $this->hasMany(Incident::class, 'resolved_by');
    }

    public function createdMaintenancePeriods()
    {
        return $this->hasMany(MaintenancePeriod::class, 'created_by');
    }

    public function processedAccountRequests()
    {
        return $this->hasMany(AccountRequest::class, 'processed_by');
    }
}
