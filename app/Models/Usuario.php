<?php

namespace App\Models;


use Eloquent;

/**
 * App\Models\Usuario
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usuario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usuario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Usuario query()
 * @mixin \Eloquent
 */
class Usuario extends Eloquent
//class Usuario extends Eloquent implements UserInterface, RemindableInterface
{
    protected $table = 'ab_usuario';
    protected $primaryKey = 'RUT_USUARIO';
    public $timestamps = false;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');

    public function PerfilesNC()
    {
        return $this->hasOne('App\Models\PerfilNC', 'RUT_USUARIO', 'RUT_USUARIO');
    }

    public function Comunas()
    {
        return $this->hasMany('App\Models\UsuarioComuna', 'RUT_USUARIO', 'RUT_USUARIO');
    }

    public function Perfil()
    {
        return $this->hasOne('App\Models\Perfil', 'ID_PERFIL', 'ID_PERFIL');
    }

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
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberRunCiudadano()
    {
        return $this->remember_run_ciudadano;
    }

    public function setRememberRunCiudadano($value)
    {
        $this->remember_run_ciudadano = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

}
