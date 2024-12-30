@REM SET DB_DATABASE=app
@REM SET DB_PASSWORD=123456
@REM SET DB_USERNAME=user
@echo off

docker compose up --build && docker compose down
pause