evercoolhk-api
==============
### Notes
- php version: `8.4`, `FPM with OPcache`
- 功能段数据
- 机组功能段
- 风机电机定价

### Backing Up
- ```cmd
  @REM https://cdn.mysql.com/Downloads/MySQL-Shell/mysql-shell-8.0.32-windows-x86-64bit.zip
  .\mysqlsh.exe "mysql://mysql2020:xxxx@mysql.hostcompany100.com:3306" -- util dumpSchemas evercoolhk_2020 --output-url=file:///%USERPROFILE%/Downloads/exports
  ```

### Expression
- https://symfony.com/doc/current/components/expression_language.html

### How to start
- Just run `run.cmd`
- for composer, go to Docker GUI terminal inside: `composer install` / `composer update`
 
### ORM
- [dirkarnez/eloquent-wrapper](https://github.com/dirkarnez/eloquent-wrapper)
  
### Testing out
- Largon / Largon: http://localhost:8000/names/hello / https://localhost/names/hello
- https://evercoolhk.com/api/names/hello
 

### Localhost for [laragon-portable](https://github.com/dirkarnez/laragon-portable)
```nginx
server {
    listen 8000 default_server;
    server_name localhost ;
    root "C:/Users/Administrator/Downloads/evercoolhk-api/app/";
    
    index index.html index.htm index.php;
 
    # Access Restrictions
    allow       127.0.0.1;
    deny        all;
 
    include "C:/Users/Administrator/Downloads/laragon-php-8.0.0-mariadb-10.11.10-portable-v6.0.0/etc/nginx/alias/*.conf";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
		autoindex on;
    }
    
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass php_upstream;		
        #fastcgi_pass unix:/run/php/php7.0-fpm.sock;
    }

	
    charset utf-8;
	
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    location ~ /\.ht {
        deny all;
    }
	
}
```

### CMS
- [Winter CMS](https://github.com/wintercms)

### Tricks
- Use matlab for interoplation

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
