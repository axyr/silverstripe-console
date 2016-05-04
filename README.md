# silverstripe-console (supersake)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/axyr/silverstripe-console.svg)](https://scrutinizer-ci.com/g/axyr/silverstripe-console/)
[![Travis](https://img.shields.io/travis/axyr/silverstripe-console.svg)](https://travis-ci.org/axyr/silverstripe-console)
[![Packagist](https://img.shields.io/packagist/dt/axyr/silverstripe-console.svg)](https://packagist.org/packages/axyr/silverstripe-console)
[![Packagist](https://img.shields.io/packagist/v/axyr/silverstripe-console.svg)](https://packagist.org/packages/axyr/silverstripe-console)
[![Packagist](https://img.shields.io/badge/unstable-dev--master-orange.svg)](https://packagist.org/packages/axyr/silverstripe-console)

Interact with your Silverstripe application from the command line with supersake.

![Screenshot](https://raw.githubusercontent.com/axyr/silverstripe-console/master/images/console.png)

## Installation

### composer install
```
$ composer require axyr/silverstripe-console
```

Run this from within your webroot:
```
$ php framework/cli-script.php dev/build
$ cp ./console/publish/supersake supersake
```

Now protect the supersake file

### update .htaccess
```
# Deny access to supersake
<Files supersake>
	Order allow,deny
	Deny from all
</Files>
```
### update web.config
```
<fileExtensions allowUnlisted="true" >
    ...
    <add fileExtension="supersake" allowed="false"/>
    ...
</fileExtensions>
```

You should now be able to show a list of available commands by running this from your webroot :

```
$ php supersake
```

## Summary

Create DataObjects, Pages and more from predefined or custom stubs with:

```
$ php supersake make:dataobject MyCustomDataObject
```

List DataObjects, Controllers, Config and more in a nice table style list with:

```
$ php supersake list:controller
```


## Further information

[Documentation](docs/en/Index.md)

For the Code of Conduct, see [CodeOfConduct](docs/en/CodeOfConduct.md)

For contributing, see [Contributing](CONTRIBUTING.md)

For further documentation information, see the [docs](docs/en/Index.md)
