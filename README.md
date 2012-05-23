Aferthought is a php code repository to expediate setting up a new website.

It relies heavily on my toolbox - [vertebrox](https://github.com/rockerest/vertebrox).  
You can see a demo of this package at [afterthought.thomasrandolph.info](http://afterthought.thomasrandolph.info).

There are two files that you need that are not included in this repository for various reasons.

1. the database credentials.  Create a file called `afterthought.conf` and place it in your copy of `backbone` from [my framework](https://github.com/rockerest/myframework) linked above.  
  place the following lines in it:  
    `$config['db']['dbname'] = "afterthought";`  
    `$config['db']['user'] = "[YourDatabaseUsername]";`  
    `$config['db']['pass'] = "[TheUser'sPassword";`  
    `$config['db']['host'] = "[TheHostName/IPOfTheDBServer]";`  
	
2. a decent .htaccess file to hide that database file.  
  I include the following lines to hide the .conf file:  
    `<Files *.conf>`  
    `    order deny,allow`  
    `    deny from all`  
    `</Files>`  
	
This website template includes the following third-party items:  
[Fugue Icon Pack](http://p.yusukekamiyamane.com/) by Yusuke Kamiyamane which is Licensed under the [CC-BY-3.0](http://creativecommons.org/licenses/by/3.0/).
  
This work is licensed under the Creative Commons Attribution-ShareAlike 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/ or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.