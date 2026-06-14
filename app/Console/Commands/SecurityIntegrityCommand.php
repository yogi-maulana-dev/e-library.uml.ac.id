<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SecurityIntegrityCommand extends Command
{
    protected $signature = 'security:integrity {action=check : check or generate}';

    protected $description = 'Generate or verify hashes for critical application files.';

    public function handle(): int
    {
        $action = $this->argument('action');

        if (! in_array($action, ['check', 'generate'], true)) {
            $this->error('Invalid action. Use "check" or "generate".');

            return self::FAILURE;
        }

        return $action === 'generate'
            ? $this->generate()
            : $this->check();
    }

    private function generate(): int
    {
        $manifest = [];

        foreach ($this->criticalFiles() as $relativePath) {
            $absolutePath = base_path($relativePath);

            if (! is_file($absolutePath)) {
                $this->error("Missing critical file: {$relativePath}");

                return self::FAILURE;
            }

            $manifest[$relativePath] = [
                'sha256' => hash_file('sha256', $absolutePath),
                'generated_at' => now()->toIso8601String(),
            ];
        }

        File::ensureDirectoryExists(dirname($this->manifestPath()));
        File::put($this->manifestPath(), json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL);

        $this->info('Security integrity manifest generated.');
        $this->line($this->manifestPath());

        return self::SUCCESS;
    }

    private function check(): int
    {
        if (! is_file($this->manifestPath())) {
            $this->error('Security integrity manifest is missing. Run: php artisan security:integrity generate');

            return self::FAILURE;
        }

        $manifest = json_decode((string) file_get_contents($this->manifestPath()), true);
        $hasFailure = false;

        foreach ($this->criticalFiles() as $relativePath) {
            $absolutePath = base_path($relativePath);
            $currentHash = is_file($absolutePath) ? hash_file('sha256', $absolutePath) : null;
            $knownHash = $manifest[$relativePath]['sha256'] ?? null;

            if (! $currentHash || ! hash_equals((string) $knownHash, (string) $currentHash)) {
                $this->error("FAILED: {$relativePath}");
                $hasFailure = true;
            } else {
                $this->line("OK: {$relativePath}");
            }
        }

        return $hasFailure ? self::FAILURE : self::SUCCESS;
    }

    private function criticalFiles(): array
    {
        return config('security.integrity.critical_files', []);
    }

    private function manifestPath(): string
    {
        return config('security.integrity.manifest');
    }
}
