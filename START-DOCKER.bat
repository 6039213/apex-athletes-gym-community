@echo off
echo ========================================
echo Gym Community WordPress - Docker Setup
echo ========================================
echo.

echo Stap 1: Controleren of Docker Desktop draait...
docker info >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker Desktop is niet actief of niet bereikbaar.
    echo.
    echo Volg deze stappen:
    echo 1. Start Docker Desktop
    echo 2. Wacht tot Docker volledig is opgestart
    echo 3. Voer dit script opnieuw uit
    echo.
    pause
    exit /b 1
)

echo [OK] Docker is actief
echo.

echo Stap 2: Docker configuratie valideren...
docker compose config >nul
if errorlevel 1 (
    echo [ERROR] docker compose config faalde.
    echo Controleer docker-compose.yml en probeer opnieuw.
    echo.
    pause
    exit /b 1
)

echo [OK] Configuratie is geldig
echo.

echo Stap 3: Oude containers stoppen...
docker compose down

echo.
echo Stap 4: Containers bouwen en starten...
docker compose up -d --build

echo.
echo Stap 5: Wachten tot de database klaar is...
timeout /t 20 /nobreak >nul

echo.
echo Stap 6: Status controleren...
docker compose ps

echo.
echo ========================================
echo Setup Compleet!
echo ========================================
echo.
echo WordPress:   http://localhost:8080
echo Admin:       http://localhost:8080/wp-admin
echo phpMyAdmin:  http://localhost:8081
echo MySQL:       localhost:3307
echo.
echo Eerste start:
echo - importeert automatisch docker\mysql-init\gym_community_backup.sql
echo - zet de oude Laragon URL om naar http://localhost:8080
echo.
echo Handige commands:
echo   docker compose logs -f
echo   docker compose down
echo   docker compose down -v   ^(database reset + nieuwe import^)
echo.
pause
