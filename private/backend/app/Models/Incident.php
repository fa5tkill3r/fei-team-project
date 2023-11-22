<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'name',
        'email',
        'surname',
        'type',
    ];

    protected $rules = [
        'title' => 'required|string',
        'description' => 'required|string',
        'name' => 'required|string',
        'email' => 'required|string',
        'surname' => 'required|string',
        'type' => 'nullable|string',
    ];
    
    public function validate($data)
    {
        return Validator::make($data, $this->rules);
    }

    public function images()
    {
        return $this->hasMany(IncidentImage::class);
    }
}
