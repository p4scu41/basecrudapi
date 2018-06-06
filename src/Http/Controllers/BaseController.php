<?php

namespace p4scu41\BaseCRUDApi\Http\Controllers;

use Illuminate\Http\Request;
use p4scu41\BaseCRUDApi\Traits\OnAfterBeforeAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

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
