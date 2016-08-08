# MX Helper Bar

The MX Helper bar is intended to be used to show store admins and operators what pipeline instance and compile mode is enabled for the store.

Also, it allows to search and perform a list of tasks, such as clearing cache types, without having to navigate away from the page.

![Alt Text](https://github.com/inviqa/MX_HelperBar/raw/master/demo.gif)

## Installing

Then add the module to the require section of the composer file:

```shell
  $ ./php composer.phar require "mx/module-helper-bar": "~1.0.0"
```

this command will add:

```json
  "require": {
    "mx/module-helper-bar": "~1.0.0"
  }
```

## Enable

Once the module is added as dependency, run the magento setup module, clear the cache and make sure the module status is enabled.

```
  $ bin/magento setup:upgrade
  $ bin/magento c:c
  $ bin/magento module:status MX_HelperBar
```

Now that the module is enabled, you need to make the Helper Bar visible.

Navigate to: _Stores -> Configuration -> Advanced -> Developer -> Debug_
and select _"Yes"_ for the option with label _"Enabled Helper Bar for Admin"_

Then refresh the page and you will see the Helper Bar at the bottom of the screen. If you wish, you can temporary hide it by pressing the 'X' or using CTRL + ` as keyboard shortcut.

## Commands

What follows is a list of commands currently available, this will grow with time and get better with time (like wine does).

### Clear Cache

This command allow you to refresh cache types as if you were doing it from the Magento _Cache Management_ page.

Start typing 'Cache' in the Helper Bar textbox to see the list of available commmands. All the following are valid commands:

```
  > Clear Cache for: All
  > Clear Cache for: Configuration, Layouts, Blocks HTML output
  > Clear Cache for: Database DDL operations
```

### Template Path and Block Hints

This command allow you to enable or disable template path hints as you were doing it from _Stores -> Configuration -> Advanced -> Developer -> Debug_
 
For instance you choose _Template Path Hints for: Storefront Enable_ this will set:
_Enabled Template Path Hints for Storefront_ and _Add Block Names to Hints_ to 'Yes'

## Contributing

If you wish to contribute to the Helper Bar

* Fork/Clone this repository
* Install the module
* Create a feature branch with descriptive name (i.e. feature/magento-tasks)
* Make changes
* Push feature branch back to this repo
* Submit a PR with details of changes

## Contributors

* James Cowie
* Alessandro Zucca
* Richard Thompson
