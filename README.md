#MX Helper Bar

The **MX Helper** Bar for Magento 2 is intended to help store admins and operators by:

 - showing at a glance which environment(Pipeline instance) they are using so they won't inadvertently make the changes to the wrong environment.  
 - acting as a super shortcut bar which allows you to quickly search and perform a list of common tasks, such as clearing cache types, without having to navigate away from the page.

![Demo of Bob, the quick shortcut helper bar for Magento 2](use.gif)


## Commands

What follows is a list of the commands currently available. This list will grow and get better with time (like wine!).

### Clear Cache (cc)

This command will allow you to refresh cache types as if you were doing it from the Magento _Cache Management_ page.

Start typing 'cc' in the Helper Bar textbox to see the list of available commmands. All the following are valid commands:

```
  > cc
  > cc configuration
  > cc database ddl operations
```

### Template Path and Block Hints (tph)

This command allow you to enable or disable template path hints as you were doing it from _Stores -> Configuration -> Advanced -> Developer -> Debug_

For instance choose _tph front en_ and this will set:
_Enabled Template Path Hints for Storefront_ and _Add Block Names to Hints_ to 'Yes'

instead, if you choose _tph en_ this will enable the template hints on the storefront and in the adminhtml site area

### Navigation shortcut (nav)

The navigation shortcut commands allow you to quickly navigate to other magento pages.
For instance choose _nav cms block_ to be redirected to the Cms Blocks page.

If you want to contribute adding more shortcuts you can easily do so by editing the _etc/di.xml_ file.

Add a new _virtualType_ (see as an example _navigation_redirect_cms_page_) and inject it as a new argument in the type _MX\HelperBar\Model\NavigationRedirectRepository_

## Installing
Then add the module to the require section of the composer file:

```shell
  $ ./php composer.phar require "mx/module-helper-bar": "~1.0.1"
```

This command will add:

```json
  "require": {
    "mx/module-helper-bar": "~1.0.1"
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

![video of how to install ](install.gif)

Navigate to: _Stores -> Configuration -> Advanced -> Developer -> Debug_
and select _"Yes"_ for the option with label _"Enabled Helper Bar for Admin"_

Then refresh the page and you will see the Helper Bar at the bottom of the screen. If you wish, you can temporarily hide it by pressing the 'X' or using CTRL + ` as a keyboard shortcut.

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

###Inspiration
Massive thanks to the Graze.com Team for inspiring this project and agreeing to let us open source the results, especially: 
* Jason Malone 
* Joe Meehan
* Joe Ferrelly 

