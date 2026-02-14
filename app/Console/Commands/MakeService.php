<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new service class';

    public function handle()
    {
        $name = $this->argument('name');
        $className = $name . 'Service';
        $path = app_path("Service/{$className}.php");

        // cria pasta se não existir
        if (!File::exists(app_path('Service'))) {
            File::makeDirectory(app_path('Service'), 0755, true);
        }

        // evita sobrescrever
        if (File::exists($path)) {
            $this->error('Service already exists!');
            return;
        }

        $template = <<<PHP
<?php

namespace App\Service;

use App\Models\\{$name};

class {$className} extends Service
{
    protected function modelClass(): string
    {
        return {$name}::class;
    }
}

PHP;

        File::put($path, $template);

        $this->info("Service {$className} created successfully!");
    }
}