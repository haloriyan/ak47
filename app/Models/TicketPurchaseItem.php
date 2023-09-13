<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id', 'ticket_id', 'event_id', 'holder_id', 'unique_code', 'has_checked_in',
        'quantity', 'total_pay'
    ];

    public function purchase() {
        return $this->belongsTo(TicketPurchase::class, 'purchase_id');
    }
    public function ticket() {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
    public function holder() {
        return $this->belongsTo(User::class, 'holder_id');
    }
    public function event() {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
