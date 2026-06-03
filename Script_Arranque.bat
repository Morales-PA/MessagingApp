@echo off

symfony server:start

start /min php bin\console app:start-websocket
