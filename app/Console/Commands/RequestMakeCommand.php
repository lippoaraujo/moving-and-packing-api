<?php

namespace App\Console\Commands;

use App\Console\Commands\Support\Stub;
use Illuminate\Support\Str;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Commands\GeneratorCommand;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RequestMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of 'name' argument.
     *
     * @var string
     */
    protected $argumentName = 'request';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-request-custom';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new request custom stub for the specified module.';

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
            ['request', InputArgument::REQUIRED, 'The name of request will be created.'],
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
        $namespace = $this->getClassNamespace($module) . '\\' . Str::studly($this->argument('request'));

        return (new Stub('/request-custom.stub', [
            'NAME'              => $this->getRequestName(),
            'NAMESPACE'         => $namespace,
            'CLASS'             => $this->getClass(),
            'MODEL'             => Str::studly($this->argument('request')),
            'LOWER_NAME'        => $module->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
        ]))->render();
    }

    /**
     * @return mixed|string
     */
    private function getRequestName()
    {
        $name = Str::studly($this->argument('request'));

        if (Str::contains(strtolower($name), 'request') === false) {
           $name = "{$name}Request";
        }

        return $name;
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $modelPath = GenerateConfigReader::read('request');
        $modelPath = $modelPath->getPath() . '/' . Str::studly($this->argument('request'));

        return $path . $modelPath . '/' . $this->getRequestName() . '.php';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        $module = $this->laravel['modules'];
        return $module->config('paths.generator.request.namespace')
        ?: $module->config('paths.generator.request.path', 'Http/Requests');
    }
}
