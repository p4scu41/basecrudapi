<?php

namespace p4scu41\BaseCRUDApi\Traits;

/**
 * Register events handlers: retrieved, creating, created, updating, updated, saving, saved, deleting, deleted
 *
 * @category Trait
 * @package  p4scu41\BaseCRUDApi\Traits
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-05-28
 */
trait RegisterEventsHandlers
{
    /**
     * Register events handlers: retrieved, creating, created, updating, updated, saving, saved, deleting, deleted
     *
     * @return void
     */
    public static function registerEventsHandlers()
    {
        static::retrieved(function ($model) {
            static::retrievedHandler($model);
        });

        static::creating(function ($model) {
            static::creatingHandler($model);
        });

        static::created(function ($model) {
            static::createdHandler($model);
        });

        static::updating(function ($model) {
            static::updatingHandler($model);
        });

        static::updated(function ($model) {
            static::updatedHandler($model);
        });

        static::saving(function ($model) {
            static::savingHandler($model);
        });

        static::saved(function ($model) {
            static::savedHandler($model);
        });

        static::deleting(function ($model) {
            static::deletingHandler($model);
        });

        static::deleted(function ($model) {
            static::deletedHandler($model);
        });
    }

    /**
     * Retrieved event handler
     *
     * @param Illuminate\Database\Eloquent\Model $model
     *
     * @return boolean
     */
    public static function retrievedHandler($model)
    {
        return true;
    }

    /**
     * Creating event handler
     *
     * @param Illuminate\Database\Eloquent\Model $model
     *
     * @return boolean
     */
    public static function creatingHandler($model)
    {
        return true;
    }

    /**
     * Creating event handler
     *
     * @param Illuminate\Database\Eloquent\Model $model
     *
     * @return boolean
     */
    public static function createdHandler($model)
    {
        return true;
    }

    /**
     * Updating event handler
     *
     * @param Illuminate\Database\Eloquent\Model $model
     *
     * @return boolean
     */
    public static function updatingHandler($model)
    {
        return true;
    }

    /**
     * Updated event handler
     *
     * @param Illuminate\Database\Eloquent\Model $model
     *
     * @return boolean
     */
    public static function updatedHandler($model)
    {
        return true;
    }

    /**
     * Saving event handler
     *
     * @param Illuminate\Database\Eloquent\Model $model
     *
     * @return boolean
     */
    public static function savingHandler($model)
    {
        return true;
    }

    /**
     * Saved event handler
     *
     * @param Illuminate\Database\Eloquent\Model $model
     *
     * @return boolean
     */
    public static function savedHandler($model)
    {
        return true;
    }

    /**
     * Deleting event handler
     *
     * @param Illuminate\Database\Eloquent\Model $model
     *
     * @return boolean
     */
    public static function deletingHandler($model)
    {
        return true;
    }

    /**
     * Deleted event handler
     *
     * @param Illuminate\Database\Eloquent\Model $model
     *
     * @return boolean
     */
    public static function deletedHandler($model)
    {
        return true;
    }
}
