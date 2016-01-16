# laravel-dynamic-translations
Allows your application to use dynamic translations with ease

## Installation
### Composer
Run the following to include this via Composer
```
composer require waterloomatt/translation
```
### Laravel 5 Configuration
Register the service provider with your application by adding the following line to config/app.php
```php
'providers' => [
  Waterloomatt\Translation\Providers\TranslationServiceProvider::class
```
That's it! You're good to go.

## Usage
In your views, use Laravel's translation helper as you normally would. 
```
{{ trans('messages.pageTitle') }}
```

Now, the fun bit! In this example, you can override the 'pageTitle' translation by specifying which controller or action (or both) a translation should apply to. 

In my messages.php translation file I override the translations by specifying a controller or action (or both).
```php 
return [
  'controller:search_key:pageTitle' => 'Search',              // Applied to !any! action in SearchController 
  'action:index_key:pageTitle' => 'Search',                   // Applied index action on !any! controller
  'pageTitle' => 'My Application!'                            // Applied to all pages !except! the ones overriden in this file
  'controller:user_action:update_key:pageTitle' => 'Update',  // Applied update action on Usercontroller
];
```

As you can see there are 3 components which you can use to define your translations: *controller*, *action*, and *key*. Each component separates its name from its value by a *colon*. Each component separates itself from other components by an *underscore*. The third component is *key* which simply defines the translation key.

So to summarize: **controller:**name**_action:**name**_key:**name

### Important! Translations are searched by most specific to least specific. 

1. controller:name_action:name_key:name
2. action:name_key:name
3. controller:name_key:name
4. name

In the following examples, assume your current route is http://localhost/public/search/index

* Example 1
```php 
return [
  'controller:search_key:pageTitle' => 'Search',                // Loser
  'controller:search_action:index_key:pageTitle' => 'Search',   // Winner!
];
```

* Example 2
```php 
return [
  'action:index_key:pageTitle' => 'Search',                     // Winner!
  'pageTitle' => 'Search',                                      // Loser
];
```

* Example 3
```php 
return [
  'controller:search_action:result_key:pageTitle' => 'Search',  // Loser
  'controller:search_action:index_key:pageTitle' => 'Search',   // Winner!
];
```

* Example 4
```php 
return [
  'controller:search_key:pageTitle' => 'Search',                // Loser
  'action:index_key:pageTitle' => 'Search',                     // Winner!
];
```
