<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeApi extends Command
{
    protected $signature = 'make:api {name}';
    protected $description = 'Create Controller + Service with default CRUD';

    public function handle()
    {
        $name = ucfirst($this->argument('name'));

        $this->createService($name);
        $this->createController($name);

        $this->info("API {$name} created successfully!");
    }

    private function createService($name)
    {
        $class = "{$name}Service";
        $path = app_path("Service/{$class}.php");

        if (!File::exists(app_path('Service'))) {
            File::makeDirectory(app_path('Service'), 0755, true);
        }

        if (File::exists($path)) {
            $this->warn("Service already exists.");
            return;
        }

        $template = <<<PHP
<?php

namespace App\Service;

use App\Models\\{$name};

class {$class} extends Service
{
    protected function modelClass(): string
    {
        return {$name}::class;
    }
}

PHP;

        File::put($path, $template);
    }

    private function createController($name)
    {
        $service = "{$name}Service";
        $controller = "{$name}Controller";

        $path = app_path("Http/Controllers/{$controller}.php");

        if (File::exists($path)) {
            $this->warn("Controller already exists.");
            return;
        }

        $template = <<<PHP
<?php

namespace App\Http\Controllers;

use App\Service\\{$service};
use Illuminate\Http\Request;

class {$controller} extends Controller
{
    protected {$service} \$service;

    public function __construct({$service} \$service)
    {
        \$this->service = \$service;
    }

    public function index()
    {
        return \$this->service->index();
    }

    public function store(Request \$request)
    {
        return \$this->service->create(\$request->all());
    }

    public function show(string \$id)
    {
        return \$this->service->show(\$id);
    }

    public function update(Request \$request, string \$id)
    {
        return \$this->service->update(\$id, \$request->all());
    }

    public function destroy(string \$id)
    {
        return \$this->service->delete(\$id);
    }
}

PHP;

        File::put($path, $template);
    }
}