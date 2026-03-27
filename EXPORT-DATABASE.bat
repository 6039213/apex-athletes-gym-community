@echo off
echo ========================================
echo Database Export - Gym Community
echo ========================================
echo.

set MYSQL_PATH=C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin
set DB_NAME=Apex_Athletes
set OUTPUT_FILE=Apex_Athletes_export_%date:~-4,4%%date:~-7,2%%date:~-10,2%.sql

echo Exporteren van database: %DB_NAME%
echo Output bestand: %OUTPUT_FILE%
echo.

"%MYSQL_PATH%\mysqldump.exe" -u root %DB_NAME% > %OUTPUT_FILE%

if errorlevel 1 (
    echo [ERROR] Database export mislukt!
    echo.
    echo Controleer of:
    echo 1. Laragon draait
    echo 2. MySQL actief is
    echo 3. Database naam correct is
    echo.
    pause
    exit /b 1
)

echo.
echo [SUCCESS] Database geexporteerd naar: %OUTPUT_FILE%
echo.
echo Bestandsgrootte:
dir %OUTPUT_FILE% | find "%OUTPUT_FILE%"
echo.
pause
