<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_input',
        'status',
        'validation_result',
        'rejection_reason',
        'tool_id',
    ];

    protected $casts = [
        'validation_result' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }
}
