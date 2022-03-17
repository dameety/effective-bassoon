<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'response_body',
        'import_status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'import_status' => 'boolean',
    ];

    public function imported()
    {
        $this->update(['import_status' => true]);
    }

    public static function unImported()
    {
        return static::where('import_status', false)->get();
    }
}
