PROJECT_NAME=laravel-calculator

docker-up: 
	# @cp -n .env .env.local
	docker-compose -p $(PROJECT_NAME) up --build 
docker-upd:
	docker-compose -p $(PROJECT_NAME) up -d
docker-app:
	docker-compose -p $(PROJECT_NAME) exec app bash
docker-down:
	docker-compose -p $(PROJECT_NAME) down
docker-killall:
	docker kill $(docker ps -q)
