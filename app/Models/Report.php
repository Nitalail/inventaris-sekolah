<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'report_name',
        'report_type',
        'report_date', 
        'file_format',
        'file_path',
        'user_id'
    ];


// Relasi ke user
public function user()
{
    return $this->belongsTo(User::class);
}

}