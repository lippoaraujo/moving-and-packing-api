<?php

namespace App\Console\Commands;

use App\Console\Commands\Support\Stub;
use Illuminate\Support\Str;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Commands\GeneratorCommand;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ServiceMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of 'name' argument.
     *
     * @var string
     */
    protected $argumentName = 'service';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service custom stub for the specified module.';

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

        $this->handleOptionalController();
        $this->handleOptionalTest();
        $this->handleOptionalRepositoryInterface();
        $this->handleOptionalFactory();
        $this->handleOptionalModel();
        $this->handleOptionalRepository();
        $this->handleOptionalRequest();
        $this->handleOptionalMigration();

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
            ['service', InputArgument::REQUIRED, 'The name of service will be created.'],
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
            ['controller', 'c', InputOption::VALUE_NONE, 'Flag to create custom controller.', null],
            ['request', 'R', InputOption::VALUE_NONE, 'Flag to create request.', null],
            ['model', 'm', InputOption::VALUE_NONE, 'Flag to create model.', null],
            ['test', 't', InputOption::VALUE_NONE, 'Flag to create custom test.', null],
            ['repository', 'r', InputOption::VALUE_NONE, 'Flag to create repository.', null],
            ['interface', 'i', InputOption::VALUE_NONE, 'Flag to create repository interface.', null],
            ['factory', 'f', InputOption::VALUE_NONE, 'Flag to create custom factory.', null],
            ['migration', 'M', InputOption::VALUE_NONE, 'Flag to create migration.', null],
            ['all', 'a', InputOption::VALUE_NONE, 'Flag to create all options.', null],
        ];
    }

    /**
     * Create the controller file with the given service if controller flag was used
     */
    private function handleOptionalController()
    {
        if ($this->option('controller') === true || $this->option('all') === true) {

            $this->call('module:make-controller-custom', [
                'controller' => $this->argument('service'),
                'module' => $this->argument('module')
            ]);
        }
    }

    /**
     * Create the test file with the given service if test flag was used
     */
    private function handleOptionalTest()
    {
        if ($this->option('test') === true || $this->option('all') === true) {

            $this->call('module:make-test-custom', [
                'test' => $this->argument('service'),
                'module' => $this->argument('module')
            ]);
        }
    }

    /**
     * Create the repository interface file with the given service if repository interface flag was used
     */
    private function handleOptionalRepositoryInterface()
    {
        if ($this->option('interface') === true || $this->option('all') === true) {

            $this->call('module:make-repository-contract', [
                'repository' => $this->argument('service'),
                'module' => $this->argument('module')
            ]);
        }
    }

    /**
     * Create the factory interface file with the given service if factory interface flag was used
     */
    private function handleOptionalFactory()
    {
        if ($this->option('factory') === true || $this->option('all') === true) {

            $this->call('module:make-factory-custom', [
                'factory' => $this->argument('service'),
                'module' => $this->argument('module')
            ]);
        }
    }

    /**
     * Create the request interface file with the given service if request interface flag was used
     */
    private function handleOptionalRequest()
    {
        if ($this->option('request') === true || $this->option('all') === true) {

            $this->call('module:make-request-custom', [
                'request' => $this->argument('service'),
                'module' => $this->argument('module')
            ]);
        }
    }

    /**
     * Create the model interface file with the given service if model interface flag was used
     */
    private function handleOptionalModel()
    {
        if ($this->option('model') === true || $this->option('all') === true) {

            $this->call('module:make-model-custom', [
                'model' => $this->argument('service'),
                'module' => $this->argument('module')
            ]);
        }
    }

    /**
     * Create the repository file with the given service if repository flag was used
     */
    private function handleOptionalRepository()
    {
        if ($this->option('repository') === true || $this->option('all') === true) {

            $this->call('module:make-repository', [
                'repository' => $this->argument('service'),
                'module' => $this->argument('module')
            ]);
        }
    }

    /**
     * Create the migration file with the given service if migration flag was used
     */
    private function handleOptionalMigration()
    {
        if ($this->option('migration') === true || $this->option('all') === true) {

            $service = Str::lower(Str::plural($this->argument('service')));
            $name = "create_{$service}_table";

            $this->call('module:make-migration', [
                'name' => $name,
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

        return (new Stub('/service.stub', [
            'NAME'              => $this->getServiceName(),
            'NAMESPACE'         => $this->getClassNamespace($module),
            'CLASS'             => $this->getClass(),
            'REPOSITORY'        => Str::studly($this->argument('service')),
            'LOWER_NAME'        => $module->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
        ]))->render();
    }

    /**
     * @return mixed|string
     */
    private function getServiceName()
    {
        $repository = Str::studly($this->argument('service'));

        if (Str::contains(strtolower($repository), 'service') === false) {
           $repository = "{$repository}Service";
        }

        return $repository;
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $modelPath = GenerateConfigReader::read('service');

        return $path . $modelPath->getPath() . '/' . $this->getServiceName() . '.php';
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace() : string
    {
        $module = $this->laravel['modules'];
        return $module->config('paths.generator.service.namespace') ?: $module->config('paths.generator.service.path', 'Services');
    }
}
