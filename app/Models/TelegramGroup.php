<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramGroup extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'title'];

    protected $primaryKey = 'title';

    protected $keyType = 'string';

    public $incrementing = false;
}
