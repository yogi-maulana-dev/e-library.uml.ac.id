param(
    [string] $ProjectPath = (Resolve-Path "$PSScriptRoot\..").Path,
    [string] $WebUser = "Users"
)

$criticalFiles = @(
    "routes\web.php",
    "routes\auth.php",
    "routes\console.php",
    "bootstrap\app.php",
    "app\Http\Controllers\AdminCrudController.php",
    "app\Http\Middleware\SecurityHeaders.php",
    "app\Http\Middleware\VerifyFileIntegrity.php",
    "config\security.php",
    "config\permission.php"
)

Push-Location $ProjectPath

php artisan security:integrity generate
php artisan security:integrity check
php artisan route:cache
php artisan config:cache
php artisan view:cache

foreach ($file in $criticalFiles) {
    $path = Join-Path $ProjectPath $file

    if (Test-Path $path) {
        attrib +R $path
        icacls $path /inheritance:r | Out-Null
        icacls $path /grant:r "$env:USERNAME`:R" | Out-Null
        icacls $path /grant:r "$WebUser`:R" | Out-Null
    }
}

Write-Host "Windows hardening complete. Critical files are read-only and integrity manifest is active."
Write-Host "To deploy authorized changes, remove read-only attributes, patch files, then rerun this script."

Pop-Location
