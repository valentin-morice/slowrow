<?php

namespace App\Models;

use App\Enums;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => Enums\FileType::class,
        ];
    }
}
