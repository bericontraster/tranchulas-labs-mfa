# Evilginx LAB on Docker
## build & run the container
	
	sudo docker-compose up --build -d --force-recreate --remove-orphans

## remove the containers

	sudo docker-compose down && sudo docker image prune -a
	
## access the container

	docker exec -it my-apache-container /bin/bash

## check mounted volumes

	docker inspect my-apache-container | grep Mounts -A 10
	
## modify your host file

	docker_container_IP www.testinc.local


## see logs

	sudo docker logs <container name>

