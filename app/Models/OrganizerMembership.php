<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizerMembership extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id', 'package_id', 'expiration', 
        'payment_reference', 'payment_status', 'payment_amount', 'payment_url'
    ];

    public function package() {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
