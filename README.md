# Evilginx LAB on Docker
## build & run the container
	
	sudo docker-compose up --build -d --force-recreate --remove-orphans

## remove the containers

	sudo docker stop $(docker ps -q) && sudo docker image prune -a
	
## access the container

	sudo docker exec -it <container name> /bin/bash

## check mounted volumes

	docker inspect my-apache-container | grep Mounts -A 10
	

## see logs

	sudo docker logs <container name>

