#Make Commands

These commands can be used to generate various Silverstripe Object classes

Currently the following classes can be generated:
- Command - make:command
- Controller - make:controller
- ControllerExtension - make:controllerextension
- DataExtension - make:dataextension
- DataObject - make:dataobject
- Extension - make:extension
- Dataobject - make:dataobject
- Page and Controller pair - make:page
- Form - make:form
- Test - make:test

- Theme - make:theme

The commands have different options, so be sure to check the help:

```
$ php supersake make:command --help
```

##Stubs

By default the stubs from console/stubs will be used, but you can define your own.

1. by 'overloading' a stub file into mysite/stubs/
2. by setting a custom stub location and overload the stub class there:
    
```php
Config::inst()->update('MakeCommand', 'stub_dir', 'mycustom/stub/dir');
```
    
For example : is the Make Command finds a DataObject.php.stub file in your custom folder,
it will use that. If the file does not exist, it will try to find the file in mysite/stubs.
Otherwise it will fallback to the default console/stubs/DataObject.php.stub file

Of course you can create your own MakeMyCustomClassCommand as long as it follows the following naming convention:

```php
class Make{$MyCustomClass}Command extends MakeCommand
{
    protected $name = 'make:mycustomclass';
    protected $description = 'Create a new MyCustomClass class';
    
    public function getPhpStub()
    {
        return BASE_PATH . '/mymodule/stubs/MyCustomClass.php.stub';
    }
}
```

## File locations
The generated files will be stored in an opiniated directory structure within mysite/code :
- commands
- controllers
- dataobjects
- extensions
- forms
- pages

You can change the default target directories with the following Config settings :

```php
// This will only change the directory for DataObjects
Config::inst()->update('MakeCommand', 'default_dirs', [
    'DataObject' => 'mysite/code/models',
]);

// This will use the default directory structure with a different module
Config::inst()->update('MakeCommand', 'default_dirs', 'mysite');

// a setting with a / will store all generated files in 1 folder
Config::inst()->update('MakeCommand', 'default_dirs', 'mysite/code');
```

You can always set the target dir when you execute the make command:

```
$ supersake make:dataobject MyDataObject --dir=mymodule/code/models
```

Or if you want to use the default convention, you can just provide the module name :

```
$ supersake make:dataobject MyDataObject --dir=mymodule
```

## Change the 'verbosity' of the generated DataObject, Pages or DataExtension.

By default we create a pretty complete DataObject, Page or DataExtension with a lot of statics and methods stubbed.

But sometimes you just want to create a simple class with 1 or 2 db fields.

Just add the --simple or --s flag to your command to just generate a DataObject with a $db array:

```
$ php supersake make:dataobject MySimpleDataObject --simple
```

will create:

```php
<?php

class MySimpleDataObject
{
    private static $db = array(
        'Title' => 'Varchar'
    );
}
```

This works the same for DataExtensions and Pages

## Generation Pages

When you generate a PageType, the corresponding PageType_Controller will also be created.
If you add the --layout flag, you will also get a Layout/PageType.ss template file.
This will be created in the current theme, but you can change this within the command :

```
$ php supersake make:page MyCustomPage --layout --theme=mytheme
```

## Generating Controllers

## Generating Forms

## Generating a Theme skeleton

```
$ php supersake make:theme themename
```

This will generate an empty theme skeleton within the themes directory like :

- themename
  - css
    - app.css
  - images
  - javascript
    - app.js
  - templates
    - Includes
      - Footer.ss
      - Header.ss
      - Navigation.ss
    - Layout
      - Page.ss
    - Page.ss 

All includes and requirements will be added in the template files, as well as a $Menu(1) menu and default head tags.

Enough to get you started quickly, clean enough to don't start with a mess.

### Including Bootstrap, Foundation, jQuery files from cdnjs.com

Optional you can include javascripts and css frameworks from a cdn, if you really need something fast.

This command will add jQuery javascript and the Twitter Bootstrap css and javascript includes from cdnjs.com.
```
$ php supersake make:theme themename --jquery --bootstrap
```

Currently the following frameworks are supported:

- jQuery
- Twitter Bootstrap 
- or Foundation (one at the same time)

Of course you can create your own theme stub Config with your defaults or create your own command

See console/stubs/theme for an example of how the skeleton theme is constructed.
The files within you stub directory will simple be copied to the given theme directory.
