@echo off
REM Setup script for Windows to make query-analyze available globally
REM Run this after composer require professionalchacha/php-query-optimizer

echo Setting up global query-analyze command...

REM Find the vendor bin directory
for /f "tokens=*" %%i in ('composer config bin-dir --absolute 2^>nul') do set VENDOR_BIN=%%i
if "%VENDOR_BIN%"=="" set VENDOR_BIN=vendor\bin

REM Check if query-analyze exists
if not exist "%VENDOR_BIN%\query-analyze" (
    echo Error: query-analyze not found in %VENDOR_BIN%
    echo Make sure you have installed: composer require professionalchacha/php-query-optimizer
    exit /b 1
)

REM Create global symlink (requires admin)
echo Creating global symlink...
mklink "C:\Windows\System32\query-analyze.bat" "%cd%\%VENDOR_BIN%\query-analyze" >nul 2>&1

if %errorlevel% equ 0 (
    echo ✅ Global command 'query-analyze' is now available!
    echo Usage: query-analyze path\to\file.php
    echo        query-analyze "SELECT * FROM users"
) else (
    echo ❌ Failed to create global symlink (run as Administrator)
    echo You can still use: %VENDOR_BIN%\query-analyze
)

REM Create a batch file wrapper as alternative
echo @echo off > query-analyze.bat
echo php "%cd%\%VENDOR_BIN%\query-analyze" %%* >> query-analyze.bat
echo.
echo ✅ Created query-analyze.bat in current directory
echo You can move this to a directory in your PATH
