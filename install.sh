#!/bin/bash

# BotCMS 1-Click Platform Installer
# Fast, Modular, and Secure CMS setup

set -e

# Visual colors
RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

echo -e "${CYAN}===================================================${NC}"
echo -e "${CYAN}             BotCMS Platform Installer             ${NC}"
echo -e "${CYAN}===================================================${NC}"
echo ""

# 1. Check PHP version
if ! command -v php &> /dev/null; then
    echo -e "${RED}Error: PHP is not installed. Please install PHP 8.4+ first.${NC}"
    exit 1
fi

PHP_VERSION=$(php -r 'echo PHP_VERSION;')
echo -e "Detected PHP Version: ${GREEN}${PHP_VERSION}${NC}"

# 2. Check Composer
if ! command -v composer &> /dev/null; then
    echo -e "${RED}Error: Composer is not installed. Please install Composer first.${NC}"
    exit 1
fi
echo -e "Detected Composer: ${GREEN}Installed${NC}"

# 3. Install composer packages if vendor directory does not exist
if [ ! -d "vendor" ]; then
    echo -e "${BLUE}Installing Composer packages... (This may take a moment)${NC}"
    composer install
else
    echo -e "Composer packages: ${GREEN}Already installed${NC}"
fi

# 4. Run Laravel Installer Command
echo ""
echo -e "${BLUE}Launching BotCMS Setup Console...${NC}"
echo ""
php artisan botcms:install
