<?php

namespace App\Console\Commands;

use App\Console\Commands\Support\Stub;
use Illuminate\Support\Str;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Commands\GeneratorCommand;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModelMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of 'name' argument.
     *
     * @var string
     */
    protected $argumentName = 'model';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-model-custom';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model custom stub for the specified module.';

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
            ['model', InputArgument::REQUIRED, 'The name of model will be created.'],
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

        return (new Stub('/model-custom.stub', [
            'NAME'              => $this->getModelName(),
            'NAMESPACE'         => $this->getClassNamespace($module),
            'CLASS'             => $this->getClass(),
            'MODEL'             => Str::studly($this->argument('model')),
            'MODEL_LOWER'       => Str::lower($this->argument('model')),
            'MODEL_PLURAL'      => Str::lower(Str::plural($this->argument('model'))),
            'MODULE'            => $module->getLowerName(),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
        ]))->render();
    }

    /**
     * @return mixed|string
     */
    private function getModelName()
    {
        return Str::studly($this->argument('model'));
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $modelPath = GenerateConfigReader::read('model');

        return $path . $modelPath->getPath() . '/' . $this->getModelName() . '.php';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        $module = $this->laravel['modules'];
        return $module->config('paths.generator.model.namespace')
        ?: $module->config('paths.generator.model.path', 'Entities');
    }
}
