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
  
I couldn't find a good license, because there are too many goddamn licenses, so I wrote this:  

This work is licensed under the following terms:  
1. You may copy, distribute, transmit and modify this work  
2. You may include this work in any product, commercial or otherwise  
3. You must attribute this work to the author (me) wherever you use it  
4. You must clearly include a copy of this license when you share this work  
5. Any of the above conditions can be waived by me at any time  
6. Any public domain items in this work are not affected by this license  

The following term is part of the license, and must be included in any copies.

1. You are strongly encouraged to share this work if you modify or improve it, under this or an equivalent license.