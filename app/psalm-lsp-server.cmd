REM run as Administrator
@echo off

cd /d %~dp0

set DOWNLOADS_DIR=%USERPROFILE%\Downloads
set DOWNLOADS_DIR_LINUX=%DOWNLOADS_DIR:\=/%
set LARAGON_DIR=%DOWNLOADS_DIR%\laragon-php-8.0.0-mariadb-10.11.10-portable-v6.0.0

set PATH=^
%LARAGON_DIR%\bin\php\php-8.0.0-Win32-vs16-x64;

@REM .\vendor\bin\psalm.bat --init &&^
php --version &&^
start "" /b .\vendor\bin\psalm-language-server.bat

cd /d %~dp0

pause