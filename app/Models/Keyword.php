<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;
    public $table = 'keywords';
    protected $fillable = [
        'url',
        'keyword',
        'google_rank',
        'google_results',
        'yahoo_rank',
        'yahoo_results',
    ];

}
