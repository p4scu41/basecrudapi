<?php

namespace p4scu41\BaseCRUDApi\Support;

use Performance\Config;
use Performance\Performance;
use ReflectionMethod;

/**
 * Clase contendedora de funciones útiles
 * Hace uso de bvanhoekelen/performance
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
 * public boolean $queryLog
 * private array $paramsValues
 * public int $time
 * public int $memory
 *
 * @method public void getParamsValues()
 * @method public void start(string $label = null)
 * @method public void message(string $message)
 * @method public string getInfo()
 * @method public string addPoint(string $label)
 * @method public string finishPoint()
 */
class PerformanceLoggerSupport
{
    /**
     * Clase
     *
     * @var string
     */
    public $class = '';

    /**
     * Método
     *
     * @var string
     */
    public $function = '';

    /**
     * Label de performance
     *
     * @var string
     */
    public $label = '';

    /**
     * Parámetros de la función
     *
     * @var array
     */
    public $params = [];

    /**
     * Valores de los parametros de la función, deben de estar en el mismo orden que $params
     *
     * @var array
     */
    public $values = [];

    /**
     * Define si se logueará las consultas sql, Default true
     *
     * @var boolean
     */
    public $queryLog = false;

    /**
     * Arreglo que contiene la lista de parámetros con su correspondiente valor
     *
     * @var array
     */
    private $paramsValues = [];

    /**
     * Guarda el tiempo total de ejecución
     *
     * @var int
     */
    public $time;

    /**
     * Guarda el uso de memoria total
     *
     * @var int
     */
    public $memory;

    /**
     * Construye el arreglo $paramsValues a partir de los valores en $params y $values
     *
     * @return void
     */
    public function getParamsValues()
    {
        // Si no se proporcionó la lista de parámetros y existe el método en la clase que se especificó,
        // se obtiene la informacion de los parámetros que recibe el método de la clase
        if (empty($this->params) && method_exists($this->class, $this->function)) {
            $method = new \ReflectionMethod($this->class, $this->function);

            if ($method) {
                foreach ($method->getParameters() as $parameter) {
                    $this->params[$parameter->getPosition()] = $parameter->getName();
                }
            }
        }

        foreach ($this->params as $index => $value) {
            $this->paramsValues[] = $this->params[$index].(
                    isset($this->values[$index]) ? '='.$this->values[$index] : ''
                );
        }
    }

    /**
     * Ejecuta Performance::point($this->label);
     * Si no se pasa el valor de label, se toma la concatenación de $class y $function
     *
     * @param string $label Default null
     *
     * @return void
     */
    public function start($label = null)
    {
        // Keep track the SQL statement
        Config::setQueryLog($this->queryLog);

        // If label no set, take the name of class and function
        $this->label = $label ?: $this->class.'::'.$this->function;
        $this->points[] = $this->label;

        Performance::point($this->label);
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
     * Time, Memory, MemoryPeak, Messages, Querys
     *
     * @return string
     */
    public function getInfo()
    {
        $exportPerformance = Performance::export();
        $exportPerformanceGet = $exportPerformance->get();
        $pointsCollect = collect($exportPerformanceGet['points']);
        $points = $pointsCollect->filter(function ($item, $key) {
            return in_array($item->getLabel(), $this->points);
        })->toArray();
        $results = [];

        if (!empty($points)) {
            foreach ($points as $point) {
                $messages     = $point->getNewLineMessage();
                $this->time   = round($point->getDifferenceTime(), 3);
                $this->memory = round($point->getDifferenceMemory()/1024/1024); // Convert to MB
                $querys       = collect($point->getQueryLog())->map(function ($item, $key) {
                    return number_format($item->time, 3) . 'ms -> ' .
                    StringSupport::sqlReplaceBindings($item->query, $item->bindings). ';' ;
                })->all();

                $results[] = '[*Performance*] ===> ' .
                    $point->getLabel() . '(' . implode(', ', $this->paramsValues) . ')' . ' ' . PHP_EOL .
                    'Time: ' . $this->time . 's, ' .
                    'Memory: ' . FormatterSupport::parseBytes($this->memory) . ', '.
                    'MemoryPeak: ' . FormatterSupport::parseBytes($point->getMemoryPeak()) .
                    (empty($messages) ? '' :
                        ', ' . PHP_EOL . 'Messages: ' . PHP_EOL . "\t" . implode(PHP_EOL . "\t", $messages)) .
                    (empty($querys) ? '' :
                        ', ' . PHP_EOL . 'Querys '. count($querys) . ': ' . PHP_EOL . "\t" . implode(PHP_EOL . "\t", $querys));
            }
        }

        Performance::instanceReset();

        return PHP_EOL . implode(PHP_EOL, $results);
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
     * Ejecuta Performance::point($label);
     *
     * @param string $label
     *
     * @return void
     */
    public function addPoint($label)
    {
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
}
