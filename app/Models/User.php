<?php

namespace App\Models;

use Eloquent;


/**
 * App\Models\User
 *
 * @property int $RUT_USUARIO
 * @property int|null $ID_ESTADO
 * @property string|null $NOMBRES_USUARIO
 * @property string|null $APAT_USUARIO
 * @property string|null $AMAT_USUARIO
 * @property string|null $USERNAME
 * @property string $PASSWORD
 * @property string|null $FECHA_CREACION
 * @property string|null $FECHA_MODIFICACION
 * @property int|null $ID_USUARIO_CREACION
 * @property int|null $ID_USUARIO_MODIFICACION
 * @property string|null $FECHA_CADUCIDAD
 * @property int|null $INTENTOS_LOGIN
 * @property string|null $MAIL
 * @property int|null $ID_PERFIL
 * @property int|null $ID_INSTITUCION
 * @property int|null $REG_ID
 * @property string|null $REMEMBER_TOKEN
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @mixin \Eloquent
 */
class User extends Eloquent
//class User extends Eloquent implements UserInterface, RemindableInterface
{
    protected $table = 'ab_usuario';

    protected $primaryKey = 'RUT_USUARIO';
    public $timestamps = false;

    protected $hidden = array(
        'password',
        'remember_token',
        'deleted_at',
        'created_at',
        'updated_at'
    );

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->mail;
    }

}
