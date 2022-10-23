<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressCodeNum extends Model
{
 protected $fillable = [
        'id',
        'phone_no',
        'address_id',
        'code'
    ];
}
