<?php

class WPML_Upgrade_Add_Word_Count_Column_To_Strings implements IWPML_Upgrade_Command {

	/** @var bool $result */
	private $result = true;

	/** @var WPML_Upgrade_Schema */
	private $upgrade_schema;

	public function __construct( array $args ) {
		$this->upgrade_schema = $args[0];
	}

	/** @return bool */
	private function run() {
		$table  = 'icl_strings';
		$column = 'word_count';

		if ( $this->upgrade_schema->does_table_exist( $table ) ) {
			if ( ! $this->upgrade_schema->does_column_exist( $table, $column ) ) {
				$this->upgrade_schema->add_column( $table, $column, 'int unsigned NULL' );
			}
		}

		return $this->result;
	}

	public function run_admin() {
		return $this->run();
	}

	public function run_ajax() {
		return $this->run();
	}

	public function run_frontend() {
		return $this->run();
	}

	/** @return bool */
	public function get_results() {
		return $this->result;
	}
}
