<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeComponentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:components {name : Nome base para Controller, Service e Repository}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria Controller, Service e Repository com o nome informado.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        // Define os caminhos dos arquivos
        $controllerPath = app_path("Http/Controllers/{$name}Controller.php");
        $servicePath    = app_path("Services/{$name}Service.php");
        $repositoryPath = app_path("Repositories/{$name}Repository.php");

        // Cria os diretórios, se não existirem
        if (!File::exists(app_path("Services"))) {
            File::makeDirectory(app_path("Services"), 0755, true);
            $this->info("Diretório Services criado!");
        }
        if (!File::exists(app_path("Repositories"))) {
            File::makeDirectory(app_path("Repositories"), 0755, true);
            $this->info("Diretório Repositories criado!");
        }

        // Cria o Controller
        if (File::exists($controllerPath)) {
            $this->error("Controller {$name}Controller já existe!");
        } else {
            $controllerContent = $this->populateStub($this->getControllerStub(), $name);
            File::put($controllerPath, $controllerContent);
            $this->info("Controller {$name}Controller criado com sucesso!");
        }

        // Cria o Service
        if (File::exists($servicePath)) {
            $this->error("Service {$name}Service já existe!");
        } else {
            $serviceContent = $this->populateStub($this->getServiceStub(), $name);
            File::put($servicePath, $serviceContent);
            $this->info("Service {$name}Service criado com sucesso!");
        }

        // Cria o Repository
        if (File::exists($repositoryPath)) {
            $this->error("Repository {$name}Repository já existe!");
        } else {
            $repositoryContent = $this->populateStub($this->getRepositoryStub(), $name);
            File::put($repositoryPath, $repositoryContent);
            $this->info("Repository {$name}Repository criado com sucesso!");
        }

        return 0;
    }

    /**
     * Substitui as variáveis do stub pelo nome informado.
     *
     * @param string $stub
     * @param string $name
     * @return string
     */
    protected function populateStub($stub, $name)
    {
        return str_replace('{{class}}', $name, $stub);
    }

    /**
     * Stub para o Controller.
     *
     * @return string
     */
    protected function getControllerStub()
    {
        return <<<'EOT'
<?php

namespace App\Http\Controllers;

use App\Services\{{class}}Service;
use Illuminate\Http\Request;

class {{class}}Controller extends Controller
{
    protected $service;

    public function __construct({{class}}Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        // Implemente o método index
    }

    // Outros métodos do controller
}
EOT;
    }

    /**
     * Stub para o Service.
     *
     * @return string
     */
    protected function getServiceStub()
    {
        return <<<'EOT'
<?php

namespace App\Services;

use App\Repositories\{{class}}Repository;

class {{class}}Service
{
    protected $repository;

    public function __construct({{class}}Repository $repository)
    {
        $this->repository = $repository;
    }

    // Métodos do service
}
EOT;
    }

    /**
     * Stub para o Repository.
     *
     * @return string
     */
    protected function getRepositoryStub()
    {
        return <<<'EOT'
<?php

namespace App\Repositories;

class {{class}}Repository
{
    // Métodos do repository
}
EOT;
    }
}