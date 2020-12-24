<?php

namespace App\Console\Commands;

use App\Console\Commands\Support\Stub;
use Illuminate\Support\Str;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Commands\GeneratorCommand;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RepositoryMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of 'name' argument.
     *
     * @var string
     */
    protected $argumentName = 'repository';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class for the specified module.';

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

        $this->handleOptionalInterface();
        $this->handleOptionalModel();
        $this->handleOptionalFactory();

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
            ['repository', InputArgument::REQUIRED, 'The name of repository will be created.'],
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
            ['interface', 'i', InputOption::VALUE_NONE, 'Flag to create repository interface.', null],
            ['model', 'm', InputOption::VALUE_NONE, 'Flag to create repository model.', null],
            ['factory', 'f', InputOption::VALUE_NONE, 'Flag to create repository factory.', null],
        ];
    }

    /**
     * Create the model file with the given repository if model flag was used
     */
    private function handleOptionalModel()
    {
        if ($this->option('model') === true) {

            $this->call('module:make-model', [
                'model' => $this->argument('repository'),
                'module' => $this->argument('module')
            ]);
        }
    }

    /**
     * Create the repository contract file with the given repository if interface flag was used
     */
    private function handleOptionalInterface()
    {
        if ($this->option('interface') === true) {

            $this->call('module:make-repository-contract', [
                'repository' => $this->argument('repository'),
                'module' => $this->argument('module')
            ]);
        }
    }

    /**
     * Create the factory file with the given repository if factory flag was used
     */
    private function handleOptionalFactory()
    {
        if ($this->option('factory') === true) {

            $this->call('module:make-factory-custom', [
                'factory' => $this->argument('repository'),
                'module' => $this->argument('module')
            ]);
        }
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/repository.stub', [
            'NAME'              => $this->getRepositoryName(),
            'NAMESPACE'         => $this->getClassNamespace($module),
            'CLASS'             => $this->getClass(),
            'MODEL'             => Str::studly($this->argument('repository')),
            'LOWER_NAME'        => $module->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
        ]))->render();
    }

    /**
     * @return mixed|string
     */
    private function getRepositoryName()
    {
        $repository = Str::studly($this->argument('repository'));

        if (Str::contains(strtolower($repository), 'eloquent', 'repository') === false) {
           $repository = "Eloquent{$repository}Repository";
        }

        return $repository;
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $modelPath = GenerateConfigReader::read('repository-eloquent');

        return $path . $modelPath->getPath() . '/' . $this->getRepositoryName() . '.php';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        $module = $this->laravel['modules'];
        return $module->config('paths.generator.repository-eloquent.namespace') ?: $module->config('paths.generator.repository-eloquent.path', 'Repositories/Eloquent');
    }
}
