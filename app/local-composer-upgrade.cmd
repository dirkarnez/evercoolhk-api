REM run as Administrator
@echo off

cd /d %~dp0

set DOWNLOADS_DIR=%USERPROFILE%\Downloads
set DOWNLOADS_DIR_LINUX=%DOWNLOADS_DIR:\=/%
set LARAGON_DIR=%DOWNLOADS_DIR%\laragon-php-8.4.0-mariadb-10.11.10-portable-v6.0.0

set PATH=^
%LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64;^
%LARAGON_DIR%\bin\composer;^
%DOWNLOADS_DIR%\PortableGit\bin;


@REM %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" init -vvv &&^
@REM %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" config -g repo.packagist composer https://packagist.phpcomposer.com &&^
@REM %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" require symfony/expression-language -vvv
@REM cd app &&^
@REM composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/



@REM %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe -r "echo ini_get('memory_limit').PHP_EOL;"
@REM %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" init -vvv
@REM %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" install -vvv
@REM %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" upgrade -vvv
@REM %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" update -vvv

@REM composer config -g repo.packagist composer https://packagist.phpcomposer.com
@REM composer install -vvv 

cd /d %~dp0 &&^
pause
