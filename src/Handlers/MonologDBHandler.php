<?php

namespace p4scu41\BaseCRUDApi\Handlers;

use Illuminate\Support\Facades\Auth;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use p4scu41\BaseCRUDApi\Models\Log;
use Illuminate\Http\Request;

/**
 * Monolog DataBase Handler
 *
 * @category Handlers
 * @package  p4scu41\BaseCRUDApi\Handlers
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-06-05
 */
class MonologDBHandler extends AbstractProcessingHandler
{
    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(array $record)
    {
        $log = new Log();

        $log->fill($record);

        $log->context       = json_encode($record['context']);
        $log->extra         = json_encode($record['extra']);
        $log->user_id       = request()->user() ? request()->user()->id: null;
        $log->url           = request()->fullUrl();
        $log->method        = request()->method();
        $log->ip            = request()->ip();
        $log->data          = json_encode(request()->all());
        $log->user_agent    = request()->server('HTTP_USER_AGENT');
        $log->php_sapi_name = php_sapi_name();
        $log->user_process  = function_exists('posix_getpwuid') ?
            (posix_getpwuid(posix_geteuid())['name'] ) : // Linux
            getenv('USERNAME'); // Windows

        $log->save();
    }
}
