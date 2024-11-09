evercoolhk-api
==============
### Notes
- php version: `7.4`, `FPM with OPcache`

### Backing Up
- ```cmd
  @REM https://cdn.mysql.com/Downloads/MySQL-Shell/mysql-shell-8.0.32-windows-x86-64bit.zip
  .\mysqlsh.exe "mysql://mysql2020:xxxx@mysql.hostcompany100.com:3306" -- util dumpSchemas evercoolhk_2020 --output-url=file:///%USERPROFILE%/Downloads/exports
  ```

### How to start
- Just run `run.cmd`
- for composer, go to Docker GUI terminal inside: `composer install` / `composer update`
 
### ORM
- [dirkarnez/eloquent-wrapper](https://github.com/dirkarnez/eloquent-wrapper)
  
### Testing out
- http://localhost:8000/names/hello

### CMS
- [Winter CMS](https://github.com/wintercms)

### Database
- Server: Localhost via UNIX socket
- Server type: MySQL
- Server connection: SSL is not being used Documentation
- Server version: 5.7.31-percona-sure1-log - MySQL Community Server (GPL)
- Protocol version: 10
- Server charset: cp1252 West European (latin1)

- Apache
- Database client version: libmysql - mysqlnd 5.0.12-dev - 20150407 - $Id: 3591daad22de08524295e1bd073aceeff11e6579 $
- PHP extension: mysqliDocumentation curlDocumentation mbstringDocumentation
- PHP version: 7.2.34

- https://dev.mysql.com/doc/refman/5.7/en/create-table-foreign-keys.html
