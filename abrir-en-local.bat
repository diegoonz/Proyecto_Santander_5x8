@echo off
rem Abre la web en este PC para probarla. Hace falta XAMPP (PHP 8).
rem Para apagarla, cierra esta ventana.
cd /d "%~dp0web"
start "" http://localhost:8080
C:\xampp\php\php.exe -S localhost:8080
