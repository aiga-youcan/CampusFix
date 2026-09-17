<?php

namespace App\Providers;

use App\Models\Signalement;
use App\Models\Intervention;
use App\Policies\SignalementPolicy;
use App\Policies\InterventionPolicy;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Mappage des Modèles avec leurs Policies de sécurité respectives
     */
    protected $policies = [
        Signalement::class  => SignalementPolicy::class,
        Intervention::class => InterventionPolicy::class,
    ];

    /**
     * Enregistrement des services d'authentification et d'autorisation (RBAC)
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Définition explicite des Gates de contrôle d'accès
        Gate::define('admin-only', fn (User $user) => $user->hasRole('admin'));
        Gate::define('tech-access', fn (User $user) => $user->hasRole(['admin', 'technicien']));
        Gate::define('declare-signalement', fn (User $user) => true);
        
        // Seul le rôle technicien peut exécuter une intervention
        Gate::define('intervene', fn (User $user) => $user->hasRole('technicien'));
    }
}