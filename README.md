# MX Helper Bar

The MX Helper bar is intended to be used to show store admins and operators what pipeline instance and compile mode is enabled for the store.

## Installation

Add the repository to the `composer.json` as the module is not part of packagist just yet

```json
    "repositories": [        
        {
            "type": "git",
            "url": "git@github.com:inviqa/MX_HelperBar.git"
        }
    ],    
```

Then add the module to the require section to ensure it gets loaded.

```json
   "require": {
        "mx/helper-bar": "1.*"
   }
```

For each of the instances that you want to be displayed you need to modify `app/etc/env.php` and set the variable `HELPER_BAR` to be
what value you want to be shown in the admin for each of the pipeline instances.

```php
<?php
return array (
  // ...
  'HELPER_BAR' => 'YOUR VALUE',
  // ...
```
