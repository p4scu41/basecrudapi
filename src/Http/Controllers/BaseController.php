<?php

namespace p4scu41\BaseCRUDApi\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use p4scu41\BaseCRUDApi\Repositories\BaseRepository;
use p4scu41\BaseCRUDApi\Traits\OnAfterBeforeAction;
use p4scu41\BaseCRUDApi\Support\PerformanceLoggerSupport;

/**
 * Controllers Base Class
 *
 * @category Controllers
 * @package  p4scu41\BaseCRUDApi\Http\Controllers
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-04-04
 */
class BaseController extends Controller
{
    use OnAfterBeforeAction;

    /**
     * BaseRepository
     *
     * @var p4scu41\BaseCRUDApi\Repositories\BaseRepository
     */
    protected $repository;

    /**
     * Current model instance.
     *
     * @var p4scu41\BaseCRUDApi\Models\BaseModel
     */
    protected $model = null;

    /**
     * If true, track performance (Time, Memory usage)
     * if no set, it tackes the value of config app.debug
     *
     * @var boolean
     */
    public $is_tracking_performance = false;

    /**
     * Wheter to log sql queries
     *
     * @var boolean
     */
    public $is_query_log = false;

    /**
     * Omit the tracking of performance in this actions
     *
     * @var array
     */
    public $except_track_performance = []; // 'create', 'show', 'edit'

    /**
     * Instance of performance to get time, memory usage and query log
     *
     * @var p4scu41\BaseCRUDApi\Support\PerformanceLoggerSupport
     */
    public $performance;

    /**
     * @param p4scu41\BaseCRUDApi\Repositories\BaseRepository
     */
    public function __construct(BaseRepository $repository, Request $request)
    {
        $this->repository = $repository;

        $route  = $request->route(); // \Illuminate\Support\Facades\Route::getCurrentRoute();
        // Remove App\ from the beginning and replace \ with _
        $className = str_replace(['App\\', '\\'], ['', '_'], get_class($this));

        // When execute php artisan route:list
        // $route is null
        if (!empty($route)) {
            $action = $route->getAction(); // \Illuminate\Support\Facades\Route::currentRouteName();
            $method = explode('@', $action['controller']); // Name of the Method in the Controller Class
            $method = $method[1];

            if ($this->is_tracking_performance && !in_array($method, $this->except_track_performance)) {
                $this->performance                    = new PerformanceLoggerSupport();
                $this->performance->class             = get_class($this);
                $this->performance->function          = $method;
                $this->performance->query_log_enabled = $this->is_query_log;

                $this->performance->start();

                // IMPORTANT: Add message after start
                $this->performance->message(json_encode($request->all()));
            }
        } else {
            // Desactivamos el perfomance cuando se ejectua php artisan route:list
            $this->is_tracking_performance = false;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request Request instance
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id Resource primary key
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request Request instance
     * @param int                      $id      Resource primary key
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id Resource primary key
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Return true if $params is instance of any:
     *  \Illuminate\Http\RedirectResponse
     *  \Illuminate\Http\Response
     *  \Illuminate\View\View
     *
     * @param mixed $param Instance of RedirectResponse, Response or View
     *
     * @return boolean
     */
    public function isValidResponse($param = null)
    {
        return $param instanceof RedirectResponse ||
               $param instanceof Response ||
               $param instanceof View;
    }
}
