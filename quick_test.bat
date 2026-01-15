@echo off
echo Starting Laravel server...
start /B php artisan serve --host=127.0.0.1 --port=8000

echo Waiting for server to start...
timeout /t 5 /nobreak > nul

echo.
echo Running production tests...
php production_test.php

echo.
echo Press any key to stop server...
pause > nul

taskkill /F /IM php.exe > nul 2>&1
