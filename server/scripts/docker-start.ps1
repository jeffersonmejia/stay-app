$startTime = Get-Date
cls

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
    wsl --shutdown
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

$timeout = 5
$interval = 1
$elapsed = 0

Write-Host "[SERVICE]" -ForegroundColor Yellow " Waiting for localhost:8080..."
do {
    $up = Test-NetConnection -ComputerName "localhost" -Port 8080
    $remaining = $timeout - $elapsed
    Write-Host "`r[SERVICE] Time remaining: $remaining s" -NoNewline
    Start-Sleep -Seconds $interval
    $elapsed += $interval
} while (-not $up.TcpTestSucceeded -and $elapsed -le $timeout)

Write-Host ""
if ($up.TcpTestSucceeded) {
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
    Write-Host "[SERVICE]" -ForegroundColor Green " localhost:8080 is ready!"
} else {
    Write-Host "[SERVICE]" -ForegroundColor Red " localhost:8080 did not respond after $timeout s."
}
$totalTime = (Get-Date) - $startTime
if ($totalTime.TotalSeconds -ge 60) {
    $timeOutput = "{0:N2} min" -f ($totalTime.TotalSeconds / 60)
} else {
    $timeOutput = "{0:N2} s" -f $totalTime.TotalSeconds
}

Write-Host "[FINISHED] All tasks completed. RTO: $timeOutput." -ForegroundColor Green