<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateIdeHelper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ide-helper:generate-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all IDE helper files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating IDE helper files...');

        // Generate basic helper
        $this->call('ide-helper:generate');

        // Generate model helpers
        $this->call('ide-helper:models', ['--write' => true]);

        // Generate meta file
        $this->call('ide-helper:meta');

        $this->info('IDE helper files generated successfully!');
    }
}
