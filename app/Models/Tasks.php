<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'tag',
        'estimated_hours',
        'user_id',
        'assigner_id',
        'assignee_id',
        'status'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id',  'id');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigner_id', 'id');
    }

}