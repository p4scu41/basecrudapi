<?php

namespace p4scu41\BaseCRUDApi\Traits;

use p4scu41\BaseCRUDApi\Support\PerformanceLoggerSupport;
use p4scu41\BaseCRUDApi\Support\LoggerSupport;

/**
 * Create PerformanceLogger instance
 *
 * @package p4scu41\BaseCRUDApi\Traits
 * @author  Pascual Pérez <pasperezn@gmail.com>
 *
 * @property boolean $is_tracking_performance
 * @property \p4scu41\BaseCRUDApi\Support\PerformanceLoggerSupport $performanceLogger
 * @property \Monolog\Logger $debug_logger
 * @property \Monolog\Logger $error_logger
 *
 * @method public void initLogger(string $function = '')
 * @method public void addMessageToPerformanceLogger(string $data)
 */
trait PerformanceLoggerTrait
{
    /**
     * If true, track of performance (Time, Memory usage and Query log)
     * if no set, it tackes the value of config app.debug
     *
     * @var boolean
     */
    public $is_tracking_performance;

    /**
     * Instance of PerformanceLogger to get time, memory usage and query log
     *
     * @var App\Helpers\PerformanceLogger
     */
    public $performanceLogger;

    /**
     * Whether to log the sql queries
     *
     * @var boolean
     */
    public $queryLog;

    /**
     * Instance of Monolog\Logger to debug log
     *
     * @var Monolog\Logger
     */
    public $debug_logger;

    /**
     * Instance of Monolog\Logger to debug log
     *
     * @var Monolog\Logger
     */
    public $error_logger;

    /**
     * Initialize the logger
     *
     * @param string $function Current function name
     *
     * @return void
     */
    public function initLogger($function = '')
    {
        $this->debug_logger = $this->debug_logger ?: LoggerSupport::createLogger($this, '.debug');
        $this->error_logger = $this->error_logger ?: LoggerSupport::createLogger($this, '.error');

        if (!isset($this->is_tracking_performance)) {
            $this->is_tracking_performance = config('app.debug');
        }

        // \Log::info(json_encode($this));
        // \Log::info(json_encode($this->is_tracking_performance));
        // \Log::info(json_encode($this->performanceLogger));

        // Avoid instance twice
        if ($this->is_tracking_performance && empty($this->performanceLogger)) {
            $this->performanceLogger           = new PerformanceLoggerSupport();
            $this->performanceLogger->class    = get_class($this);
            $this->performanceLogger->function = $function;
            $this->performanceLogger->queryLog = isset($this->queryLog) ? $this->queryLog : true;

            $this->performanceLogger->start();
        }
    }


    /**
     * Shorcut for $this->performanceLogger->message($data);
     *
     * @param string $data String to add as message
     *
     * @return void
     */
    public function addMessageToPerformanceLogger($data)
    {
        if ($this->is_tracking_performance) {
            $this->performanceLogger->message($data);
        }
    }
}
