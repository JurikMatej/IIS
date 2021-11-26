# IIS Project

## Description 

Information Systems Project - an Auction System with PHP Slim framework & jQuery

## Authors

 * Peter Rúček, xrucek00
 * Marek Miček, xmicek08
 * Matej Jurík, xjurik12

________________________

## Setup

1. Clone this repository
2. Run ```composer install```
3. Run ```npm install```
4. Copy ```.env.example``` file into a new ```.env``` file and fill out all fields
5. To edit the application functionality:
   1. JavaScript: ```npm run build-watch```
   2. PHP: ```composer start``` - runs a localhost server at port 8080 to test the application
6. Deploy: 
   1. Make sure ```.env``` debug variables are turned off
      1. `APP_DEBUG=0`
      2. `APP_DISPLAY_ERROR_DETAILS=0`
   2. Host the production ready application somewhere
   3. Enjoy a new, full-fledged auction system
