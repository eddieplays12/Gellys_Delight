@echo off
setlocal

set "PROJECT_DIR=%~dp0"
set "FRONTEND_DIR=%PROJECT_DIR%frontend"

echo Checking Laravel backend port 8000...
netstat -ano | findstr ":8000 " >nul
if %errorlevel% equ 0 (
    echo Port 8000 is already in use. If Laravel is already running, keep it open.
) else (
    start "Gellys Laravel API" /D "%PROJECT_DIR%" cmd /k php artisan serve --host=127.0.0.1 --port=8000
)

echo Checking Next frontend port 3000...
netstat -ano | findstr ":3000 " >nul
if %errorlevel% equ 0 (
    echo Port 3000 is already in use. Open http://127.0.0.1:3000/products or close the old server first.
) else (
    start "Gellys Frontend" /D "%FRONTEND_DIR%" cmd /k npm.cmd run dev
)

echo.
echo Customer products page:
echo http://127.0.0.1:3000/products
echo.
echo Keep the opened terminal windows running while testing the app.
pause
