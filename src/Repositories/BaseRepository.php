<?php

namespace p4scu41\BaseCRUDApi\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository as PrettusRepository;
use Prettus\Validator\Contracts\ValidatorInterface;
use Illuminate\Container\Container as Application;

/**
 * Base Repository Class
 *
 * @category Repositories
 * @package  p4scu41\BaseCRUDApi\Repositories
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-05-29
 */
class BaseRepository extends PrettusRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [];

    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        // ValidatorInterface::RULE_CREATE => [],
        // ValidatorInterface::RULE_UPDATE => [],
    ];

    /**
     * Validation Custom Messages
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Validation Custom Attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $modelClass = $this->model();

        if (empty($this->rules)) {
            $this->rules[ValidatorInterface::RULE_CREATE] = $modelClass::$rules;
            $this->rules[ValidatorInterface::RULE_UPDATE] = collect($modelClass::$rules)->map(function ($value) {
                return str_replace(['|required|', '|required', 'required|', 'required'], ['|', '', '|', ''], $value);
            })->all();
        }

        if (empty($this->messages)) {
            $this->messages = $modelClass::$messages;
        }

        if (empty($this->attributes)) {
            $this->attributes = $modelClass::$customAttributes;
        }

        if (empty($this->fieldSearchable)) {
            $model = new $modelClass();

            $this->fieldSearchable = $model->getFillable();
            unset($model);
        }

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return '';
    }

    /**
     * Add RequestCriteria
     *
     * @return void
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * FindOrFail
     *
     * @param int $id
     *
     * @return Illuminate\Database\Eloquent\Model
     *
     * @throws Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($id)
    {
        $model = $this->find($id);

        if (empty($model)) {
            throw new ModelNotFoundException();
        }

        return $model;
    }

    /**
     * Specify Validator class name of Prettus\Validator\Contracts\ValidatorInterface
     * Modified from the parent class to add messages and attributes to the validator
     *
     * @return null
     * @throws Exception
     */
    public function validator()
    {
        if (isset($this->rules) && !is_null($this->rules) && is_array($this->rules) && !empty($this->rules)) {
            if (class_exists('Prettus\Validator\LaravelValidator')) {
                $validator = app('Prettus\Validator\LaravelValidator');
                if ($validator instanceof ValidatorInterface) {
                    $validator->setRules($this->rules);
                    // Added by p4scu41
                    $validator->setMessages($this->messages);
                    $validator->setAttributes($this->attributes);

                    return $validator;
                }
            } else {
                throw new Exception(trans('repository::packages.prettus_laravel_validation_required'));
            }
        }

        return null;
    }
}
