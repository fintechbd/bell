<?php

namespace Fintech\Bell\Models;

use Fintech\Core\Abstracts\BaseModel;
use Fintech\Core\Traits\BlameableTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Trigger extends BaseModel implements Auditable
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

    protected $casts = ['trigger_data' => 'array', 'restored_at' => 'datetime', 'enabled' => 'bool'];

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
    public function triggerActions(): HasMany
    {
        return $this->hasMany(config('fintech.bell.trigger_action_model', TriggerAction::class));
    }

    public function triggerRecipients(): HasMany
    {
        return $this->hasMany(config('fintech.bell.trigger_recipient_model', TriggerRecipient::class));
    }

    public function triggerVariables(): HasMany
    {
        return $this->hasMany(config('fintech.bell.trigger_variable_model', TriggerVariable::class));
    }

    public function notificationTemplates(): HasMany
    {
        return $this->hasMany(config('fintech.bell.notification_template_model', NotificationTemplate::class));
    }

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
            'show' => action_link(route('bell.triggers.show', $primaryKey), __('restapi::messages.action.show'), 'get'),
            'update' => action_link(route('bell.triggers.update', $primaryKey), __('restapi::messages.action.update'), 'put'),
            'destroy' => action_link(route('bell.triggers.destroy', $primaryKey), __('restapi::messages.action.destroy'), 'delete'),
            'restore' => action_link(route('bell.triggers.restore', $primaryKey), __('restapi::messages.action.restore'), 'post'),
        ];

        if ($this->getAttribute('deleted_at') == null) {
            unset($links['restore']);
        } else {
            unset($links['destroy']);
        }

        return $links;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
