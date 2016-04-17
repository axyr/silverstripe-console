# Extending console

You can create your own commands by extending the SilverstripeCommand class.

```php
class HelloWorldCommand extends SilverstripeCommand
{
    protected $name = 'say:hello';
    
    protected $description = 'Say hello to someone';
    
    public function handle()
    {
        $this->info('hello : fix this todo');
    }
}
```
