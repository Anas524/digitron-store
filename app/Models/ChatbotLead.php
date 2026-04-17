<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotLead extends Model
{
    protected $fillable = [
        'product_id',
        'product_name',
        'product_sku',
        'customer_name',
        'customer_email',
        'customer_phone',
        'message',
        'source_type',
        'button_label',
        'page_type',
        'status',
        'ip_address',
        'user_agent',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
