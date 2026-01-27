<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class CreateServiceClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {classname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    protected $file;
    public function __construct(Filesystem $file)
    {
        parent::__construct();
        $this->file = $file;
    }

    public function singleClassName($name)
    {
        // ucwords=>make class name single and first letter is capital
        return ucwords(Pluralizer::singular($name));
    }

    public function stubPath()
    {
        return __DIR__ . "/../../../stubs/servicepattern.stub";
    }

    public function stubVariables()
    {
        return [
            'NAME' => $this->singleClassName($this->argument('classname')),
        ];
    }

    public function stubContent($stubPath, $stubVariables)
    {
        $content = file_get_contents($stubPath);
        foreach ($stubVariables as $search => $name) {
            $contents = str_replace('$' . $search, $name, $content);
        }
        return $contents;
    }

    public function getPath()
    {
        return base_path("App\\Services\\") . $this->singleClassName($this->argument('classname')) . "Service.php";
    }

    public function makeDir($path)
    {
        $this->file->makeDirectory($path, 077, true, true);
        return $path;
    }

    public function handle()
    {
        // dd($this->argument('classname'));
        $path = $this->getPath();
        $this->makeDir(dirname($path));
        if ($this->file->exists($path)) {
            $this->info('this file already Exsits');
        } else {
            $stubPath = $this->stubPath();
            $content = $this->stubContent($stubPath, $this->stubVariables());
            $this->file->put($path, $content);
            $this->info('this file has been created');
        }
    }
}
