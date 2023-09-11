<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id', 'name', 'description', 'username', 'icon', 'cover', 'type', 'contact_phone', 'contact_email'
    ];

    public function teams() {
        return $this->hasMany(OrganizerTeam::class, 'organizer_id');
    }
    public function membership() {
        return $this->hasOne(OrganizerMembership::class, 'organizer_id')
        ->where('payment_status', 'PAID')
        ->orderBy('created_at', 'DESC');
    }
}
