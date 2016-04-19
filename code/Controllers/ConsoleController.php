<?php

use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConsoleController
 * The Central Command Access Point and Bootstrapper.
 *
 */
class ConsoleController extends Controller
{
    /**
     * @var array
     */
    private static $allowed_actions = [
        'publish',
        'ConsoleForm',
    ];

    /**
     * @var SilverstripeApplication
     */
    protected $application;

    public function init()
    {
        parent::init();

        $this->application = new SilverstripeApplication();
    }

    public function index(SS_HTTPRequest $request)
    {
        /*
         * Lets be clear when calling commands
         */
        ini_set('display_errors', 1);

        if (!Director::is_cli()) {
            if (!Permission::check('ADMIN')) {
                return Security::permissionFailure();
            }

            return $this->callFromBrowser($request);
        }

        return $this->callFromConsole();
    }

    protected function callFromConsole()
    {
        // remove the framework/cli-script.php argument
        array_shift($_SERVER['argv']);

        $this->application->run(new ArgvInput($_SERVER['argv']), new ConsoleOutput());
    }

    protected function callFromBrowser(SS_HTTPRequest $request)
    {
        $input = new ArrayInput([
            'command' => $request->param('ID'),
        ]);

        $output = new BufferedOutput(
            OutputInterface::VERBOSITY_NORMAL,
            true // true for decorated
        );
        $this->application->run($input, $output);

        // return the output
        $converter = new AnsiToHtmlConverter();
        $content = $output->fetch();

        return ['ConsoleOutput' => $converter->convert($content)];
    }

    /**
     * @return ConsoleForm
     */
    public function ConsoleForm()
    {
        return new ConsoleForm($this, 'ConsoleForm');
    }
}
