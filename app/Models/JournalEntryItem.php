<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntryItem extends Model
{
    protected $table = 'journal_entry_items';

    protected $fillable = ['journal_entry_id', 'chart_of_account_id', 'debit', 'credit', 'description'];

    protected function casts(): array
    {
        return ['journal_entry_id' => 'integer', 'chart_of_account_id' => 'integer', 'debit' => 'decimal:2', 'credit' => 'decimal:2'];
    }

    public function entry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }
}
