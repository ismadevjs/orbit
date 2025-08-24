#!/bin/sh

# Define the source and destination paths
SOURCE_PATH="/var/www/enb"
DESTINATION_PATH="/var/www/.env"

# Check if the source file exists
if [ ! -f "$SOURCE_PATH" ]; then
  echo "Error: Source file $SOURCE_PATH does not exist."
  exit 1
fi

# Copy the file to the destination
cp "$SOURCE_PATH" "$DESTINATION_PATH"

# Check if the copy operation was successful
if [ $? -eq 0 ]; then
  echo "File copied successfully to $DESTINATION_PATH."
else
  echo "Error: Failed to copy file."
  exit 1
fi

# Set proper ownership and permissions
chown www-data:www-data "$DESTINATION_PATH"
chmod 644 "$DESTINATION_PATH"

# Confirm permissions were set
if [ $? -eq 0 ]; then
  echo "Ownership and permissions set successfully for $DESTINATION_PATH."
else
  echo "Error: Failed to set ownership or permissions."
  exit 1
fi

# Laravel setup steps
echo "Running Laravel setup steps..."

pwd

whoami

ls -la
# Run composer install to install dependencies
composer install --optimize-autoloader --no-dev
if [ $? -eq 0 ]; then
  echo "Composer install completed successfully."
else
  echo "Error: Failed to run composer install."
  exit 1
fi


# Generate application key
php artisan key:generate
if [ $? -eq 0 ]; then
  echo "Application key generated successfully."
else
  echo "Error: Failed to generate application key."
  exit 1
fi

# Link storage folder
php artisan storage:link
if [ $? -eq 0 ]; then
  echo "Storage folder linked successfully."
else
  echo "Error: Failed to link storage folder."
  exit 1
fi

# Final success message
echo "The file has been successfully copied, configured, and Laravel setup steps completed."
