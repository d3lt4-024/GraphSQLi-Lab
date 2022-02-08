<?php
# If you are having problems connecting to the MySQL database and all of the variables below are correct
# try changing the 'db_server' variable from localhost to 127.0.0.1. Fixes a problem due to sockets.

# Database variables
$_Config = array();
$_Config['db_server'] = '127.0.0.1';
$_Config['db_database'] = 'graphsqli_lab_db';
$_Config['db_user'] = 'graphsqli_lab';
$_Config['db_password'] = 'Vuphptit@2410';
$_Config['db_port'] = '3306';

# Default security level
#   Default value for the security level with each session.
#   The default is 'impossible'. You may wish to set this to either 'low', 'medium', 'high' or impossible'.
$_Config['default_level'] = 'low';
