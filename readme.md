### Requirements
- PHP 8 
- Docker-compose
- Symfony CLI

### Install
1. **git clone https://github.com/andrewskigar/stock_history.git stock_history_app**
2. **symfony composer install**
3. **docker-compose up -d**
4. **symfony server:start -d**
5. create .env.local and add RAPID_API_KEY=  (key from email)
6. run phpunit tests **symfony php bin/phpunit**
