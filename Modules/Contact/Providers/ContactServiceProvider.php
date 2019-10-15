<?php

namespace Modules\Contact\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Contact\Entities\ContactRequest;
use Modules\Contact\Repositories\Cache\CacheContactRequestDecorator;
use Modules\Contact\Repositories\ContactRequestRepository;
use Modules\Contact\Repositories\Eloquent\EloquentContactRequestRepository;

class ContactServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->registerBindings();
//        $this->registerHtmlPackage();

    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('contact.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'contact'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/contact');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/contact';
        }, \Config::get('view.paths')), [$sourcePath]), 'contact');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/contact');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'contact');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'contact');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }


//    /**
//     * Register "iluminate/html" package.
//     */
//    private function registerHtmlPackage()
//    {
//        $this->app->register('Collective\Html\HtmlServiceProvider');
//
//        $aliases = [
//            'HTML' => 'Collective\Html\HtmlFacade',
//            'Form' => 'Collective\Html\FormFacade',
//        ];
//
//        AliasLoader::getInstance($aliases)->register();
//    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function registerBindings()
    {
        $this->app->bind(ContactRequestRepository::class, function () {
            $repository = new EloquentContactRequestRepository(new ContactRequest());

            if (! config('app.cache')) {
                return $repository;
            }

            return new CacheContactRequestDecorator($repository);
        });
// add bindings

    }
}
