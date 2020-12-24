<?php

namespace App\Console\Commands;

use App\Console\Commands\Support\Stub;
use Illuminate\Support\Str;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Commands\GeneratorCommand;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TestMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of 'name' argument.
     *
     * @var string
     */
    protected $argumentName = 'test';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-test-custom';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new test custom stub for the specified module.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() : int
    {
        if (parent::handle() === E_ERROR) {
            return E_ERROR;
        }

        // $this->handleOptionalMigrationOption();

        return 0;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['test', InputArgument::REQUIRED, 'The name of test will be created.'],
            ['module', InputArgument::REQUIRED, 'The name of module will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            // ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/test-custom.stub', [
            'NAME'              => $this->getTestName(),
            'NAMESPACE'         => $this->getClassNamespace($module),
            'CLASS'             => $this->getClass(),
            'MODEL'             => Str::studly($this->argument('test')),
            'MODEL_LOWER'       => Str::lower($this->argument('test')),
            'MODEL_PLURAL'      => Str::lower(Str::plural($this->argument('test'))),
            'MODULE'            => $module->getLowerName(),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
        ]))->render();
    }

    /**
     * @return mixed|string
     */
    private function getTestName()
    {
        $name = Str::studly($this->argument('test'));

        if (Str::contains(strtolower($name), 'test') === false) {
           $name = "{$name}Test";
        }

        return $name;
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $modelPath = GenerateConfigReader::read('test');

        return $path . $modelPath->getPath() . '/' . $this->getTestName() . '.php';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        $module = $this->laravel['modules'];
        return $module->config('paths.generator.test.namespace')
        ?: $module->config('paths.generator.test.path', 'Tests/Unit');
    }
}
