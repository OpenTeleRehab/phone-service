<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone',
        'organization_name',
        'patient_api_url',
        'admin_api_url',
        'therapist_api_url',
        'chat_api_url',
        'chat_websocket_url',
        'clinic_id',
        'sub_domain',
    ];
}
