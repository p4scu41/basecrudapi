<?php

namespace p4scu41\BaseCRUDApi\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class associated with the users table
 *
 * @category Models
 * @package  p4scu41\BaseCRUDApi\Models
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-04-04
 *
 * @property int $id
 * @property int $role_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property datetime $last_login
 * @property string $remember_token
 * @property boolean $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read \p4scu41\BaseCRUDApi\Models\Role $role
 */
class User extends BaseModelActivityLog implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    JWTSubject
{
    use Authenticatable, Authorizable, CanResetPassword, Notifiable;

    /**
     * @inheritDoc
     */
    public $table = 'users';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'last_login',
        'active',
    ];

    /**
     * @inheritDoc
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @inheritDoc
     */
    public static $rules = [
        'role_id'               => 'bail|required|integer|exists:roles,id',
        'name'                  => 'bail|required|min:3|max:55',
        'email'                 => 'bail|required|min:3|max:40|email|unique:users',
        'password'              => 'bail|required|min:6|max:61|confirmed',
        'active'                => 'bail|required|integer|in:0,1',
    ];

    /**
     * inheritDoc
     */
    public static $messages = [
        'role_id.required'           => 'No se especificó el Rol de Usuario',
        'role_id.exis'               => 'El Rol de Usuario especificado no existe',
        'password_confirmation.same' => 'Las Contraseñas no Coinciden',
    ];

    /**
     * inheritDoc
     */
    public static $customAttributes = [
        'role_id'               => 'Tipo de Usuario',
        'name'                  => 'Nombre',
        'email'                 => 'E-mail',
        'password'              => 'Contraseña',
        'password_confirmation' => 'Confirmar Contraseña',
        'active'                => 'Activo',
        'last_login'            => 'Último Acceso',
        'created_at'            => 'Fecha de Registro',
    ];

    /**
     * @inheritDoc
     */
    public static function creatingHandler($model)
    {
        // Encrypt the password before create
        $model->password = bcrypt($model->password);

        return true;
    }

    /**
     * @inheritDoc
     */
    public static function updatingHandler($model)
    {
        // Encrypt the password before update
        if ($model->isDirty('password')) {
            $model->password = bcrypt($model->password);
        }

        return true;
    }

    /**
     * Role Model related with role_id
     *
     * @return App\Models\Role $role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Check if the user is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->active == 1;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
