@echo off
powershell docker compose --env-file=.env.joby up -d --build


