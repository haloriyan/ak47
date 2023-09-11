<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 'description', 'price_yearly', 'price_monthly',
        'commission_fee', 'max_team_members', 'download_report_ability', 'max_file_size'
    ];
}
