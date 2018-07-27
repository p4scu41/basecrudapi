<?php

namespace p4scu41\BaseCRUDApi\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

/**
 * CreatedUpdatedBy Trait
 *
 * @category Trait
 * @package  App\Traits
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-06-17
 */
trait CreatedUpdatedBy
{
    /**
     * If the user is logged in and the table has the column created_by
     * store the ID who created the record
     */
    public function setCreatedBy()
    {
        if (Auth::check() && Schema::hasColumn($this->getTable(), 'created_by')) {
            $this->created_by = Auth::user()->id;
        }
    }

    /**
     * If the user is logged in and the table has the column updated_by
     * store the ID who updated the record
     */
    public function setUpdatedBy()
    {
        if (Auth::check() && Schema::hasColumn($this->getTable(), 'updated_by')) {
            $this->updated_by = Auth::user()->id;
        }
    }

    /**
     * Implement the handler to creating event.
     *
     * @param Illuminate\Database\Eloquent\Model $model Model to create
     *
     * @return boolean
     *
     * @throws Exception
     */
    public static function creatingHandler($model)
    {
        $model->setCreatedBy();
        return true;
    }

    /**
     * Implement the handler to updating event.
     *
     * @param Illuminate\Database\Eloquent\Model $model Model to update
     *
     * @return boolean
     *
     * @throws Exception
     */
    public static function updatingHandler($model)
    {
        $model->setUpdatedBy();
        return true;
    }

    /**
     * Relation to get de user owner
     *
     * @return App\Models\User $user
     */
    public function createdBy()
    {
        if (Schema::hasColumn($this->getTable(), 'created_by')) {
            return $this->belongsTo(User::class, 'created_by', $this->primaryKey);
        }

        return null;
    }

    /**
     * Relation to get de user who updated
     *
     * @return App\Models\User $user
     */
    public function updatedBy()
    {
        if (Schema::hasColumn($this->getTable(), 'updated_by')) {
            return $this->belongsTo(User::class, $this->primaryKey, 'updated_by');
        }

        return null;
    }
}
