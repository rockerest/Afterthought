Aferthought is a php code repository to expediate setting up a new website.

It relies heavily on [my framework](https://github.com/rockerest/myframework).  
You can see a demo of this package at [afterthought.thomasrandolph.info](http://afterthought.thomasrandolph.info). Log in with username `admin@example.com` and password `password`.

There are two files that you need that are not included in this repository for various reasons.

1. the database credentials.  Create a file called `afterthought.db` and place it in your copy of `backbone` from [my framework](https://github.com/rockerest/myframework) linked above.  
  place the following lines in it:  
    `<?php`  
    `    $GLOBALS['dbname'] = $dbname = "afterthought";`  
    `    $GLOBALS['user'] = $user = "[YourDatabaseUsername]";`  
    `    $GLOBALS['pass'] = $pass = "[TheUser'sPassword";`  
    `    $GLOBALS['host'] = $host = "[TheHostName/IPOfTheDBServer]";`  
    `?>`  
	
2. a decent .htaccess file to hide that database file.  
  I include the following lines to hide the .db file:  
    `<Files *.db>`  
    `    order deny,allow`  
    `    deny from all`  
    `</Files>`  
  
This work is licensed under the Creative Commons Attribution-ShareAlike 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/ or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.