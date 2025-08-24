#!/bin/bash

# Function to check if Docker is running as root
check_docker_root() {
    if [[ $(id -u) -eq 0 ]]; then
        echo "Docker is running as root."
        DOCKER_ROOT=true
    else
        echo "Docker is not running as root."
        DOCKER_ROOT=false
    fi
}

# Function to start the application
start_application() {
    if [ "$DOCKER_ROOT" = true ]; then
        sudo ./run.sh "$DB_DATABASE"
    else
        ./run.sh "$DB_DATABASE"
    fi
}


# Prompt user to confirm Docker's root status
read -p "Is Docker running as root? (yes/no): " DOCKER_ROOT_CONFIRMATION

case $DOCKER_ROOT_CONFIRMATION in
    [yY]|[yY][eE][sS])
        DOCKER_ROOT=true
        ;;
    [nN]|[nN][oO])
        DOCKER_ROOT=false
        ;;
    *)
        echo "Invalid input. Assuming Docker is not running as root."
        DOCKER_ROOT=false
        ;;
esac

# Copy .env.example to .env
cp src/.env.example src/.env

# Edit .env file
sed -i -e 's/^DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' src/.env
sed -i -e 's/^# DB_HOST=127\.0\.0\.1/DB_HOST=127.0.0.1/' src/.env
sed -i -e 's/^# DB_PORT=3306/DB_PORT=3306/' src/.env
sed -i -e 's/^# DB_DATABASE=laravel/DB_DATABASE=/' src/.env
sed -i -e 's/^# DB_USERNAME=root/DB_USERNAME=/' src/.env
sed -i -e 's/^# DB_PASSWORD=/DB_PASSWORD=/' src/.env

# Prompt for database details
read -p "Enter the database host (default: 127.0.0.1): " DB_HOST
read -p "Enter the database port (default: 3306): " DB_PORT
read -p "Enter the database name: " DB_DATABASE
read -p "Enter the database username: " DB_USERNAME
read -p "Enter the database password: " DB_PASSWORD

# Update .env file with provided details
sed -i -e "s/^DB_HOST=.*/DB_HOST=$DB_HOST/" src/.env
sed -i -e "s/^DB_PORT=.*/DB_PORT=$DB_PORT/" src/.env
sed -i -e "s/^DB_DATABASE=.*/DB_DATABASE=$DB_DATABASE/" src/.env
sed -i -e "s/^DB_USERNAME=.*/DB_USERNAME=$DB_USERNAME/" src/.env
sed -i -e "s/^DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" src/.env
sed -i -e "s/MYSQL_DATABASE:.*/MYSQL_DATABASE: $DB_DATABASE/" docker-compose.yml
echo "Database details updated successfully."

# Run 'composer install' inside src/
echo "Running 'composer install'..."
cd src/ || exit
composer install
cd ..

# Check if APP_KEY is empty, if so, generate the key
if grep -q "APP_KEY=" src/.env && grep -q "APP_KEY=" src/.env | grep -q "^[^#;]"; then
  echo "Laravel key already set."
else
  echo "Generating Laravel key..."
  cd src/ || exit
  php artisan key:generate
  cd ..
fi

# Check if previous steps were successful, then start the application
if [ $? -eq 0 ]; then
  echo "Setup completed successfully. Starting application..."
  start_application
else
  echo "Setup failed. Please check the error messages above."
fi
