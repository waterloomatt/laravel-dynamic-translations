<?php

/**
 * Custom translator service for Laravel 5 that provides dynamic messages based on the current controller/action.
 *
 * Author: MSkelton
 * Date: 2016-01-13
 * Change Log:
 *
 */
namespace Waterloomatt\Translation;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

class Translator extends \Illuminate\Translation\Translator
{
    /**
     * @var array a list of translation keys to search for
     */
    protected $_translationKeys = array();

    /**
     * @var string the current controller being accessed
     */
    protected $_controller = null;

    /**
     * @var string the current action being accessed
     */
    protected $_action = null;

    /**
     * Get the translation for the given key.
     *
     * @param  string  $key
     * @param  array   $replace
     * @param  string|null  $locale
     * @param  bool  $fallback
     * @return string
     */
    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        list($namespace, $group, $item) = $this->parseKey($key);
        $translationKeys = $this->_buildTranslationKeys($namespace, $group, $item);
        $locales = $fallback ? array_unique($this->parseLocale($locale)) : [$locale ?: $this->locale];

        foreach ($locales as $locale) {
            $this->load($namespace, $group, $locale);

            foreach ($translationKeys as $translationKey) {
                $translation = $this->getLine($namespace, $group, $locale, $translationKey, $replace);

                if (!is_null($translation)) {
                    return $translation;
                }
            }
        }

        // A translation wasn't found for the given key; just return the key.
        return $key;
    }

    /**
     * Returns a boolean indicating if the translation is an empty string. This function will also
     * return true if no translation is defined for the given key.
     *
     * This is useful for situations where you conditionally want to output a translation. Ex:
     *  @if (!Lang::isBlank('messages.pageTitle'))
     *      <h1>{{ trans('messages.pageTitle') }}</h1>
     *  @endif
     *
     * @param $key
     * @param null $locale
     * @param bool|true $fallback
     * @return bool
     */
    public function isBlank($key, $locale = null, $fallback = true)
    {
        $translation = $this->get($key, [], $locale, $fallback);
        return (empty($translation) || $translation == $key);
    }

    /**
     * Returns an array of translation keys specific to the currently requested controller/action.
     *
     * @param $namespace
     * @param $group
     * @param $item
     * @return mixed
     */
    protected function _buildTranslationKeys($namespace, $group, $item)
    {
        if (!Arr::has($this->_translationKeys, "$namespace.$group.$item")) {
            $controller = "controller:{$this->_getController()}";
            $action = "action:{$this->_getAction()}";
            $key = "key:{$item}";

            // The order of these matter. We always search from most specific to least specific.
            Arr::set($this->_translationKeys, "$namespace.$group.$item", [
                "{$controller}_{$action}_{$key}", // controller:search_action:show_key:pageTitle
                "{$action}_{$key}",               // action:show_key:pageTitle
                "{$controller}_{$key}",           // controller:search_key:pageTitle
                "{$item}"                         // pageTitle
            ]);
        }

        return Arr::get($this->_translationKeys, "$namespace.$group.$item");
    }

    /**
     * Returns the simple name, in lowercase, of the current controller.
     * For example, if the current resource is SearchController@index then this method will return 'search'.
     *
     * @return string
     */
    protected function _getController()
    {
        if ($this->_controller === null) {
            $this->_loadCurrentResource();
            // Lowercase and strip of the word 'Controller' from the end.
            // Ex. 'SearchController' becomes 'search'
            $this->_controller = strtolower(substr($this->_controller, 0, -10));
        }

        return $this->_controller;
    }

    /**
     * Returns the name of the current action being accessed.
     *
     * @return string
     */
    protected function _getAction()
    {
        if ($this->_action === null) {
            $this->_loadCurrentResource();
        }

        return $this->_action;
    }

    /**
     * Determines and loads the current controller and action.
     */
    protected function _loadCurrentResource()
    {
        $resource = class_basename(Route::getCurrentRoute()->getActionName());
        list($this->_controller, $this->_action) = explode('@', $resource);
    }
}