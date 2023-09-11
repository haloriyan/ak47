<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'type', 'name', 'description', 'price',
        'quantity', 'start_quantity', 'start_sale', 'end_sale'
    ];
}
