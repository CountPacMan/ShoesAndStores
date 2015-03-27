# Shoes and Stores for Epicodus Assessment
## by Daniel Toader
### Date: March 27, 2015
#### Description
This web app lets a user add shoe brands and stores to a catalog. Each brand can be carried by multiple stores and each store can carry multiple brands.

#### Setup instructions
1. Clone this git repository
2. Set your localhost root folder to ~/ShoesAndStores/web/
3. Ensure PHP server is running.
4. Start Postgres and import shoes.sql database into a new database shoes
5. Use Composer to install required dependencies in the composer.json file
6. Start the web app by pointing your browser to the root (http://localhost:8000/)

#### Copyright © 2015, Daniel Toader

#### License: [MIT](https://github.com/twbs/bootstrap/blob/master/LICENSE")  

#### Technologies used
- HTML5
- CSS3
- Bootstrap ver 3.3.1
- PHP (tested to run on PHP ver 5.6.6)
- Silex ver 1.2.3
- Twig ver 1.18.0
- PHPUnit ver 4.5.0
- PostgreSQL ver 9.4.1

#### Database
CREATE DATABASE shoes; CREATE TABLE stores (id serial PRIMARY KEY, name varchar); CREATE TABLE brands (id serial PRIMARY KEY, brand varchar); CREATE TABLE stores_brands (id serial PRIMARY KEY, store_id int REFERENCES stores, brand_id int REFERENCES brands); CREATE DATABASE shoes_test WITH TEMPLATE shoes;
