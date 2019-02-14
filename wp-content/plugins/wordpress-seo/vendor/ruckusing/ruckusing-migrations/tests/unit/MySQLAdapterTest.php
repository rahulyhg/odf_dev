<?php

/**
 * Implementation of MySQLAdapterTest
 * To run these unit-tests an empty test database needs to be setup in database.inc.php
 * and of course, it has to really exist.
 *
 * @category Ruckusing_Tests
 * @package  Ruckusing_Migrations
 * @author   (c) Cody Caughlan <codycaughlan % gmail . com>
 */
class MySQLAdapterTest extends PHPUnit_Framework_TestCase
{
    /** @var Ruckusing_Adapter_MySQL_Base */
    protected $adapter;

    /**
     * Setup commands before test case
     */
    protected function setUp()
    {
        $ruckusing_config = require RUCKUSING_BASE . '/config/database.inc.php';

        if (!is_array($ruckusing_config) || !(array_key_exists("db", $ruckusing_config) && array_key_exists("mysql_test", $ruckusing_config['db']))) {
            $this->markTestSkipped("\n'mysql_test' DB is not defined in config/database.inc.php\n\n");
        }

        $test_db = $ruckusing_config['db']['mysql_test'];

        //setup our log
        $logger = Ruckusing_Util_Logger::instance(RUCKUSING_BASE . '/tests/logs/test.log');

        $this->adapter = new Ruckusing_Adapter_MySQL_Base($test_db, $logger);
        $this->adapter->logger->log("Test run started: " . date('Y-m-d g:ia T') );
    }//setUp()

    /**
     * shutdown commands after test case
     */
    protected function tearDown()
    {
        //delete any tables we created
        if ($this->adapter->has_table('users', true)) {
            $this->adapter->drop_table('users');
        }

        if ($this->adapter->has_table(RUCKUSING_TS_SCHEMA_TBL_NAME, true)) {
            $this->adapter->drop_table(RUCKUSING_TS_SCHEMA_TBL_NAME);
        }

        $db = "test_db";
        //delete any databases we created
        if ($this->adapter->database_exists($db)) {
            $this->adapter->drop_database($db);
        }
    }

    /**
     * test create schema version table
     */
    public function test_create_schema_version_table()
    {
        //force drop, start from a clean slate
        if ($this->adapter->has_table(RUCKUSING_TS_SCHEMA_TBL_NAME, true)) {
            $this->adapter->drop_table(RUCKUSING_TS_SCHEMA_TBL_NAME);
        }
        $this->adapter->create_schema_version_table();
        $this->assertEquals(true, $this->adapter->has_table(RUCKUSING_TS_SCHEMA_TBL_NAME, true));
    }

    /**
     * test to ensure table does not exist
     */
    public function test_ensure_table_does_not_exist()
    {
        $this->assertEquals(false, $this->adapter->has_table('unknown_table'));
    }

    /**
     * test to ensure table does exist
     */
    public function test_ensure_table_does_exist()
    {
        //first make sure the table does not exist
        $users = $this->adapter->has_table('users', true);
        $this->assertEquals(false, $users);
        $t1 = new Ruckusing_Adapter_MySQL_TableDefinition($this->adapter, "users", array('options' => 'Engine=InnoDB'));
        $t1->column("name", "string", array('limit' => 20));
        $sql = $t1->finish();

        //now make sure it does exist
        $users = $this->adapter->table_exists('users', true);
        $this->assertEquals(true, $users);
        $this->remove_table('users');
    }

    /**
     * Drop a table
     *
     * @param string $table the table to drop
     */
    private function remove_table($table)
    {
        if ($this->adapter->has_table($table, true)) {
            $this->adapter->drop_table($table);
        }
    }

    /**
     * test database creation
     */
    public function test_database_creation()
    {
        $db = "test_db";
        $this->assertEquals(true, $this->adapter->create_database($db));
        $this->assertEquals(true, $this->adapter->database_exists($db));

        $db = "db_does_not_exist";
        $this->assertEquals(false, $this->adapter->database_exists($db));
    }

    /**
     * test drop database
     */
    public function test_database_droppage()
    {
        $db = "test_db";
        //create it
        $this->assertEquals(true, $this->adapter->create_database($db));
        $this->assertEquals(true, $this->adapter->database_exists($db));

        //drop it
        $this->assertEquals(true, $this->adapter->drop_database($db));
        $this->assertEquals(false, $this->adapter->database_exists($db));
    }

    /**
     * test throwing exception on index too long
     */
    public function test_index_name_too_long_throws_exception()
    {
        $bm = new Ruckusing_Migration_Base($this->adapter);
        try {
            srand();
            $table_name = "users_" . rand(0, 1000000);
            $table = $bm->create_table($table_name, array('id' => false));
            $table->column('somecolumnthatiscrazylong', 'integer');
            $table->column('anothercolumnthatiscrazylongrodeclown', 'integer');
            $sql = $table->finish();
            $bm->add_index($table_name, array('somecolumnthatiscrazylong', 'anothercolumnthatiscrazylongrodeclown'));
        } catch (Ruckusing_Exception $exception) {
            if (Ruckusing_Exception::INVALID_INDEX_NAME == $exception->getCode()) {
                $bm->drop_table($table_name);

                return;
            }
        }
        $this->fail('Expected to raise & catch Ruckusing_Exception::INVALID_INDEX_NAME');
    }

    /**
     * test custom primary key
     */
    public function test_custom_primary_key_1()
    {
        $t1 = new Ruckusing_Adapter_MySQL_TableDefinition($this->adapter, "users", array('id' => true, 'options' => 'Engine=InnoDB') );
        $t1->column("user_id", "integer", array("primary_key" => true));
        $actual = $t1->finish(true);
        $this->remove_table('users');
    }

    /**
     * test column definition
     */
    public function test_column_definition()
    {
        $expected = "`age` varchar(255)";
        $this->assertEquals($expected, $this->adapter->column_definition("age", "string"));

        $expected = "`age` varchar(32)";
        $this->assertEquals($expected, $this->adapter->column_definition("age", "string", array('limit' => 32)));

        $expected = "`age` varchar(32) NOT NULL";
        $this->assertEquals($expected, $this->adapter->column_definition("age", "string",
                array('limit' => 32, 'null' => false)));

        $expected = "`age` varchar(32) DEFAULT 'abc' NOT NULL";
        $this->assertEquals($expected, $this->adapter->column_definition("age", "string",
                array('limit' => 32, 'default' => 'abc', 'null' => false)));

        $expected = "`age` varchar(32) DEFAULT 'abc'";
        $this->assertEquals($expected, $this->adapter->column_definition("age", "string",
                array('limit' => 32, 'default' => 'abc')));

        $expected = "`age` int(11)";
        $this->assertEquals($expected, $this->adapter->column_definition("age", "integer"));

        $expected = "`active` tinyint(1)";
        $this->assertEquals($expected, $this->adapter->column_definition("active", "boolean"));

        $expected = "`weight` bigint(20)";
        $this->assertEquals($expected, $this->adapter->column_definition("weight", "biginteger", array('limit' => 20)));

        $expected = "`weight` mediumint(4)";
        $this->assertEquals($expected, $this->adapter->column_definition("weight", "mediuminteger", array('limit' => 4)));

        $expected = "`weight` tinyint(1)";
        $this->assertEquals($expected, $this->adapter->column_definition("weight", "tinyinteger", array('limit' => 1)));

        $expected = "`age` int(11) AFTER `height`";
        $this->assertEquals($expected, $this->adapter->column_definition("age", "integer", array("after" => "height")));

        $expected = "`adapter` enum('mysql','pgsql','ha\'xor')";
        $this->assertEquals($expected, $this->adapter->column_definition("adapter", "enum", array('values' => array('mysql', 'pgsql', "ha'xor"))));

        $expected = "`age` tinytext";
        $this->assertEquals($expected, $this->adapter->column_definition("age", "tinytext"));

	$expected = "`age` longblob";
	$this->assertEquals($expected, $this->adapter->column_definition("age", "longbinary"));
    }//test_column_definition

    /**
     * test column info
     */
    public function test_column_info()
    {
        //create it
        $this->adapter->execute_ddl("CREATE TABLE `users` ( name varchar(20) );");

        $expected = array();
        $actual = $this->adapter->column_info("users", "name");
        $this->assertEquals('varchar(20)', $actual['type'] );
        $this->assertEquals('name', $actual['field'] );
        $this->remove_table('users');
    }

    /**
     * test rename table
     */
    public function test_rename_table()
    {
        //create it
        $this->adapter->execute_ddl("CREATE TABLE `users` ( name varchar(20) );");
        $this->assertEquals(true, $this->adapter->has_table('users') );
        $this->assertEquals(false, $this->adapter->has_table('users_new') );
        //rename it
        $this->adapter->rename_table('users', 'users_new');
        $this->assertEquals(false, $this->adapter->has_table('users') );
        $this->assertEquals(true, $this->adapter->has_table('users_new') );
        //clean up
        $this->adapter->drop_table('users_new');
    }

    /**
     * test rename column
     */
    public function test_rename_column()
    {
        //create it
        $this->adapter->execute_ddl("CREATE TABLE `users` ( name varchar(20) DEFAULT 'abc' NOT NULL);");

        $before = $this->adapter->column_info("users", "name");
        $this->assertEquals('varchar(20)', $before['type'] );
        $this->assertEquals('name', $before['field'] );

        //rename the name column
        $this->adapter->rename_column('users', 'name', 'new_name');

        $after = $this->adapter->column_info("users", "new_name");
        $this->assertEquals('varchar(20)', $after['type'] );
        $this->assertEquals('new_name', $after['field'] );
        
        // assert that we didnt mess up the NOT NULL and DEFAULT
        $this->assertEquals('NO', $after['null'] );
        $this->assertEquals('abc', $after['default'] );

        $this->remove_table('users');
    }

    /**
     * test add column
     */
    public function test_add_column()
    {
        //create it
        $this->adapter->execute_ddl("CREATE TABLE `users` ( name varchar(20) );");

        $col = $this->adapter->column_info("users", "name");
        $this->assertEquals("name", $col['field']);

        //add column
        $this->adapter->add_column("users", "fav_color", "string", array('limit' => 32));
        $col = $this->adapter->column_info("users", "fav_color");
        $this->assertEquals("fav_color", $col['field']);
        $this->assertEquals('varchar(32)', $col['type'] );

        //add column
        $this->adapter->add_column("users", "latitude", "decimal", array('precision' => 10, 'scale' => 2));
        $col = $this->adapter->column_info("users", "latitude");
        $this->assertEquals("latitude", $col['field']);
        $this->assertEquals('decimal(10,2)', $col['type'] );

        //add column with unsigned parameter
        $this->adapter->add_column("users", "age", "integer", array('unsigned' => true));
        $col = $this->adapter->column_info("users", "age");
        $this->assertEquals("age", $col['field']);
        $this->assertEquals('int(11) unsigned', $col['type'] );

        //add column with biginteger datatype
        $this->adapter->add_column("users", "weight", "biginteger", array('limit' => 20));
        $col = $this->adapter->column_info("users", "weight");
        $this->assertEquals("weight", $col['field']);
        $this->assertEquals('bigint(20)', $col['type'] );

        // Test that the collate option works
        $this->adapter->add_column('users', 'shortcode', 'string', array('collate' => 'utf8_bin'));
        $col = $this->adapter->column_info('users', 'shortcode');
        $this->assertEquals('utf8_bin', $col['collation']);

        // Test that the character option works, default collation of latin1 is latin1_swedish_ci
        // http://dev.mysql.com/doc/refman/5.0/en/charset-mysql.html
        $this->adapter->add_column('users', 'highschool', 'string', array('character' => 'latin1'));
        $col = $this->adapter->column_info('users', 'highschool');
        $this->assertEquals('latin1_swedish_ci', $col['collation']);

        $this->remove_table('users');
    }

    /**
     * test drop column
     */
    public function test_remove_column()
    {
        //create it
        $this->adapter->execute_ddl("CREATE TABLE `users` ( name varchar(20), age int(3) );");

        //verify it exists
        $col = $this->adapter->column_info("users", "name");
        $this->assertEquals("name", $col['field']);

        //drop it
        $this->adapter->remove_column("users", "name");

        //verify it does not exist
        $col = $this->adapter->column_info("users", "name");
        $this->assertEquals(null, $col);
        $this->remove_table('users');
    }

    /**
     * test change column
     */
    public function test_change_column()
    {
        //create it
        $this->adapter->execute_ddl("CREATE TABLE `users` ( name varchar(20), age int(3) );");

        //verify its type
        $col = $this->adapter->column_info("users", "name");
        $this->assertEquals('varchar(20)', $col['type'] );
        $this->assertEquals('', $col['default'] );

        //change it, add a default too!
        $this->adapter->change_column("users", "name", "string", array('default' => 'abc', 'limit' => 128));

        $col = $this->adapter->column_info("users", "name");
        $this->assertEquals('varchar(128)', $col['type'] );
        $this->assertEquals('abc', $col['default'] );

        // Test collate option
        $this->adapter->change_column("users", "name", "string", array('default' => 'abc', 'limit' => 128,
                'collate' => 'ascii_bin'));
        $col = $this->adapter->column_info('users', 'name');
        $this->assertEquals('ascii_bin', $col['collation']);

        $this->remove_table('users');
    }

    /**
     * test add index
     */
    public function test_add_index()
    {
        //create it
        $this->adapter->execute_ddl("CREATE TABLE `users` ( name varchar(20), age int(3), title varchar(20) );");
        $this->adapter->add_index("users", "name");

        $this->assertEquals(true, $this->adapter->has_index("users", "name") );
        $this->assertEquals(false, $this->adapter->has_index("users", "age") );

        $this->adapter->add_index("users", "age", array('unique' => true));
        $this->assertEquals(true, $this->adapter->has_index("users", "age") );

        $this->adapter->add_index("users", "title", array('name' => 'index_on_super_title'));
        $this->assertEquals(true, $this->adapter->has_index("users", "title", array('name' => 'index_on_super_title')));
        $this->remove_table('users');
    }

    /**
     * test add multi column index
     */
    public function test_multi_column_index()
    {
        //create it
        $this->adapter->execute_ddl("CREATE TABLE `users` ( name varchar(20), age int(3) );");
        $this->adapter->add_index("users", array("name", "age"));

        $this->assertEquals(true, $this->adapter->has_index("users", array("name", "age") ));

        //drop it
        $this->adapter->remove_index("users", array("name", "age"));
        $this->assertEquals(false, $this->adapter->has_index("users", array("name", "age") ));
        $this->remove_table('users');
    }

    /**
     * test drop index with default index name
     */
    public function test_remove_index_with_default_index_name()
    {
        //create it
        $this->adapter->execute_ddl("CREATE TABLE `users` ( name varchar(20), age int(3) );");
        $this->adapter->add_index("users", "name");

        $this->assertEquals(true, $this->adapter->has_index("users", "name") );

        //drop it
        $this->adapter->remove_index("users", "name");
        $this->assertEquals(false, $this->adapter->has_index("users", "name") );
        $this->remove_table('users');
    }

    /**
     * test drop index with custom index name
     */
    public function test_remove_index_with_custom_index_name()
    {
        //create it
        $this->adapter->execute_ddl("CREATE TABLE `users` ( name varchar(20), age int(3) );");
        $this->adapter->add_index("users", "name", array('name' => 'my_special_index'));

        $this->assertEquals(true, $this->adapter->has_index("users", "name", array('name' => 'my_special_index')) );

        //drop it
        $this->adapter->remove_index("users", "name", array('name' => 'my_special_index'));
        $this->assertEquals(false, $this->adapter->has_index("users", "name", array('name' => 'my_special_index')) );
        $this->remove_table('users');
    }

    /**
     * test add timestamps
     */
    public function test_add_timestamps()
    {
        $bm = new Ruckusing_Migration_Base($this->adapter);
        $table_name = 'users';
        
        //create it
        $this->adapter->execute_ddl("CREATE TABLE `$table_name` ( name varchar(20) );");

        $col = $this->adapter->column_info($table_name, "name");
        $this->assertEquals("name", $col['field']);

        //add timestamps
        $bm->add_timestamps($table_name);
        
        $col = $this->adapter->column_info($table_name, "created_at");
        $this->assertEquals("created_at", $col['field']);
        $this->assertEquals('datetime', $col['type'] );
        
        $col = $this->adapter->column_info($table_name, "updated_at");
        $this->assertEquals("updated_at", $col['field']);
        $this->assertEquals('timestamp', $col['type'] );

        $this->remove_table($table_name);
    }

    /**
     * test remove timestamps
     */
    public function test_remove_timestamps()
    {
        $bm = new Ruckusing_Migration_Base($this->adapter);
        $table_name = 'users';
        
        //create it
        $this->adapter->execute_ddl("CREATE TABLE `$table_name` ( name varchar(20), created_at datetime not null, updated_at datetime not null );");
        
        //verify they exists
        $col = $this->adapter->column_info($table_name, "created_at");
        $this->assertEquals("created_at", $col['field']);
        
        $col = $this->adapter->column_info($table_name, "updated_at");
        $this->assertEquals("updated_at", $col['field']);

        //drop them
        $bm->remove_timestamps($table_name);

        //verify they does not exist
        $col = $this->adapter->column_info($table_name, "created_at");
        $this->assertEquals(null, $col);
        $col = $this->adapter->column_info($table_name, "updated_at");
        $this->assertEquals(null, $col);
        
        $this->remove_table($table_name);
    }

    /**
     * test add empty colmun names timestamps
     */
    public function test_add_empty_timestamps()
    {
        $bm = new Ruckusing_Migration_Base($this->adapter);
        $table_name = 'users';
        
        //create it
        $this->adapter->execute_ddl("CREATE TABLE `$table_name` ( name varchar(20) );");

        $col = $this->adapter->column_info($table_name, "name");
        $this->assertEquals("name", $col['field']);

        try{
          //add timestamps
          $bm->add_timestamps($table_name, "", "");
        } catch (Ruckusing_Exception $exception) {
            if (Ruckusing_Exception::INVALID_ARGUMENT == $exception->getCode()) {
                $this->remove_table($table_name);
                return;
            }
        }
        $this->fail('Expected to raise & catch Ruckusing_Exception::INVALID_ARGUMENT');
    }

    /**
     * test add named timestamps
     */
    public function test_add_named_timestamps()
    {
        $bm = new Ruckusing_Migration_Base($this->adapter);
        $table_name = 'users';
        $created_name = 'created';
        $updated_name = 'updated';
        
        //create it
        $this->adapter->execute_ddl("CREATE TABLE `$table_name` ( name varchar(20) );");

        $col = $this->adapter->column_info($table_name, "name");
        $this->assertEquals("name", $col['field']);

        //add timestamps
        $bm->add_timestamps($table_name, $created_name, $updated_name);
        
        $col = $this->adapter->column_info($table_name, $created_name);
        $this->assertEquals($created_name, $col['field']);
        $this->assertEquals('datetime', $col['type'] );
        
        $col = $this->adapter->column_info($table_name, $updated_name);
        $this->assertEquals($updated_name, $col['field']);
        $this->assertEquals('timestamp', $col['type'] );

        $this->remove_table($table_name);
    }
    
    /**
     * test remove named timestamps
     */
    public function test_remove_named_timestamps()
    {
        $bm = new Ruckusing_Migration_Base($this->adapter);
        $table_name = 'users';
        $created_name = 'created';
        $updated_name = 'updated';

        //create it
        $this->adapter->execute_ddl("CREATE TABLE `$table_name` ( name varchar(20), `$created_name` datetime not null, `$updated_name` datetime not null );");

        //verify they exists
        $col = $this->adapter->column_info($table_name, $created_name);
        $this->assertEquals($created_name, $col['field']);
        
        $col = $this->adapter->column_info($table_name, $updated_name);
        $this->assertEquals($updated_name, $col['field']);

        //drop them
        $bm->remove_timestamps($table_name, $created_name, $updated_name);

        //verify they does not exist
        $col = $this->adapter->column_info($table_name, $created_name);
        $this->assertEquals(null, $col);
        $col = $this->adapter->column_info($table_name, $updated_name);
        $this->assertEquals(null, $col);
        
        $this->remove_table($table_name);
    }

    /**
     * test string quoting
     */
    public function test_string_quoting()
    {
        $unquoted = "Hello Sam's";
        $quoted = "Hello Sam\'s";
        $this->assertEquals($quoted, $this->adapter->quote_string($unquoted));
    }

    /**
     * test multiple queries
     */
    public function test_multiple_queries()
    {
        $this->adapter->execute_ddl("CREATE TABLE `users` ( name varchar(20) );");
        $this->adapter->multi_query("
            INSERT INTO users (name)
            VALUES ('Bill');
            INSERT INTO users (name)
            VALUES ('John')
        ");

        $users = $this->adapter->select_all('SELECT * FROM users');
        $expected = array(
            array('name' => 'Bill'),
            array('name' => 'John'),
        );

        $this->assertEquals($expected, $users);
    }

    /**
     * test multiple calls to the multi_query method
     * 
     * (Once failed with "Commands out of sync; you can't run this command now")
     *
     */
    public function test_multiple_multi_query_calls()
    {
        $this->adapter->multi_query("SELECT VERSION()");
        $this->adapter->multi_query("SELECT VERSION()");
        $this->adapter->multi_query("SELECT VERSION(); SELECT VERSION(); ");
    }

    /**
     * test multiple queries with error in first query
     *
     * @expectedException Ruckusing_Exception
     */
    public function test_multiple_queries_error_in_first_query()
    {
        $this->adapter->execute_ddl("CREATE TABLE `users` ( name varchar(20) );");
        $this->adapter->multi_query("
            INSERT INTO users (name2)
            VALUES ('Bill');
            INSERT INTO users (name)
            VALUES ('John')
        ");
    }

    /**
     * test multiple queries with error in second query
     *
     * @expectedException Ruckusing_Exception
     */
    public function test_multiple_queries_error_in_second_query()
    {
        $this->adapter->execute_ddl("CREATE TABLE `users` ( name varchar(20) );");
        $this->adapter->multi_query("
            INSERT INTO users (name)
            VALUES ('Bill');
            INSERT INTO users (name2)
            VALUES ('John')
        ");
    }

    /**
     * test query with trigger
     */
    public function test_query_trigger()
    {
        $this->adapter->multi_query("
            DROP TABLE IF EXISTS test;
            DROP TABLE IF EXISTS log;

            CREATE TABLE `test` (
                `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `content` TEXT NOT NULL
            ) ENGINE = MYISAM;

            CREATE TABLE `log` (
                `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `msg` VARCHAR( 255 ) NOT NULL,
                `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `row_id` INT( 11 ) NOT NULL
            ) ENGINE = MYISAM;

            CREATE TRIGGER `update_test` AFTER INSERT ON `test`
            FOR EACH ROW BEGIN
               INSERT INTO log SET msg = 'insert', row_id = NEW.id;
            END;

            INSERT INTO test (id, content)
                VALUES
                  (2, 'Cup');
        ");

        $log = $this->adapter->select_one('SELECT id, msg, row_id FROM log');
        $expected = array(
            'id' => 1,
            'msg' => 'insert',
            'row_id' => 2,
        );
        $this->assertEquals($expected, $log);

        $this->adapter->drop_table('test');
        $this->adapter->drop_table('log');
    }
}//class
