@echo off

php ..\vendor\doctrine\orm\bin\doctrine %*


REM doctrine orm:schema-tool:create
REM orm:schema-tool:drop --force
REM orm:schema-tool:create
REM orm:schema-tool:update --force