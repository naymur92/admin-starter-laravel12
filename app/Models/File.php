<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class File extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'file_id';

    protected $fillable = [
        'operation_name',
        'table_id',
        'path',
        'name',
        'size',
        'info',
        'created_by',
        'deleted_by',
    ];

    public function remove(?int $deletedBy = null): ?bool
    {
        if (file_exists(public_path($this->path . '/' . $this->name))) {
            unlink(public_path($this->path . '/' . $this->name));
        }

        $this->deleted_by = $deletedBy ?? Auth::id();
        return $this->delete();
    }


    /**
     * User who uploaded the file.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User who deleted the file.
     */
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
