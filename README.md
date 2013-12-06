Moorfields central staff database module
========================================

Requires:

 - php5-sybase

Installation:

1. Ensure these lines are in [global] section of /etc/freetds/freetds.conf:

tds version = 8.0
client charset = UTF-8

2. Set reasonable timeouts in /etc/freetds/freetds.conf:

timeout = 3
connect timeout = 3

3. Add to modules list in config/local/common.php

'modules' => array(
	...
	'mehstaffdb',
	...
),

3. Add a db connection called 'db_staff' to config/local/common.php with the details of your connection:

'db_staff' => array(
	'connectionString' => 'dblib:host=HOSTNAME;dbname=DATABASE',
	'username' => 'USERNAME':,
	'password' => 'PASSWORD',
),

4. Optionally add one of the following params directives to override the defaults:

'mehstaffdb_always_refresh' => true,
'mehstaffdb_cache_time' => 300,

(the former overrides the latter)
