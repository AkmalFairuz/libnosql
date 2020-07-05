# Libnosql
Fast, realtime and object-oriented database. Suggested for PocketMine-MP Database.
Non SQL database due to "realtime" database.

### How to load
Copy src folder to your src plugins folder or use DEVirion

### Example
```php
<?php
// TODO: You must load libnosql src folder. Will auto loaded if you're using for PocketMine plugins 
use libnosql\LibNoSQL;

LibNoSQL::setDatabaseDirectory("db2");
LibNoSQL::init();

/* Automatic created when table not exists */
$t = LibNoSQL::getTable("realname");
$t->setString("akmalfairuz", "AkmalFairuz");
$t->setString("steve", "Steve");

$t = LibNoSQL::getTable("money");
$t->setInt("akmalfairuz", 10000);
$t->setInt("steve", 19923);

$t = LibNoSQL::getTable("friend");
$t->setArray("akmalfairuz", ["steve"]);
$t->setArray("steve", ["akmalfairuz"]);

$t = LibNoSQL::getTable("spawn");
$t->setFloat("x", 256.55);
$t->setFloat("y", 65.49);
$t->setFloat("z", 257.67);

$t = LibNoSQL::getTable("clientId");
$t->setInt("akmalfairuz", 1234567890123456);
```