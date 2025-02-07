@echo off
rem Ruta al ejecutable de PHP
set PHP_EXECUTABLE=C:\xampp\php\php.exe

rem Ruta al archivo PHP que quieres ejecutar
set PHP_SCRIPT=C:\xampp\htdocs\rentdesk\cron\acciones\varios_acreedores\insert.php

set LOG_FILE=C:\xampp\htdocs\rentdesk\cron\acciones\varios_acreedores\log.txt

rem Comando para ejecutar PHP
"%PHP_EXECUTABLE%" "%PHP_SCRIPT%" > "%LOG_FILE%" 2>&1