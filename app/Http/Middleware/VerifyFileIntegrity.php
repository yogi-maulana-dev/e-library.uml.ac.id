<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyFileIntegrity
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('security.integrity.enabled')) {
            return $next($request);
        }

        $manifestPath = config('security.integrity.manifest');

        if (! is_file($manifestPath)) {
            Log::critical('Security integrity manifest is missing.', ['path' => $manifestPath]);

            return $this->failOrContinue($next, $request, 'Security manifest missing.');
        }

        $manifest = json_decode((string) file_get_contents($manifestPath), true);

        if (! is_array($manifest)) {
            Log::critical('Security integrity manifest is invalid.', ['path' => $manifestPath]);

            return $this->failOrContinue($next, $request, 'Security manifest invalid.');
        }

        foreach (config('security.integrity.critical_files', []) as $relativePath) {
            $absolutePath = base_path($relativePath);

            if (! is_file($absolutePath)) {
                Log::critical('Critical application file is missing.', ['file' => $relativePath]);

                return $this->failOrContinue($next, $request, 'Critical file missing.');
            }

            $currentHash = hash_file('sha256', $absolutePath);
            $knownHash = $manifest[$relativePath]['sha256'] ?? null;

            if (! hash_equals((string) $knownHash, (string) $currentHash)) {
                Log::critical('Critical application file was modified.', [
                    'file' => $relativePath,
                    'expected' => $knownHash,
                    'actual' => $currentHash,
                    'ip' => $request->ip(),
                ]);

                return $this->failOrContinue($next, $request, 'Application integrity check failed.');
            }
        }

        return $next($request);
    }

    private function failOrContinue(Closure $next, Request $request, string $message): Response
    {
        if (config('security.integrity.fail_closed')) {
            abort(503, $message);
        }

        return $next($request);
    }
}
