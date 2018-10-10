# GENIE Application
## Deploy

#### Required to deploy:

| Software  | Download link                 |
|----------:|------------------------------:|
| Docker    | https://www.docker.com/       |
| Node      | https://nodejs.org/en/        |
| Git       | https://git-scm.com/downloads |

#### Execute this to deploy the project:
```bash
# Basic setup
./sh/generage-env.sh
./sh/init-nginx-conf.sh

# Build frontend app
cd src/frontend/
npm start

# Build and run docker containers
docker-compose build
docker-compose up -d
```