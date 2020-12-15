# About
TNP WEBSITE

# Installation

### Quick install on Debian/Ubuntu
```
git clone https://github.com/TNP-GNDEC/TNP-WEBSITE.git
```
Once the Repositary is cloned jump into the cloned directory using :
```
cd TNP-WEBSITE
```
**Create a file named .env and copy the contents of .env.example into .env file.**

Run the following commands inorder to install all the dependencies : 
```
composer install
npm install
```
Generate the php application key :
```
php artisan key:generate
```
Now you are Ready to launch the web application : 
```
php artisan serve
npm run watch
```