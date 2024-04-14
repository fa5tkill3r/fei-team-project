<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalIncidentInfo extends Model
{
    use HasFactory;

    public $table = 'incident_additional_info';

    public $fillable = [
        'recorded_by',
        'attacked_service',
        'attack_severity',
        'attack_solution',
        'attack_description',
        'attack_vector',
        'predicated_attack_severity',
        'attack_category',
        'attack_type',
        'security_measures',
        'description_of_damage',
        'notes'
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    // make fucntions that will return the enum values
    public static function getAttackSeverityValues()
    {
        return ['low', 'medium', 'high'];
    }

}
