<?php

namespace arsatapi\Providers;
use arsatapi\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        ///
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        if(Schema::hasTable('users')){
            $usuarios = User::all();
            $usr_sinPermisos = [];
            foreach($usuarios as $user){
                $permisos = $user->getAllPermissions();
                if( count($permisos) == 0){
                    array_push($usr_sinPermisos, $user->employeeid);
                }
            }
            config(['usr_sinPermisos' => $usr_sinPermisos]);
        }
       
    }

}
