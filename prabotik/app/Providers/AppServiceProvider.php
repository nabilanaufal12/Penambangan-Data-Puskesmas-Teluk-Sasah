<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $role = session('active_user_role', 'farmasi');
            $user = User::where('role', $role)->first();
            if (!$user) {
                $user = (object)[
                    'name' => $role === 'kepala' ? 'dr. Bambang Utoyo, M.M.' : 'Apt. Siti Rahmawati, S.Farm',
                    'role' => $role,
                    'jabatan' => $role === 'kepala' ? 'Kepala UPTD Puskesmas Teluk Sasah' : 'Penanggung Jawab Farmasi & Pengelola Obat',
                    'nip' => $role === 'kepala' ? 'NIP. 19780512 200604 1 008' : 'SIP. 19890415/SIPA/2018/021',
                    'email' => $role === 'kepala' ? 'kapus@puskesmas-teluksasah.go.id' : 'farmasi@puskesmas-teluksasah.go.id',
                ];
            }
            $view->with('currentUser', $user);
        });
    }
}
