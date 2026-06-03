@echo off
tar --exclude=vendor --exclude=.git --exclude=node_modules --exclude=db_storage --exclude=deploy.bat -czf messageApp.tar.gz .

scp messageApp.tar.gz juan@192.168.0.15:~/

@REM scp messageApp.tar.gz juan@213.37.98.60:~/

ssh juan@192.168.0.15 "mkdir -p ~/messageApp && mv ~/messageApp.tar.gz ~/messageApp/ && cd ~/messageApp && tar -xzf messageApp.tar.gz && rm messageApp.tar.gz && sudo docker compose up -d --build"

@REM ssh juan@213.37.98.60 "mkdir -p ~/messageApp && mv ~/messageApp.tar.gz ~/messageApp/ && cd ~/messageApp && tar -xzf messageApp.tar.gz && rm messageApp.tar.gz && sudo docker compose up -d --build"

del messageApp.tar.gz

echo "done"