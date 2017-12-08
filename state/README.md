Admin Directory -   Samaj/upload/admin

API Directory -   Samaj/upload/api/v1/


configure docker compose file

To start work in local system in PHP add
```
        volumes:
            - ./samaj/upload/api:/var/www/html/api
            - ./samaj/upload/admin:/var/www/html/admin
```

Add in composer
```
        volumes:
            - ./samaj/upload/api/v1:/var/www/html/api/v1
```            



To start project in docker

Step 1

`docker-compose build`


Step 2

`docker-compose up`


