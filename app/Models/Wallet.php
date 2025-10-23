<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Wallet extends Model
{
    // Indica que a chave primária é string e não auto-increment
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id', 'user_id', 'balance'];

    // Gera UUID automaticamente ao criar a wallet
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // Relação com usuário
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Transações enviadas
    public function sentTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'sender_wallet_id');
    }

    // Transações recebidas
    public function receivedTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'receiver_wallet_id');
    }
}
