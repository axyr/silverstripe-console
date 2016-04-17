# silverstripe-console (supersake)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/axyr/silverstripe-console.svg)](https://scrutinizer-ci.com/g/axyr/silverstripe-console/)
[![Travis](https://img.shields.io/travis/axyr/silverstripe-console.svg)](https://travis-ci.org/axyr/silverstripe-console)
[![Packagist](https://img.shields.io/badge/unstable-dev--master-orange.svg)](https://packagist.org/packages/axyr/silverstripe-console)

# Very unstable and incomplete module.....

# DO NOT USES THIS ON A LIVE ENVIRONMENT !!!

This will likely not work as it should be... Hang on... 

Interact with your Silverstripe application from the command line with supersake.

![Screenshot](https://raw.githubusercontent.com/axyr/silverstripe-console/master/images/console.png)


## Summary

Create DataObjects, Pages and more from predefined or custom stubs with:

```
$ php supersake make:dataobject MyCustomDataObject
```

List DataObjects, Controllers, Config and more in a nice table style list with:

```
$ php supersake list:controller
```

## Quick start

Install with 
```
$ composer require axyr/silverstripe-console
```

This will copy the file 'supersake' to your webroot.

Run the following command to list all other commands you can execute:

```
$ php supersake
```


## Further information

[Make Commands](docs/en/Make.md)

[Extending](docs/en/Extending.md)

For the Code of Conduct, see [CodeOfConduct](docs/en/CodeOfConduct.md)

For contributing, see [Contributing](CONTRIBUTING.md)

For further documentation information, see the [docs](docs/en/Index.md)
