<?php

namespace App\Providers;

use App\Models\Signalement;
use App\Models\Intervention;
use App\Models\Salle;
use App\Policies\SignalementPolicy;
use App\Policies\InterventionPolicy;
use App\Policies\SallePolicy;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Signalement::class => SignalementPolicy::class,
        Intervention::class => InterventionPolicy::class,
        Salle::class => SallePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Définition explicite des Gates
        Gate::define('admin-only', fn (User $user) => $user->hasRole('admin'));
        Gate::define('tech-access', fn (User $user) => $user->hasRole(['admin', 'technicien']));
        Gate::define('declare-signalement', fn (User $user) => true);
        Gate::define('intervene', fn (User $user) => $user->hasRole(['admin', 'technicien']));
    }
}
