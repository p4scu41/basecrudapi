<?php

namespace p4scu41\BaseCRUDApi\Exceptions;

use Illuminate\Validation\ValidationException;

/**
 * Validation Model Exception Class
 *
 * @category Exceptions
 * @package  p4scu41\BaseCRUDApi\Exceptions
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-04-04
 */
class ValidationModelException extends ValidationException
{
    /**
     * The invalid model
     *
     * @var Illuminate\Database\Eloquent\Model
     */
    public $model;

    /**
     * Create a new instance.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @param \Illuminate\Database\Eloquent\Model        $model
     *
     * @return void
     */
    public function __construct($validator, $model)
    {
        parent::__construct($validator);

        $this->model   = $model;
        $this->code    = $this->status;
        $this->message = collect($this->errors())->reduce(function ($carry, $item) {
            return $carry . implode(', ', $item) . ' ';
        });
    }
}
