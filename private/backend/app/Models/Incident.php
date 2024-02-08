<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;


/**
 * Incident Model
 * 
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $name
 * @property string $email
 * @property string $surname
 * @property string $type
 * 
 * @method HasMany images()
 */
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
