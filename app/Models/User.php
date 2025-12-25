<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasFiles;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasFiles, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'type',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            0 => 'Inactive',
            1 => 'Active',
            default => 'Unknown',
        };
    }

    public function getTypeLabelAttribute()
    {
        return match ($this->type) {
            1 => 'Super Admin',
            2 => 'Admin',
            3 => 'User',
            4 => 'API User',
            default => 'Unknown',
        };
    }

    public static function getTypeOptions()
    {
        return [
            1 => 'Super Admin',
            2 => 'Admin',
            3 => 'User',
            4 => 'API User',
        ];
    }

    public function profilePicture()
    {
        return $this->hasOne(File::class, 'table_id', 'id')
            ->where('operation_name', $this->getTable())
            ->whereNull('deleted_at')
            ->orderByDesc('created_at');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
