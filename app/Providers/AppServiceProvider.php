<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->environment(['staging', 'production'])) {
            URL::forceScheme('https');
        }

        $this->mergeStrMacro();
        $this->mergeArrMacro();
        $this->mergeRequestMacro();
        $this->mergeBuilderMacros();

        $this->registerModuleLanguageFiles();
    }

    private function mergeStrMacro(): void
    {
        Str::macro('cleanPhoneNumber', function (?string $value) {
            return str_replace('+', '', $value);
        });
    }

    private function mergeArrMacro(): void
    {
        Arr::macro('snakeKeys', function ($array, $delimiter = '_') {
            $result = [];
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $value = Arr::snakeKeys($value, $delimiter);
                }
                $result[Str::snake($key, $delimiter)] = $value;
            }
            return $result;
        });
    }

    public function mergeBuilderMacros()
    {
        Builder::macro('orderByNullsLast', function ($column) {
            /** @var Builder $this */

            $column = $this->getGrammar()->wrap($column);

            return $this->orderByRaw("$column is null");
        });

        EloquentBuilder::macro('getOrPaginate', function () {
            /** @var EloquentBuilder $this */

            return $this->when(
                request()->filled('per_page') || request()->filled('limit'),
                fn ($query) => $query->paginate(request()->limit()),
                fn ($query) => $query->get()
            );
        });

        EloquentBuilder::macro('getOrSimplePaginate', function () {
            /** @var EloquentBuilder $this */

            return $this->when(
                request()->filled('per_page') || request()->filled('limit'),
                fn ($query) => $query->simplePaginate(request()->limit()),
                fn ($query) => $query->get()
            );
        });
    }

    private function mergeRequestMacro(): void
    {
        Request::macro('perPage', function ($perPage = 10) {
            return (int)request()->input('per_page', request()->input('limit', $perPage));
        });

        Request::macro('limit', function ($perPage = 100) {
            /** @var Request $this */

            return (int) $this->input('per_page', $this->input('limit', $perPage));
        });
    }

    private function registerModuleLanguageFiles(): void
    {
        $concordModules = concord()->getModules();

        /** @var \Konekt\Concord\BaseBoxServiceProvider $provider */
        foreach ($concordModules as $moduleName => $provider) {
            $langDirectory = $provider->getBasePath() . '/resources/lang';

            $moduleName = str_replace('Kolette.', '', $moduleName);
            if (is_dir($langDirectory)) {
                $this->loadTranslationsFrom($langDirectory, $moduleName);
            }
        }
    }
}
