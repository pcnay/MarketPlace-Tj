

USE bd_marketplace;
LOAD DATA
LOCAL INFILE 'categories.csv'
INTO TABLE t_Categories
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\r\n';
/* CHARACTER SET  'utf8'; */
 /*IGNORE 1 LINES;*/

USE bd_marketplace;
LOAD DATA
LOCAL INFILE 'disputes.csv'
INTO TABLE t_Disputes
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\r\n';
/* CHARACTER SET  'utf8'; */
 /*IGNORE 1 LINES;*/


USE bd_marketplace;
LOAD DATA
LOCAL INFILE 'messages.csv'
INTO TABLE t_Messages
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\r\n';
/* CHARACTER SET  'utf8'; */
 /*IGNORE 1 LINES;*/

USE bd_marketplace;
LOAD DATA
LOCAL INFILE 'orders.csv'
INTO TABLE t_Orders
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\r\n';
/* CHARACTER SET  'utf8'; */
 /*IGNORE 1 LINES;*/

USE bd_marketplace;
LOAD DATA
LOCAL INFILE 'products.csv'
INTO TABLE t_Products
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\r\n';
/* CHARACTER SET  'utf8'; */
 /*IGNORE 1 LINES;*/
 

USE bd_marketplace;
LOAD DATA
LOCAL INFILE 'sales.csv'
INTO TABLE t_Sales
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\r\n';
 /* CHARACTER SET  'utf8'; */
 /*IGNORE 1 LINES;*/

USE bd_marketplace;
LOAD DATA
LOCAL INFILE 'stores.csv'
INTO TABLE t_Stores
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\r\n';
 /* CHARACTER SET  'utf8'; */
 /*IGNORE 1 LINES;*/

USE bd_marketplace;
LOAD DATA
LOCAL INFILE 'subcategories.csv'
INTO TABLE t_Subcategories
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\r\n';
/* CHARACTER SET  'utf8'; */
 /*IGNORE 1 LINES;*/

USE bd_marketplace;
LOAD DATA
LOCAL INFILE 'users.csv'
INTO TABLE t_Users
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\r\n';
/* CHARACTER SET  'utf8'; */
 /*IGNORE 1 LINES;*/
