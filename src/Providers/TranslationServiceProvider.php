<?php
/**
 *
 *
 * Author: MSkelton
 * Date: 2016-01-13
 * Change Log:
 *
 */

namespace Waterloomatt\Translation\Providers;

class TranslationServiceProvider extends \Illuminate\Translation\TranslationServiceProvider
{
    public function boot()
    {
        $this->app->singleton('translator', function($app)
        {
            $loader = $app['translation.loader'];
            $locale = $app['config']['app.locale'];

            $trans = new \Waterloomatt\Translation\Translator($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });

        parent::boot();
    }
}