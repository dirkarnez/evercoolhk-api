REM run as Administrator
@echo off

cd /d %~dp0

set DOWNLOADS_DIR=%USERPROFILE%\Downloads
set DOWNLOADS_DIR_LINUX=%DOWNLOADS_DIR:\=/%
set LARAGON_DIR=%DOWNLOADS_DIR%\laragon-php-8.4.0-mariadb-10.11.10-portable-v6.0.0
set COMPOSER_DIR=%LARAGON_DIR%\bin\composer

set PATH=^
%LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64;^
%COMPOSER_DIR%;^
%DOWNLOADS_DIR%\PortableGit\bin;


%LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
%LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
%LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe composer-setup.php --install-dir=%COMPOSER_DIR%
%LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe -r "unlink('composer-setup.php');"

@REM %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" init -vvv &&^
@REM %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" config -g repo.packagist composer https://packagist.phpcomposer.com &&^
@REM %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" require symfony/expression-language -vvv
@REM cd app &&^
@REM composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/



@REM %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe -r "echo ini_get('memory_limit').PHP_EOL;"
@REM %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" init -vvv
%LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" install -vvv
%LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" upgrade -vvv
%LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" update -vvv

@REM composer config -g repo.packagist composer https://packagist.phpcomposer.com
@REM composer install -vvv 

cd /d %~dp0 &&^
pause
