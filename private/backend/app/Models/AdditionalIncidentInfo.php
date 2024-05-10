<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 *
 * AdditionalIncidentInfo ModelF
 *
 * @property int $id
 * @property int $incident_id
 * @property string $recorded_by
 * @property string $attacked_service
 * @property string $attack_severity
 * @property string $attack_description
 * @property string $predicated_attack_severity
 * @property string $attack_category
 * @property string $attack_type
 * @property string $description_of_damage
 * @property string $notes
 * @property string $incident_created_at
 * @property string $incident_taken_by
 * @property string $incident_approved_by
 * @property Incident $incident
 *
 */
class AdditionalIncidentInfo extends Model
{
    use HasFactory;

    public $table = 'incident_additional_info';

    public $fillable = [
        'incident_id',
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
