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
        'attack_description',
        'predicated_attack_severity',
        'attack_category',
        'attack_type',
        'description_of_damage',
        'notes',
        'incident_created_at',
        'incident_taken_by',
        'incident_approved_by',
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
