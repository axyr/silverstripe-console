<?php


use Symfony\Component\Console\Input\InputOption;

class MakeFormCommand extends AbstractMakeCommand
{
    /**
     * @var string
     */
    protected $name = 'make:form';

    /**
     * @var string
     */
    protected $description = 'Create a new Form class and optional template';

    protected function writeFile($target, $class)
    {
        parent::writeFile($target, $class);

        if ($controller = $this->option('controller')) {
            $filePath = $this->findControllerFilePath($controller);
            if ($filePath !== false) {
                $this->addFormMethodToController($controller, $class);
                $content = file_get_contents($filePath);

                $content = $this->addFormMethodToController($content, $class);
                $this->info($class.' method added to ', $controller);
                $content = $this->addAllowedActionsToController($content, $class);
                $this->warn('Adding allowed_actions not supported yet');

                file_put_contents($filePath, $content);
            }
        }
    }

    /**
     * @param string $content
     * @param string $formClass
     *
     * @return string
     */
    protected function addFormMethodToController($content, $formClass)
    {
        if (!Str::contains($content, "function $formClass(")) {
            $methodStub = $this->getFormMethodStub($formClass);
            $replacement = $methodStub."\n}";
            if ($methodStub) {
                $content = Str::replaceLast('}', $replacement, $content);
            }
        }

        return $content;
    }

    /**
     * @param string $content
     * @param string $formClass
     *
     * @return string
     */
    protected function addAllowedActionsToController($content, $formClass)
    {
        $allowedActions = 'private static $allowed_actions';

        if (!Str::contains($content, $allowedActions)) {
        }

        return $content;
    }

    /**
     * @param $controller
     *
     * @return bool|string
     */
    protected function findControllerFilePath($controller)
    {
        $controller = strtolower($controller);
        $classes = SS_ClassLoader::instance()->getManifest()->getClasses();
        $filePath = isset($classes[$controller]) ? $classes[$controller] : '';

        if (!is_file($filePath)) {
            $this->error("$filePath does not exist");

            return false;
        }

        return $filePath;
    }

    protected function getFormMethodStub($formClass)
    {
        $file = $this->getStubFilePath('ControllerFormMethods');

        if (is_file($file)) {
            return str_replace('DummyClass', $formClass, file_get_contents($file));
        }
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        $options = parent::getOptions();
        $options[] = ['template', 't', InputOption::VALUE_NONE, 'Create a custom template for this Form'];
        $options[] = ['controller', 'C', InputOption::VALUE_REQUIRED, 'Add FormMethod and allowed actions to the given Controller'];

        return $options;
    }
}
