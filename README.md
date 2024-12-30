---
Author: Quadri Abolarinwa
---

# How set up the application
---
## **Requirements**
1. Docker (version 20.x or later)
2. Docker Compose (version 1.29.x or later)
3. Basic knowledge of Docker and containerized environments
---


This project contains two applications that are fully Dockerized and can be run using `docker-compose`. Follow the instructions below to set up and run the project in your local environment.


## Clone the Repository
To clone the repository, run the following command:

```
git clone https://github.com/Abdqadr1/innoscripta.git
cd innoscripta
```


# Build and Start the Containers
To build and start all services, run:
```
docker-compose up --build
```

This command will:

1. Build the Docker images for both applications.
2. Start the containers in the specified network.

# Access the Applications
Once the containers are running, access the applications using the following URLs:
```
Laravel App: http://localhost:8000
React App: http://localhost:5173
```

# Laravel Scheduler
The Laravel application includes a scheduler service configured as:

Service Name: scheduler
Schedule Command: Runs every minute in the container:
```
* * * * * php /app/backend/artisan schedule:run >> /dev/null 2>&1
```
This is defined in the docker-compose.yml file.

**Note:** All the environment files and variables have already been included in the repository

Thank you.