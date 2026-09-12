@echo off
echo =======================================================
echo    ThinkPHP8 + Hyperf + React18 AI-Native Setup
echo =======================================================

echo [1/4] Copying .env files...
if not exist "tp8-admin\.env" (
    copy "tp8-admin\.env.example" "tp8-admin\.env"
    echo   Created tp8-admin\.env
)

if not exist "hyperf-service\.env" (
    copy "hyperf-service\.env.example" "hyperf-service\.env"
    echo   Created hyperf-service\.env
)

echo [2/4] Installing ThinkPHP 8 Composer Dependencies...
cd tp8-admin
where composer >nul 2>nul
if %errorlevel% equ 0 (
    call composer install
) else (
    echo   Composer not found, skipping.
)
cd ..

echo [3/4] Installing Hyperf Composer Dependencies...
cd hyperf-service
where composer >nul 2>nul
if %errorlevel% equ 0 (
    call composer install
) else (
    echo   Composer not found, skipping.
)
cd ..

echo [4/4] Installing React Web Dependencies...
cd react-web
where npm >nul 2>nul
if %errorlevel% equ 0 (
    call npm install
) else (
    echo   npm not found, skipping.
)
cd ..

echo =======================================================
echo    Initialization Finished! Ready for AI & Dev!
echo =======================================================
pause
