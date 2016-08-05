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
        "mx/module-helper-bar": "dev-master"
   }
```

Then navigate to Stores -> Configuration -> Advanced -> Developer -> Debug
and select "Enabled Helper Bar for Admin": "Yes""
