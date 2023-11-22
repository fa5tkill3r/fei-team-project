<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentImage extends Model
{
    use HasFactory;
    protected $table = 'incident_images';

    protected $fillable = [
        'image',
        'incident_id',
        "path",
    ];

    protected $appends = [
        'url',
    ];


    public function getUrlAttribute(){
        return url('storage' . '/images'. '/' . $this->image);
    }

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }



}
