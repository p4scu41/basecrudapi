<?php

namespace p4scu41\BaseCRUDApi\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class associated with the logs table
 *
 * @category Models
 * @package  p4scu41\BaseCRUDApi\Models
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-06-05
 */
class Log extends Model
{
    /**
     * Disable created_at column
     *
     * @inheritDoc
     */
    const UPDATED_AT = null;

    public $fillable = [
        'message',
        'level',
        'level_name',
        'context',
        'channel',
        'extra',
        'user_id',
        'url',
        'method',
        'ip',
        'data',
        'user_agent',
        'php_sapi_name',
        'user_process',
    ];
}
