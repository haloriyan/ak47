<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id', 'title', 'description', 'cover',
        'start_date', 'end_date',
        'address', 'coordinate', 'province', 'city', 'province_id', 'city_id', 'district_id',
        'max_buy_ticket'
    ];

    public function forms() {
        // return $this->hasMany()
    }
    public function sessions() {
        return $this->hasMany(EventSession::class, 'event_id');
    }
    public function tickets() {
        return $this->hasMany(Ticket::class, 'event_id')->orderBy('price', 'ASC');
    }
    public function organizer() {
        return $this->belongsTo(Organizer::class, 'organizer_id');
    }
}
