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
In your views, use Laravel's translation helper as you normally would. Nothing needs to change here. 
```
{{ trans('messages.pageTitle') }}
```

Now, the fun bit! In this example, you can override the 'pageTitle' translation by specifying which controller or action (or both) a translation should apply to. 

In your messages.php translation file, override the `pageTitle` translation by specifying a controller or action (or both).
```php 
return [
  'pageTitle'                                   => 'My Application!'  // Applies to all pages
  'controller:search_key:pageTitle'             => 'Search',          // Applies to /search/{any_action}
  'action:index_key:pageTitle'                  => 'All Index Pages!',// Applies to /{any_controller}/index
  'controller:user_action:update_key:pageTitle' => 'Update User',     // Applies to user/update
];
```
As you can see, you can control which translation will be used by specifying the *controller* or *action* or both. The most specific translation that matches the current route will be used. 

- Each component separates its name from its value by a *colon* `controller:user`, `controller:payment`
- Each component separates itself from other components by an *underscore* `controller:user_action:update`, `controller:payment_action:decline_key:pageTitle`
- The third component is *key* which simply defines the translation key.
- Both controller and action are optional

### Important! Translations are searched by most specific to least specific. 

1. controller:name_action:name_key:name
2. action:name_key:name
3. controller:name_key:name
4. name

In the following examples, assume your current route is http://localhost/public/search/index

* Example 1
```php 
return [
  'controller:search_key:pageTitle'               => 'Search',    
  'controller:search_action:index_key:pageTitle'  => 'Search',    // this one!
];
```

* Example 2
```php 
return [
  'action:index_key:pageTitle'                    => 'Search',  
  'pageTitle'                                     => 'Search',    // this one!
];
```

* Example 3
```php 
return [
  'controller:search_action:result_key:pageTitle' => 'Search',    
  'controller:search_action:index_key:pageTitle'  => 'Search',    // this one!
];
```

* Example 4
```php 
return [
  'controller:search_key:pageTitle'               => 'Search',    
  'action:index_key:pageTitle'                    => 'Search',    // this one!
];
```
