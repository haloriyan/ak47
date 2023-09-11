<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizerTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'organizer_id', 'role'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function organizer() {
        return $this->belongsTo(Organizer::class, 'organizer_id');
    }
}
