<?php

namespace Fintech\Bell\Models;

use Fintech\Bell\Facades\Bell;
use Fintech\Core\Abstracts\BaseModel;
use Fintech\Core\Enums\Bell\NotificationMedium;
use Fintech\Core\Traits\Audits\BlameableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property-read string $trigger_code
 * @property-read string $trigger_name
 */
class Template extends BaseModel implements Auditable
{
    use BlameableTrait;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    protected $casts = [
        'template_data' => 'array',
        'content' => 'array',
        'recipients' => 'array',
        'restored_at' => 'datetime',
        'enabled' => 'bool',
        'medium' => NotificationMedium::class,
    ];

    protected $hidden = ['creator_id', 'editor_id', 'destroyer_id', 'restorer_id'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * @return array
     */
    public function getLinksAttribute()
    {
        $primaryKey = $this->getKey();

        $links = [
            'show' => action_link(route('bell.templates.show', $primaryKey), __('core::messages.action.show'), 'get'),
            'update' => action_link(route('bell.templates.update', $primaryKey), __('core::messages.action.update'), 'put'),
            'destroy' => action_link(route('bell.templates.destroy', $primaryKey), __('core::messages.action.destroy'), 'delete'),
            'restore' => action_link(route('bell.templates.restore', $primaryKey), __('core::messages.action.restore'), 'post'),
        ];

        if ($this->getAttribute('deleted_at') == null) {
            unset($links['restore']);
        } else {
            unset($links['destroy']);
        }

        return $links;
    }

    public function getTriggerNameAttribute(): ?string
    {
        $trigger = Bell::trigger()->find($this->trigger_code, 'code');

        if ($trigger != null) {
            return $trigger['name'];
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
