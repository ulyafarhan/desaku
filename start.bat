@echo off
title SIG-Udeung - Starting...
cd /d "%~dp0"
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dpn0.ps1"
pause
