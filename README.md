# Evilginx LAB on Docker
![back](evil-dock.jpg)
## build & run the containers
	
	sudo docker-compose up --build -d --force-recreate --remove-orphans
	
Verify if they run:

	docker ps -a
	CONTAINER ID   IMAGE                     COMMAND                  CREATED         STATUS         PORTS                                                                              NAMES
	d7bc39548885   evilginx_lab_evilginx2    "/bin/bash -l"           8 seconds ago   Up 6 seconds   0.0.0.0:443->443/tcp, :::443->443/tcp, 0.0.0.0:8080->8080/tcp, :::8080->8080/tcp   evilginx3.local
	6d5eac2940a7   evilginx_lab_apache-php   "apachectl -D FOREGR…"   8 seconds ago   Up 6 seconds   80/tcp, 443/tcp                                                                    targetsite.local



## stop and remove all the containers

	sudo docker stop $(docker ps -q) && sudo docker rm $(docker ps -a -q) && sudo docker image prune -a
	
## access a container

	sudo docker exec -it <container name> /bin/bash

## check mounted volumes

	sudo docker inspect <container name> | grep Mounts -A 10
	

## see logs

	sudo docker logs <container name>
	
## show containers IP

	sudo docker ps -q | xargs -n 1 docker inspect -f '{{.Name}} - {{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}'
	
	/targetsite.local - 172.18.0.20
	/evilginx3.local - 172.18.0.10






## Start evilnginx
	
	sudo docker exec -it evilginx3.local /bin/bash 
	root@d82bae9ce059:/evilginx2# ./evilginx2 

                                         
                                             ___________      __ __           __               
                                             \_   _____/__  _|__|  |    ____ |__| ____ ___  ___
                                              |    __)_\  \/ /  |  |   / __ \|  |/    \\  \/  /
                                              |        \\   /|  |  |__/ /_/  >  |   |  \>    < 
                                             /_______  / \_/ |__|____/\___  /|__|___|  /__/\_ \
                                                     \/              /_____/         \/      \/
                                         
                                                        - --  Community Edition  -- -
                                         
                                               by Kuba Gretzky (@mrgretzky)     version 3.3.0
                                         

	[11:20:42] [inf] Evilginx Mastery Course: https://academy.breakdev.org/evilginx-mastery (learn how to create phishlets)
	[11:20:42] [inf] loading phishlets from: /evilginx2/phishlets
	[11:20:42] [inf] loading configuration from: /root/.evilginx
	[11:20:42] [inf] blacklist mode set to: unauth
	[11:20:42] [inf] unauthorized request redirection URL set to: https://www.youtube.com/watch?v=dQw4w9WgXcQ
	[11:20:42] [inf] https port set to: 443
	[11:20:42] [inf] dns port set to: 53
	[11:20:42] [inf] autocert is now enabled
	[11:20:42] [inf] blacklist: loaded 0 ip addresses and 0 ip masks
	[11:20:42] [war] server domain not set! type: config domain <domain>
	[11:20:42] [war] server external ip not set! type: config ipv4 external <external_ipv4_address>
	[11:20:42] [inf] obtaining and setting up 0 TLS certificates - please wait up to 60 seconds...
	[11:20:42] [inf] successfully set up all TLS certificates

	+-----------+-----------+-------------+-----------+-------------+
	| phishlet  |  status   | visibility  | hostname  | unauth_url  |
	+-----------+-----------+-------------+-----------+-------------+
	| example   | disabled  | visible     |           |             |
	+-----------+-----------+-------------+-----------+-------------+

	:  

## Config Evilngnix container

### Add a subdomain alias

sudo docker exec -it evilginx3.local /bin/bash                                                     
root@f5c76fc94de6:/evilginx2# echo "127.0.0.1    example.local" >> /etc/hosts
	
