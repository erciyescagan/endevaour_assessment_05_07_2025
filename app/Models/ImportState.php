<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportState extends Model
{
    protected $fillable = [
        'file_path',
        'converted_file_path',
        'last_processed_index',
    ];

    protected $casts = [
        'last_processed_index' => 'integer',
    ];
    
    protected $table = 'import_states';
}
