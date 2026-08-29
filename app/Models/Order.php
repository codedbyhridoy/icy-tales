<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\City;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',

        // Customer
        'first_name',
        'last_name',
        'email',
        'phone',

        // Shipping
        'country',
        'state_id',
        'city_id',
        'address',
        'postal_code',

        // Totals
        'subtotal',
        'shipping_charge',
        'discount',
        'total',

        // Order
        'status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_charge' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function scopeFilter(Builder $query, array $filters)
    {
        $query
            ->when($filters['search'] ?? null, function (
                Builder $query,
                $search
            ) {
                $query->where(function (Builder $query) use ($search) {

                    $query
                        ->where('order_number', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");

                    // Search registered user
                    $query->orWhereHas('user', function (
                        Builder $userQuery
                    ) use ($search) {
                        $userQuery->where(function (
                            Builder $userQuery
                        ) use ($search) {
                            $userQuery
                                ->where(
                                    'first_name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'last_name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'email',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'phone',
                                    'like',
                                    "%{$search}%"
                                );
                        });
                    });

                    // Search payment gateway
                    $query->orWhereHas('payments', function (
                        Builder $paymentQuery
                    ) use ($search) {
                        $paymentQuery->where(
                            'gateway',
                            'like',
                            "%{$search}%"
                        );
                    });
                });
            })

            ->when($filters['status'] ?? null, function (
                Builder $query,
                $status
            ) {
                $query->where('status', $status);
            })

            // Payment status now comes from payments table
            ->when($filters['payment_status'] ?? null, function (
                Builder $query,
                $status
            ) {
                $query->whereHas('payments', function (
                    Builder $paymentQuery
                ) use ($status) {
                    $paymentQuery->where('status', $status);
                });
            })

            // Payment gateway now comes from payments table
            ->when($filters['payment_method'] ?? null, function (
                Builder $query,
                $gateway
            ) {
                $query->whereHas('payments', function (
                    Builder $paymentQuery
                ) use ($gateway) {
                    $paymentQuery->where('gateway', $gateway);
                });
            })

            ->when($filters['state_id'] ?? null, function (
                Builder $query,
                $state
            ) {
                $query->where('state_id', $state);
            })

            ->when($filters['city_id'] ?? null, function (
                Builder $query,
                $city
            ) {
                $query->where('city_id', $city);
            })

            ->when($filters['from_date'] ?? null, function (
                Builder $query,
                $date
            ) {
                $query->whereDate('created_at', '>=', $date);
            })

            ->when($filters['to_date'] ?? null, function (
                Builder $query,
                $date
            ) {
                $query->whereDate('created_at', '<=', $date);
            })

            ->when($filters['min_total'] ?? null, function (
                Builder $query,
                $amount
            ) {
                $query->where('total', '>=', $amount);
            })

            ->when($filters['max_total'] ?? null, function (
                Builder $query,
                $amount
            ) {
                $query->where('total', '<=', $amount);
            })

            ->when($filters['sort_by'] ?? null, function (
                Builder $query,
                $sortBy
            ) use ($filters) {
                $sortType = $filters['sort_type'] ?? 'desc';

                $query->orderBy($sortBy, $sortType);
            });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
