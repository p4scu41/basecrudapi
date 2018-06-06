<?php

namespace p4scu41\BaseCRUDApi\Traits;

use Illuminate\Http\Request;

/**
 * Register methods to create a handlers onBeforeAction and onAfterAction:
 *    onBeforeIndex, onBeforeCreate, onBeforeStore, onBeforeShow, onBeforeEdit, onBeforeUpdate, onBeforeDestroy
 *    onAfterIndex, onAfterCreate, onAfterStore, onAfterShow, onAfterEdit, onAfterUpdate, onAfterDestroy
 *
 * @category Trait
 * @package  p4scu41\BaseCRUDApi\Traits
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-06-05
 */
trait OnAfterBeforeAction
{
    /**
     * Execute before action event
     * Example: if $action is index the method called is onBeforeIndex
     *
     * @param string                   $action  Action name to execute
     * @param \Illuminate\Http\Request $request Instance of Request with the input sent
     *
     * @return mixed
     */
    public function onBeforeAction($action, $request)
    {
        $callBack = 'onBefore' . ucfirst(strtolower($action));

        /**
         * Examples:
         *     index   -> onBeforeIndex
         *     create  -> onBeforeCreate
         *     store   -> onBeforeStore
         *     show    -> onBeforeShow
         *     edit    -> onBeforeEdit
         *     update  -> onBeforeUpdate
         *     destroy -> onBeforeDestroy
         */
        $resultBeforeAction = $this->{$callBack}($request);

        return $resultBeforeAction;
    }

    /**
     * Function executed before index
     *
     * @param \Illuminate\Http\Request $request Instance of Request with the input sent
     *
     * @return mixed
     */
    public function onBeforeIndex(Request $request)
    {
        return null;
    }

    /**
     * Function executed before create
     *
     * @param \Illuminate\Http\Request $request Instance of Request with the input sent
     *
     * @return mixed
     */
    public function onBeforeCreate(Request $request)
    {
        return null;
    }

    /**
     * Function executed before store
     *
     * @param \Illuminate\Http\Request $request Instance of Request with the input sent
     *
     * @return mixed
     */
    public function onBeforeStore(Request $request)
    {
        return null;
    }

    /**
     * Function executed before show
     *
     * @param \Illuminate\Http\Request $request Instance of Request with the input sent
     *
     * @return mixed
     */
    public function onBeforeShow(Request $request)
    {
        return null;
    }

    /**
     * Function executed before edit
     *
     * @param \Illuminate\Http\Request $request Instance of Request with the input sent
     *
     * @return mixed
     */
    public function onBeforeEdit(Request $request)
    {
        return null;
    }

    /**
     * Function executed before update
     *
     * @param \Illuminate\Http\Request $request Instance of Request with the input sent
     *
     * @return mixed
     */
    public function onBeforeUpdate(Request $request)
    {
        return null;
    }

    /**
     * Function executed before destroy
     *
     * @param \Illuminate\Http\Request $request Instance of Request with the input sent
     *
     * @return mixed
     */
    public function onBeforeDestroy(Request $request)
    {
        return null;
    }

    /**
     * Execute after action event
     * Example: if $action is index the method call is onAfterIndex
     *
     * @param string                   $action  Action name to execute
     * @param \Illuminate\Http\Request $request Instance of Request with the input sent
     *
     * @return mixed
     */
    public function onAfterAction($action, $request)
    {
        $callBack = 'onAfter' . ucfirst(strtolower($action));

        /**
         * Examples:
         *     index   -> onAfterIndex
         *     create  -> onAfterCreate
         *     store   -> onAfterStore
         *     show    -> onAfterShow
         *     edit    -> onAfterEdit
         *     update  -> onAfterUpdate
         *     destroy -> onAfterDestroy
         */
        $resultAfterAction = $this->{$callBack}($request);

        return $resultAfterAction;
    }

    /**
     * Function executed after index
     *
     * @param \Illuminate\Http\Request $request Instance of Request with the input sent
     *
     * @return mixed
     */
    public function onAfterIndex(Request $request)
    {
        return null;
    }

    /**
     * Function executed after create
     *
     * @param \Illuminate\Http\Request $request Instance of Request with the input sent
     *
     * @return mixed
     */
    public function onAfterCreate(Request $request)
    {
        return null;
    }

    /**
     * Function executed after store
     *
     * @param \Illuminate\Http\Request $request Instance of Request with the input sent
     *
     * @return mixed
     */
    public function onAfterStore(Request $request)
    {
        return null;
    }

    /**
     * Function executed after show
     *
     * @param \Illuminate\Http\Request $request Instance of Request with the input sent
     *
     * @return mixed
     */
    public function onAfterShow(Request $request)
    {
        return null;
    }

    /**
     * Function executed after edit
     *
     * @param \Illuminate\Http\Request $request Instance of Request with the input sent
     *
     * @return mixed
     */
    public function onAfterEdit(Request $request)
    {
        return null;
    }

    /**
     * Function executed after update
     *
     * @param \Illuminate\Http\Request $request Instance of Request with the input sent
     *
     * @return mixed
     */
    public function onAfterUpdate(Request $request)
    {
        return null;
    }

    /**
     * Function executed after destroy
     *
     * @param \Illuminate\Http\Request $request Instance of Request with the input sent
     *
     * @return mixed
     */
    public function onAfterDestroy(Request $request)
    {
        return null;
    }
}
