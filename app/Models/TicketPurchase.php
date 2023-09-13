<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'discount_id', 'event_id',
        'total', 'total_pay',
        'payment_reference', 'payment_method', 'payment_channel', 'payment_field', 'payment_status'
    ];

    public function items() {
        return $this->hasMany(TicketPurchaseItem::class, 'purchase_id');
    }
    public function event() {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
