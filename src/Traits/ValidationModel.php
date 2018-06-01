<?php

namespace p4scu41\BaseCRUDApi\Traits;

use p4scu41\BaseCRUDApi\Exceptions\ValidationModelException;
use Validator;

/**
 * ValidationModel Trait
 *
 * @category Trait
 * @package  p4scu41\BaseCRUDApi\Traits
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-05-28
 */
trait ValidationModel
{
    /**
     * The validator instance
     *
     * @var Illuminate\Validation\Validator
     */
    public $validator;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    /**
     * Custom error messages
     *
     * @var array
     */
    public static $messages = [];

    /**
     * Custom attributes names
     *
     * @var array
     */
    public static $customAttributes = [];

    /**
     * Undocumented function
     *
     * @param array $data
     *
     * @return void
     * @throws p4scu41\BaseCRUDApi\Exceptions\ValidationModelException
     */
    public function validate($data = null)
    {
        $this->validator = Validator::make($data ?: $this->getAttributes(), static::$rules, static::$messages, static::$customAttributes);

        if ($this->validator->fails()) {
            throw new ValidationModelException($this->validator, $this);
        }
    }
}
