@echo off
echo ========================================
echo Gym Community WordPress - Docker Setup
echo ========================================
echo.

echo Stap 1: Controleren of Docker Desktop draait...
docker --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker Desktop is niet actief!
    echo.
    echo Volg deze stappen:
    echo 1. Start Docker Desktop
    echo 2. Wacht tot Docker volledig opgestart is
    echo 3. Voer dit script opnieuw uit
    echo.
    pause
    exit /b 1
)

echo [OK] Docker is actief
echo.

echo Stap 2: Stoppen van eventuele oude containers...
docker-compose down

echo.
echo Stap 3: Starten van containers...
docker-compose up -d

echo.
echo Stap 4: Wachten tot containers klaar zijn (30 seconden)...
timeout /t 30 /nobreak

echo.
echo Stap 5: Status controleren...
docker-compose ps

echo.
echo ========================================
echo Setup Compleet!
echo ========================================
echo.
echo WordPress:   http://localhost:8080
echo Admin:       http://localhost:8080/wp-admin
echo phpMyAdmin:  http://localhost:8081
echo.
echo Database credentials:
echo   User: wordpress
echo   Pass: wordpress
echo   DB:   Apex_Athletes
echo.
echo Bekijk logs met: docker-compose logs -f
echo Stop containers met: docker-compose down
echo.
pause
