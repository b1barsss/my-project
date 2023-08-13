# Authorization and registration (велосипед)
------
### Functionality:
+ Register user
+ Login (Remember me checkbox, save to cookie)
+ Logout
+ Update (username/password)
------
### Requirements:  
+ Set root folder as "`/public`"
+ php8.1  
+ mysql
------
### Guide:
Configure the config.php file:
```php
return [
    'database' => [
        'connection' => 'mysql',  //your_connection
        'host' => 'localhost',    //your_host
        'dbname' => 'my-project', //your_database_name
        'username' => 'bibarys',  //your_username
        'password' => '1',        //your_password
    ],
];
```
Create these table on your database.  
Table `groups` (In this example, if user has group_id 1, then user is admin):
```sql
CREATE TABLE `your_database_name`.`groups`
(
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
`name` VARCHAR(255) NOT NULL ,
`permissions` VARCHAR(255) NOT NULL ,
PRIMARY KEY (`id`)
);

INSERT INTO `groups` (`id`, `name`, `permissions`)
VALUES (1, 'Administrator', '{\"admin\":1}'), (2, 'Standard user', '')
```
Table `users` (You can modify group_id as you want):
```sql
CREATE TABLE `your_database_name`.`groups`
(
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
`username` VARCHAR(255) NOT NULL ,
`email` VARCHAR(255) NOT NULL ,
`password` VARCHAR(255) NOT NULL ,
 `group_id` INT NOT NULL DEFAULT '1' ,
PRIMARY KEY (`id`)
);
```
Table `user_sessions`
```sql
CREATE TABLE `your_database_name`.`user_sessions`
(
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
`user_id` INT UNSIGNED NOT NULL ,
`hash` VARCHAR(255) NOT NULL ,
PRIMARY KEY (`id`)
);
```
------
Now your project is ready!
