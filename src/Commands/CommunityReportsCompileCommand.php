<?php

namespace iEducar\Community\Reports\Commands;

use Illuminate\Console\Command;

class CommunityReportsCompileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'community:reports:compile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile reports files';

    protected function getJasperFiles(): ?string
    {
        $reportDefaultPath = 'ieducar/modules/Reports/ReportSources';
        if (false === is_dir(base_path($reportDefaultPath))) {
            return null;
        }

        return base_path($reportDefaultPath);
    }

    /**
     * Return the JasperStarter binary file.
     *
     * @return string
     */
    protected function getJasperStarter()
    {
        return base_path('vendor/cossou/jasperphp/src/JasperStarter/bin/jasperstarter');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Compiling reports files..');

        $jasperFiles = $this->getJasperFiles();

        if ($jasperFiles === null) {
            $this->error('Report package not installed or linked. Run php artisan community:reports:link first.');

            return 1;
        }

        $jasperStarter = $this->getJasperStarter();
        $jrxmlFiles = glob($jasperFiles . '/*.jrxml');

        if (empty($jrxmlFiles)) {
            $this->warn('No .jrxml files found to compile in: ' . $jasperFiles);

            return 0;
        }

        sort($jrxmlFiles);
        $total = count($jrxmlFiles);
        $this->info("Found {$total} report templates to compile...");

        $successCount = 0;
        $errorCount = 0;

        foreach ($jrxmlFiles as $file) {
            $baseName = basename($file, '.jrxml');
            $outputDestination = $jasperFiles . DIRECTORY_SEPARATOR . $baseName;

            $cmd = sprintf(
                '%s cp %s -o %s',
                escapeshellarg($jasperStarter),
                escapeshellarg($file),
                escapeshellarg($outputDestination)
            );

            passthru($cmd, $exitCode);

            if ($exitCode === 0) {
                $this->line("  ✓ {$baseName}");
                $successCount++;
            } else {
                $this->error("  ✗ Error compiling: {$baseName}");
                $errorCount++;
            }
        }

        $this->info("Compilation finished: {$successCount} compiled successfully, {$errorCount} errors.");

        return $errorCount === 0 ? 0 : 1;
    }
}
