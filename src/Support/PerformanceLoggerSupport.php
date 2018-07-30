<?php

namespace p4scu41\BaseCRUDApi\Support;

use Illuminate\Database\Eloquent\Model;
use Performance\Config;
use Performance\Performance;
use ReflectionMethod;

/**
 * Use bvanhoekelen/performance
 * Work with PerformanceLoggerFinish Middleware
 *
 * @package p4scu41\BaseCRUDApi\Support
 * @author  Pascual Pérez <pasperezn@gmail.com>
 *
 * public string $class
 * public string $function
 * public string $label
 * public array $points
 * public array $params
 * public array $values
 * public boolean $query_log_enabled
 * private array $params_values
 * public int $time
 * public int $memory
 *
 * @method public void getParamsValues()
 * @method public void start(string $label = null)
 * @method public void message(string $message)
 * @method public array getInfo()
 * @method public void addPoint(string $label)
 * @method public void finishPoint()
 * @method public void saveAll()
 */
class PerformanceLoggerSupport extends Model
{
    /**
     * @inheritDoc
     */
    protected $table = 'performance';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'label',
        'time',
        'memory',
        'memory_peak',
        'messages',
        'queries',
    ];

    /**
     * @inheritDoc
     */
    const UPDATED_AT = null;

    /**
     * @var string
     */
    public $class = '';

    /**
     * @var string
     */
    public $function = '';

    /**
     * @var string
     */
    public $label = '';

    /**
     * Function arguments
     *
     * @var array
     */
    public $params = [];

    /**
     * Labels
     *
     * @var array
     */
    public $points = [];

    /**
     * Arguments Values
     *
     * @var array
     */
    public $values = [];

    /**
     * Whether log sql queries
     *
     * @var boolean
     */
    public $query_log_enabled = false;

    /**
     * List of arguments with their respective values
     *
     * @var array
     */
    private $params_values = [];

    /**
     * @var int
     */
    public $time;

    /**
     * @var int
     */
    public $memory;

    /**
     * Info after finish
     *
     * @var int
     */
    public $result;

    /**
     * Build $params_values
     *
     * @return void
     */
    public function getParamsValues()
    {
        // If $params are empty and function exists in the class
        // retrieve function information
        if (empty($this->params) && method_exists($this->class, $this->function)) {
            $method = new ReflectionMethod($this->class, $this->function);

            if ($method) {
                foreach ($method->getParameters() as $parameter) {
                    $this->params[$parameter->getPosition()] = $parameter->getName();
                }
            }
        }

        foreach ($this->params as $index => $value) {
            $this->params_values[] = $this->params[$index].(
                    isset($this->values[$index]) ? '='.$this->values[$index] : ''
                );
        }
    }

    /**
     * Runt Performance::point($this->label);
     * If $this->label is empty, use $class and $function
     *
     * @param string $label Default null
     *
     * @return void
     */
    public function start($label = null)
    {
        Config::setQueryLog($this->query_log_enabled);

        // If label no set, take the name of class and function
        $this->label = $label ?: $this->class.'::'.$this->function;

        $this->addPoint($this->label);
    }

    /**
     * Facade of Performance::message($message)
     *
     * @param string $message
     *
     * @return void
     */
    public function message($message)
    {
        Performance::message($message);
    }

    /**
     * Return the information of performance
     * Time, Memory, MemoryPeak, Messages, Queries
     *
     * @return array
     */
    public function getInfo()
    {
        $pointsCollect = collect(Performance::export()->points()->get());
        // There are some points created by default, exclude them
        $points = $pointsCollect->filter(function ($item, $key) {
            return in_array($item->getLabel(), $this->points);
        })->toArray();
        $this->result = [];

        if (!empty($points)) {
            foreach ($points as $point) {
                $messages     = $point->getNewLineMessage();
                $this->time   = round($point->getDifferenceTime(), 3); // Seconds
                $this->memory = round($point->getDifferenceMemory()/1024/1024); // Convert to MB
                $queries      = collect($point->getQueryLog())->map(function ($item, $key) {
                    return number_format($item->time, 3) . 'ms -> ' .
                        StringSupport::sqlReplaceBindings($item->query, $item->bindings). ';' ;
                })->toArray();

                $info         = [
                    'label'       => $point->getLabel() . '(' . implode(', ', $this->params_values) . ')',
                    'time'        => $this->time, // Seconds
                    'memory'      => $this->memory, // MB
                    'memory_peak' => round($point->getMemoryPeak()/1024/1024),
                    'messages'    => implode(PHP_EOL, $messages),
                    'queries'     => implode(PHP_EOL, $queries),
                ];

                $this->result[] = $info;
            }
        }

        Performance::instanceReset();

        return $this->result;
    }

    /**
     * Facade of Performance::finish();
     *
     * @return void
     */
    public function finish()
    {
        // Get the parameters info
        $this->getParamsValues();

        Performance::finish();
    }

    /**
     * Run Performance::point($label);
     *
     * @param string $label
     *
     * @return void
     */
    public function addPoint($label)
    {
        // Clean label, replace \ with _
        $label = str_replace('\\', '_', $label);
        // Add the label to the points list
        $this->points[] = $label;

        Performance::point($label);
    }

    /**
     * Facade of Performance::finish();
     *
     * @return void
     */
    public function finishPoint()
    {
        Performance::finish();
    }

    /**
     * Insert all performance points
     *
     * @return void
     */
    public function saveAll()
    {
        if (!empty($this->result)) {
            foreach ($this->result as $item) {
                self::create($item);
            }
        }
    }
}
