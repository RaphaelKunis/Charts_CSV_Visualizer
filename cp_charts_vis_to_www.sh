#!/bin/bash
# DECLARE variable
DIR_CHART=public_html
DIR_WEB=/var/www/html/chart_visualizer
echo "Removing Webdirectory $DIR_WEB"
sudo rm -r $DIR_WEB
echo "Copy new files to $DIR_WEB"
sudo cp -r $DIR_CHART $DIR_WEB
echo "Setting rights"
sudo chown -R www-data:www-data $DIR_WEB
# Finish
echo "Finished"
