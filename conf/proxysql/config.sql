SET mysql-monitor_writer_is_also_reader='false';
SET admin-web_enabled='true';

LOAD MYSQL VARIABLES TO RUNTIME;
SAVE MYSQL VARIABLES TO DISK;

DELETE FROM mysql_servers;
INSERT INTO mysql_servers (hostgroup_id,hostname,port,max_replication_lag) VALUES (0,'mysql1',3306,1);
LOAD MYSQL SERVERS TO RUNTIME;
SAVE MYSQL SERVERS TO DISK;

DELETE FROM mysql_users;
INSERT INTO mysql_users (username,password,transaction_persistent,active) values ('sysbench','sysbench',0,1);
INSERT INTO mysql_users (username,password,transaction_persistent,active) values ('poc','poc',0,1);
LOAD MYSQL USERS TO RUNTIME;
SAVE MYSQL USERS TO DISK; 

DELETE FROM mysql_query_rules;
INSERT INTO mysql_query_rules (rule_id,active,match_digest,destination_hostgroup,apply) VALUES (1,1,'^SELECT.*FOR UPDATE',0,1),(2,1,'^SELECT',0,1);
LOAD MYSQL QUERY RULES TO RUNTIME;
SAVE MYSQL QUERY RULES TO DISK;