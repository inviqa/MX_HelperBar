#MX Helper Bar

The **MX Helper** Bar for Magento 2 is intended to help store admins and operators by:

 - showing at a glance which environment(Pipeline instance) they are using so they won't inadvertently make the changes to the wrong environment.  
 - acting as a super shortcut bar which allows you to quickly search and perform a list of common tasks, such as clearing cache types, without having to navigate away from the page.

![Demo of Bob, the quick shortcut helper bar for Magento 2](https://github.com/inviqa/MX_HelperBar/raw/master/use.gif)

## Installing
Then add the module to the require section of the composer file:

```shell
  $ ./php composer.phar require "mx/module-helper-bar": "~1.0.0"
```

This command will add:

```json
  "require": {
    "mx/module-helper-bar": "~1.0.0"
  }
```


## Enable

Once the module is added as dependency, run the Magento setup module, clear the cache and make sure the module status is enabled.

```
  $ bin/magento setup:upgrade
  $ bin/magento c:c
  $ bin/magento module:status MX_HelperBar
```

Now that the module is enabled, you need to make the Helper Bar visible.

![video of how to install ](https://github.com/inviqa/MX_HelperBar/raw/master/install.gif)

Navigate to: _Stores -> Configuration -> Advanced -> Developer -> Debug_
and select _"Yes"_ for the option with label _"Enabled Helper Bar for Admin"_

Then refresh the page and you will see the Helper Bar at the bottom of the screen. If you wish, you can temporarily hide it by pressing the 'X' or using CTRL + ` as a keyboard shortcut.

## Commands

What follows is a list of the commands currently available. This list will grow and get better with time (like wine!).

### Clear Cache

This command will allow you to refresh cache types as if you were doing it from the Magento _Cache Management_ page.

Start typing 'Cache' in the Helper Bar textbox to see the list of available commmands. All the following are valid commands:

```
  > Clear Cache for: All
  > Clear Cache for: Configuration, Layouts, Blocks HTML output
  > Clear Cache for: Database DDL operations
```

### Template Path and Block Hints

This command allow you to enable or disable template path hints as you were doing it from _Stores -> Configuration -> Advanced -> Developer -> Debug_

For instance choose _Template Path Hints for: Storefront Enable_ and this will set:
_Enabled Template Path Hints for Storefront_ and _Add Block Names to Hints_ to 'Yes'

## Contributing
By making this open source we hope others will gain value from it and use it as part of their projects.
Please share any feedback, suggestions and additions so we can help make Magento 2 even faster, easier and simpler to work with and to develop amazing e-Commerce experiences on.

If you wish to contribute to the Helper Bar:

* Fork/Clone this repository
* Install the module
* Create a feature branch with descriptive name (i.e. feature/magento-tasks)
* Make changes
* Push feature branch back to this repo
* Submit a PR with details of changes

###Any Feedback?:
Please kindly raise an issue in Github.

## Contributors

###Dev Team
* James Cowie
* Alessandro Zucca
* Richard Thompson

###Other team members who helped with the module
* Paal Soberg
* Grant Kemp
* Alex Blaney
* Andrew Bennett

#### A story with a happy ending
This module originated from a conversation between a customer and our analytics geek, Grant Kemp. Our customer was experiencing a difficulty with Magento 2 and Grant saw an opportunity to both help the customer and help improve Magento 2.
He shared his idea for a module within Inviqa. From there, James Cowie and Paal Soberg jumped on board enthusiastically and helped to evolve the ideas into a more compelling product.
This project had tremendous support from our other devs such as Alessandro Zucca and Richard Thomson who propelled it forward, drove it over the line, and made sure it looked great.

Eventually it crystallised into this first version of the Helper Bar which we are delighted to open to the Magento community.
