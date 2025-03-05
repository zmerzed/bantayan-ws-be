<?php

namespace Kolette\Auth\Providers;

use App\Support\Models;
use Kolette\Auth\Console\Commands\AclSync;
use Kolette\Auth\Http\Resources\PasswordResetResource;
use Kolette\Auth\Http\Resources\UserResource;
use Kolette\Auth\Middleware\VerificationTokenMiddleware;
use Kolette\Auth\Models\PasswordReset;
use Kolette\Auth\Models\User;
use Kolette\Auth\Policies\UserPolicy;
use Kolette\Media\Http\Resources\MediaResource;
use Kolette\Media\Models\Media;
use Illuminate\Support\Facades\Gate;
use Konekt\Concord\BaseBoxServiceProvider;
use Spatie\Permission\Middlewares\RoleMiddleware;

class ModuleServiceProvider extends BaseBoxServiceProvider
{
    protected $models = [
        User::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->app->register(SanctumServiceProvider::class);

        if (!app()->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../resources/config/permission.php', 'permission');
            $this->mergeConfigFrom(__DIR__ . '/../resources/config/media-library.php', 'media-library');
            $this->mergeConfigFrom(__DIR__ . '/../resources/config/oauth.php', 'oauth');
        }

        $this->registerResources();
        $this->registerCommands();
        $this->registerPolicies();
    }

    public function boot(): void
    {
        parent::boot();

        config()->set('auth.guards.api.driver', 'sanctum');
        config()->set('auth.providers.users.model', User::class);
        $this->app->alias(VerificationTokenMiddleware::class, 'verification-token');
        $this->app->alias(RoleMiddleware::class, 'user.role');
    }

    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AclSync::class,
            ]);
        }
    }

    protected function registerResources(): void
    {
        Models::registerModel(User::class, UserResource::class, 'users');
        Models::registerModel(Media::class, MediaResource::class, 'media');
        Models::registerModel(PasswordReset::class, PasswordResetResource::class, 'password_resets');
    }

    protected function registerPolicies(): void
    {
        Gate::policy(User::class, UserPolicy::class);

        Gate::define('update-onboarding-details', function (User $user) {
            return !$user->isOnboarded();
        });
    }
}
