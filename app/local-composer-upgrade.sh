export DOWNLOADS_DIR="$USERPROFILE/Downloads"
export LARAGON_DIR="$DOWNLOADS_DIR/laragon-php-8.4.0-mariadb-10.11.10-portable-v6.0.0"

export PATH="/mingw64/bin:/usr/local/bin:/usr/bin:/bin"
export PATH="$PATH:$LARAGON_DIR/bin/php/php-8.4.0-Win32-vs17-x64:$LARAGON_DIR/bin/composer:$DOWNLOADS_DIR/PortableGit/bin"

# php -r "ini_set('memory_limit', '2G');echo ini_get('memory_limit').PHP_EOL;"
# composer config -g repo.packagist composer https://packagist.phpcomposer.com
# $LARAGON_DIR/bin/composer/composer.phar --no-dev install

php composer-setup.php --install-dir=./bin
composer install -vvv
composer upgrade -vvv
composer update -vvv


# @REM %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" init -vvv &&^
# @REM %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" config -g repo.packagist composer https://packagist.phpcomposer.com &&^
# @REM %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" require symfony/expression-language -vvv
# @REM cd app &&^
# @REM composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
# %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" init -vvv
# %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" install -vvv
# %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" upgrade -vvv
# %LARAGON_DIR%\bin\php\php-8.4.0-Win32-vs17-x64\php.exe "%LARAGON_DIR%\bin\composer\composer.phar" update -vvv

# @REM composer config -g repo.packagist composer https://packagist.phpcomposer.com
# @REM composer install -vvv 

# cd /d %~dp0 &&^
# pause
