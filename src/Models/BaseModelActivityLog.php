<?php

namespace p4scu41\BaseCRUDApi\Models;

use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Models Base ActivityLog Class
 *
 * @category Models
 * @package  p4scu41\BaseCRUDApi\Models
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-04-04
 */
class BaseModelActivityLog extends BaseModel
{
    use LogsActivity;

    /**
     * Attributes to be tracked by Activitylog
     *
     * @var array
     */
    protected static $logAttributes = [];

    /**
     * Attributes to be ignored by Activitylog
     *
     * @var array
     */
    protected static $ignoreChangedAttributes = [
        'created_at',
        'updated_at',
    ];

    /**
     * Just log the attributes modified
     *
     * @var boolean
     */
    protected static $logOnlyDirty = true;

    /**
     * Set $logAttributes with $fillable, to be tracked by Activitylog
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        self::$logAttributes = $this->fillable;
    }
}
