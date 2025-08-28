$envFile = Join-Path $PSScriptRoot "../../.env.docker"
Write-Host "`n[ENVIRONMENT]" -ForegroundColor Yellow " Loading .env.docker..."
Get-Content $envFile | ForEach-Object {
    if ($_ -match "^\s*([^=]+)=(.+)$") {
        $name = $matches[1]
        $value = $matches[2].Trim('"')
        Set-Variable -Name $name -Value $value
    }
}

$dockerProcs = Get-Process *docker* -ErrorAction SilentlyContinue
if ($dockerProcs) {
    $dockerProcs | Stop-Process -Force
    Write-Host "`n[DOCKER]" -ForegroundColor Blue " Docker processes stopped"
} else {
    Write-Host "`n[DOCKER]" -ForegroundColor Blue " Docker processes already stopped"
}

Write-Host "[DOCKER]" -ForegroundColor Blue " Starting WSL and Docker Desktop..."
Start-Process "wsl.exe" -ArgumentList "-d $WSL_DISTRO", "tail -f /dev/null" -WindowStyle Hidden
Start-Process $DOCKER_DESKTOP -WindowStyle Hidden

do {
    Start-Sleep -Seconds 2
    $dockerVersion = docker version --format '{{.Server.Version}}' 2>$null
} while (-not $dockerVersion)
Write-Host "[DOCKER]" -ForegroundColor Blue " Docker is ready (version $dockerVersion)"

Write-Host "[DOCKER]" -ForegroundColor Blue " Starting services with Docker Compose..."
Start-Process -FilePath "docker" -ArgumentList "compose up -d" -NoNewWindow -Wait
$timeout = 30
$interval = 1
$remaining = $timeout

do {
    $up = Test-NetConnection -ComputerName "localhost" -Port 8080
    if ($up.TcpTestSucceeded) { break }
    Write-Host "`r[SERVICE]" -ForegroundColor Yellow " Waiting $remaining s..." -NoNewline
    Start-Sleep -Seconds $interval
    $remaining -= $interval
} while ($remaining -gt 0)

if ($up.TcpTestSucceeded) {
    Write-Host "`n[SERVICE]" -ForegroundColor Yellow " localhost:8080 is ready"
} else {
    Write-Host "`n[SERVICE]" -ForegroundColor Red " localhost:8080 did not respond after $timeout s"
}

$chromeProcs = Get-Process "chrome" -ErrorAction SilentlyContinue
$chromePath = "C:\Program Files\Google\Chrome\Application\chrome.exe"
Write-Host "`n[BROWSER]" -ForegroundColor Yellow " Opening Chrome..."
if ($chromeProcs) {
    & $chromePath "--new-tab http://localhost:8080"
} else {
    & $chromePath "http://localhost:8080"
}

Write-Host "`n[IDE]" -ForegroundColor Yellow " Opening VS Code..."
code .

Write-Host "`n[FINISHED]" -ForegroundColor Green " All tasks completed"
