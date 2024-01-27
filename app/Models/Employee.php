<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees';
    protected $fillable =[
        'first',
        'last',
        'category_id'
    ];
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
