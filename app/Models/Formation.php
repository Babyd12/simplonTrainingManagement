<?php

namespace App\Models;

use App\Models\Adminor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'startDate', 'endDate',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'formations_users')->withTimestamps();
    }

    // public function admin() : BelongsTo
    // {
    //     return $this -> belongsTo(User::class);
    // }
}
