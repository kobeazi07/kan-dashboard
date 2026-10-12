
@echo off
cd /d "C:\laragon\www\kan-dashboard-1"

if not exist "storage\logs" mkdir "storage\logs"

:loop
echo [%date% %time%] Memulai sinkronisasi >> "storage\logs\dashboard-cron.log"

"C:\laragon\bin\php\php-8.3.33-nts-Win32-vs16-x64\php.exe" artisan app:check-dashboard-progress >> "storage\logs\dashboard-cron.log" 2>&1

timeout /t 15 /nobreak > nul
goto loop
