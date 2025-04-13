<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeHelperCommand extends Command
{
    protected $signature = 'make:helper {name}';
    protected $description = 'Create a new helper class';

    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path('Helpers/' . $name . '.php');

        if (File::exists($path)) {
            $this->error('Helper already exists!');
            return;
        }

        $stub = <<<EOD
<?php

namespace App\Helpers;

class {$name}
{
    // Add your helper methods here
}
EOD;

        File::ensureDirectoryExists(app_path('Helpers'));
        File::put($path, $stub);

        $this->info('Helper created successfully: ' . $path);
    }
}
