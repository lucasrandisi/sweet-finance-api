## Install [Docker](https://docs.docker.com/get-docker/) and [Docker Compose](https://docs.docker.com/compose/install/)

1. `sudo apt-get update`
2. `sudo apt-get install \
    apt-transport-https \
    ca-certificates \
    curl \
    gnupg \
    lsb-release`
3. `curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg`
4. `echo \
  "deb [arch=amd64 signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu \
  $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null`
5. `sudo apt-get update`
6. `sudo apt-get install docker-ce docker-ce-cli containerd.io`
7. `sudo groupadd docker`
8. `sudo usermod -aG docker usuario_pc`
9. `newgrp docker`

1. `sudo curl -L "https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose`
2. `sudo chmod +x /usr/local/bin/docker-compose`

## Install [Composer](https://getcomposer.org/)

1. `sudo apt install php-cli`
2. `php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"`
3. `php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"`
4. `php composer-setup.php`
5. `php -r "unlink('composer-setup.php');"`
6. `sudo mv composer.phar /usr/local/bin/composer`
7. `composer`

## Set up Composer in the repository of the project

1. Copy .env.example and rename to .env
2. `composer install --ignore-platform-reqs`
3. Create an alias for sail: in home directory create a document '.bash_aliases' with alias `sail='vendor/bin/sail'`
4. Back to repository: `sail up -d`
5. `sail artisan migrate:install`
6. `sail artisan migrate`
7. `sail artisan db:seed --class=AlphaVantageKeysSeeder`
8. `sail artisan key:generate`

In case of problem with root password for mysql:
1. `docker-compose down -v`
2. `docker-compose up -d`

## Install DBeaver from Ubuntu Software and set up with

Server Host: localhost
Port: 33061
Username: sail
Password: password

In case of 'public key retrieval' error, log in into mysql docker container and try again with DBeaver:
1. `docker exec -it sweet-finance-api_mysql_1 bash -c "mysql -u sail -p"`

In case of others errors try to debug docker by:
1. `docker ps`
2. `docker inspect id_container`


