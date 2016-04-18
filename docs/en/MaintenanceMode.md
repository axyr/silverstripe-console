#Maintenance Mode

You can put the website in Maintenance Mode with :

```
$ php supersake down
```

And getting it back online again with :

```
$ php supersake up
```

##How it works

When you put the site in Maintenance Mode a file will be written to mysite, so the MaintenanceModeExtension can check if the site is published or not.

If the file is found, a 503 response will be thrown with the contents of the assets/error-503.html file.
If that ErrorPage does not exist, it will show a generic message.

If you don't use the CMS or don't want to have 503 ErrorPage, you can set a custom file to pull in with :

```
Config::inst()->update('MaintenanceMode', 'file', 'path/to/503content.file');
```

##Excluding Ip addresses

You can exclude Ip aaddresses with :

```
Config::inst()->update('MaintenanceMode', 'allowed_ips', array(
    192.168.0.1,
    123.456.789.0
));
```
