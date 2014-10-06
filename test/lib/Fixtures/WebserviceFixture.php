<?php
namespace Omocha\Fixtures;

/**
 * @RestService
 * @Option(output) XML
 * @Option(template) service.tpl
 * @Option(on_error) 404
 * @Option null
 */
class WebserviceFixture {
	/**
	 * @Config(MySQL) ['user', 'password', 'database']
	 * @Config(PostgreSQL) user=user,password=password,dbname=database
	 * @Config(SQLite) database.db
	 */
	public $connection;
}
?>