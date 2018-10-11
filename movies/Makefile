default:
	echo "There is nothing to do."

install:
	apt install docker-compose php7.2-cli php7.2-xml php7.2-mbstring php7.2-sqlite3 composer

start:
	docker-compose up -d
	
stop:
	docker-compose down

test:
	./vendor/bin/phpunit --bootstrap vendor/autoload.php tests