#!/bin/bash

# --- Configuration ---
API_DIR="api"
SPA_DIR="spa-react"

# --- Colors for better output ---
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# --- ASCII Art Log ---
echo -e "${YELLOW}"
echo -e "
  _____ _       ___   __    __  ____   ___   __    __ 
 / ___/| |     /   \\ |  |__|  ||    \\ /   \\ |  |__|  |
(   \\_ | |    |     ||  |  |  ||  D  )     ||  |  |  |
 \\__  || |___ |  O  ||  |  |  ||    /|  O  ||  |  |  |
 /  \\ ||     ||     ||  \`  '  ||    \\|     ||  \`  '  |
 \\    ||     ||     | \\      / |  .  \\     | \\      / 
  \\___||_____| \\___/   \\_/\_/  |__|\_|\___/   \\_/\_/  
                                                      
"
echo -e "${NC}"

restore_terminal() {
  stty echo # Re-enable echoing
}

trap 'echo -e "\n${YELLOW}Stopping development servers...${NC}"; kill $(jobs -p); restore_terminal; exit' INT

stty -echo # Disable echoing

# --- Start Laravel API ---
cd "$API_DIR" || { echo -e "${RED}Error: API directory not found!${NC}"; exit 1; }

if ! command -v php &> /dev/null
then
    echo -e "${RED}Error: PHP is not installed or not in PATH.${NC}"
    exit 1
fi

# Run php artisan serve in background, redirecting stdout and stderr to /dev/null
# 2>&1 redirects stderr to stdout, and > /dev/null redirects stdout to /dev/null
php artisan serve > /dev/null 2>&1 &
API_PID=$!
echo -e "${GREEN}Laravel API started with PID: $API_PID ${NC}"
cd ..

# --- Start React SPA ---
cd "$SPA_DIR" || { echo -e "${RED}Error: SPA directory not found!${NC}"; exit 1; }

if ! command -v npm &> /dev/null
then
    echo -e "${RED}Error: npm is not installed or not in PATH.${NC}"
    exit 1
fi

# Run npm start in background, redirecting stdout and stderr to /dev/null
npm run dev > /dev/null 2>&1 &
SPA_PID=$!
echo -e "${GREEN}React SPA started with PID: $SPA_PID ${NC}"
cd ..

# --- Keep the script running ---
echo -e "${GREEN}\nBoth servers are running. Press Ctrl+C to stop them.${NC}"
wait

restore_terminal