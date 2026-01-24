<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction_code',
        'user_id',
        'cashier_id',
        'transaction_type',
        'payment_method',
        'payment_status',
        'payment_proof',
        'subtotal',
        'shipping_cost',
        'total',
        'shipping_destination',
        'shipping_address',
        'recipient_name',
        'recipient_phone',
        'weight_kg',
        'order_status',
        'notes',
        'verified_at',
        'verified_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'weight_kg' => 'integer',
        'verified_at' => 'datetime',
    ];

    /**
     * Boot method to auto-generate transaction_code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (!$transaction->transaction_code) {
                // Generate invoice code: INV-YYYYMMDD-XXX
                $date = now()->format('Ymd');
                $count = static::whereDate('created_at', now())->count() + 1;
                $transaction->transaction_code = 'INV-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Relationships
     */
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Accessors
     */
    
    public function getPaymentProofUrlAttribute()
    {
        if ($this->payment_proof) {
            return asset('storage/' . $this->payment_proof);
        }
        return null;
    }

    public function getTotalProfitAttribute()
    {
        return $this->details->sum(function ($detail) {
            return ($detail->price - $detail->cost_price) * $detail->quantity;
        });
    }

    /**
     * Scopes
     */
    
    public function scopeOffline($query)
    {
        return $query->where('transaction_type', 'offline');
    }

    public function scopeOnline($query)
    {
        return $query->where('transaction_type', 'online');
    }

    public function scopePending($query)
    {
        return $query->where('order_status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('payment_status', 'verified');
    }

    public function scopeCompleted($query)
    {
        return $query->where('order_status', 'completed');
    }

    public function scopeByCashier($query, $cashierId)
    {
        return $query->where('cashier_id', $cashierId);
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('user_id', $customerId);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Helper methods
     */
    
    public function markAsVerified($verifiedBy)
    {
        $this->payment_status = 'verified';
        $this->verified_at = now();
        $this->verified_by = $verifiedBy;
        $this->cashier_id = $verifiedBy; // Assign to kasir who verified
        $this->save();
    }

    public function markAsRejected($verifierId = null)
    {
        $this->payment_status = 'rejected';
        $this->order_status = 'cancelled';
        if ($verifierId) {
            $this->verified_at = now();
            $this->verified_by = $verifierId;
        }
        $this->save();
    }

    public function updateOrderStatus($status)
    {
        $this->order_status = $status;
        $this->save();
    }
}
