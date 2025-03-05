<?php

namespace Kolette\Media\Providers;

use App\Support\Models;
use Kolette\Media\Http\Resources\MediaResource;
use Kolette\Media\Models\Media;
use Kolette\Media\Policies\MediaPolicy;
use Illuminate\Support\Facades\Gate;
use Konekt\Concord\BaseBoxServiceProvider;

class ModuleServiceProvider extends BaseBoxServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->registerResources();
    }

    public function boot(): void
    {
        parent::boot();

        $this->registerPolicies();
    }

    protected function registerResources(): void
    {
        Models::registerModel(Media::class, MediaResource::class, 'media');
    }

    protected function registerPolicies(): void
    {
        Gate::policy(Media::class, MediaPolicy::class);
    }
}
