$dockerProcs = Get-Process *docker* -ErrorAction SilentlyContinue

if ($dockerProcs) {
    $dockerProcs | Stop-Process -Force
    Write-Host "[DOCKER]" -ForegroundColor Red "Docker processes stopped"
} else {
    Write-Host "[DOCKER]" -ForegroundColor Green "Docker processes already stopped"
}
