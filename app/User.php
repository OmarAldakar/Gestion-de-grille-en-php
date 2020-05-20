<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPasswordNotification;
use App\Notifications\CustomVerifyNotification;
use App\UE;

class User extends Authenticatable
{
    use Notifiable;

    protected $attributes = [
        'confirmed' => false,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','prenom'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function admin() {
        return $this->hasOne('App\Admin');
    }

    /*
    * Les UEs pour lesquel cet utilisateur est responsable (faire UEs->get())
    */
    public function UEs() {
        return $this->belongsToMany('App\UE', 'ue_user', 'user_id', 'ue_id');
    }

    /*
    * Retourne les ues pour lesquel cet utilisateur n'est pas responsable 
    */
    public function nonResponsable() {
        return UE::all() -> diff ($this -> UEs () -> get());
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

        /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyNotification());
    }

}
