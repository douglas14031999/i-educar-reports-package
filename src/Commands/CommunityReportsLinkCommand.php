<?php

namespace iEducar\Community\Reports\Commands;

use Illuminate\Console\Command;

class CommunityReportsLinkCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'community:reports:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a symbol link to reports package';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $source = realpath(__DIR__ . '/../../ieducar') ?: (__DIR__ . '/../../ieducar');
        $target = base_path('ieducar/modules/Reports');

        if (!is_dir(dirname($target))) {
            mkdir(dirname($target), 0755, true);
        }

        if (is_link($target) || file_exists($target)) {
            if (PHP_OS_FAMILY === 'Windows' && is_dir($target) && !is_link($target)) {
                @rmdir($target);
            } else {
                @unlink($target);
            }
        }

        if (PHP_OS_FAMILY === 'Windows') {
            exec(sprintf('mklink /J "%s" "%s"', str_replace('/', '\\', $target), str_replace('/', '\\', $source)), $output, $returnVar);
            if ($returnVar !== 0) {
                @symlink($source, $target);
            }
        } else {
            symlink($source, $target);
        }

        $this->info("Symbol link created in: {$target}");
    }
}
