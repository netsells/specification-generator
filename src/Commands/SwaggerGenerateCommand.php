<?php

namespace Juddling\Parserator\Commands;

use Illuminate\Console\Command;
use Juddling\Parserator\Generators\MigrationGenerator;
use Juddling\Parserator\Parsers\SwaggerParser;

class SwaggerGenerateCommand extends Command
{
    protected $signature = 'swagger:generate {file}';
    protected $description = 'Generates migrations from a swagger specification file';
    private $parser;

    public function handle()
    {
        $this->parser = new SwaggerParser($this->argument('file'));
        $models = $this->parser->migrationModels();

        $this->writeMigrations($models);
    }

    /**
     * @param $models
     */
    private function writeMigrations($models): void
    {
        foreach ($models as $model) {
            $generator = new MigrationGenerator($model);
            $migration = $generator->generate();
            $path = base_path('database/migrations/' . $generator->fileName());

            if (file_put_contents($path, $migration)) {
                $this->info("Migration created: " . $generator->fileName());
            }
        }
    }
}
