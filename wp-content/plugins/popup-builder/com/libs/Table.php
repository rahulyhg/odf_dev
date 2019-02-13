<?php

namespace sgpbDataTable;

use sgpb\SubscriptionPopup;
require_once(dirname(__FILE__).'/ListTable.php');
file_exists(SG_POPUP_CLASSES_POPUPS_PATH.'SubscriptionPopup.php') && require_once(SG_POPUP_CLASSES_POPUPS_PATH.'SubscriptionPopup.php');

class SGPBTable extends SGPBListTable
{
	protected $id = '';
	protected $columns = array();
	protected $displayColumns = array();
	protected $sortableColumns = array();
	protected $tablename = '';
	protected $rowsPerPage = 10;
	protected $initialOrder = array();
	private $previewPopup = false;
	private $isVisibleExtraNav = true;

	public function __construct($id, $popupPreviewId = false)
	{
		$this->id = $id;
		$this->previewPopup = $popupPreviewId;
		parent::__construct(array(
			'singular'=> 'wp_'.$id, //singular label
			'plural' => 'wp_'.$id.'s', //plural label
			'ajax' => false
		));
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function setRowsPerPage($rowsPerPage)
	{
		$this->rowsPerPage = $rowsPerPage;
	}

	public function setColumns($columns)
	{
		$this->columns = $columns;
	}

	public function getColumns()
	{
		return $this->columns;
	}

	public function setDisplayColumns($displayColumns)
	{
		$this->displayColumns = $displayColumns;
	}

	public function setSortableColumns($sortableColumns)
	{
		$this->sortableColumns = $sortableColumns;
	}

	public function setTablename($tablename)
	{
		$this->tablename = $tablename;
	}

	public function setInitialSort($orderableColumns)
	{
		$this->initialOrder = $orderableColumns;
	}

	public function get_columns()
	{
		return $this->displayColumns;
	}

	public function setIsVisibleExtraNav($isVisibleExtraNav)
	{
		$this->isVisibleExtraNav = $isVisibleExtraNav;
	}

	public function getIsVisibleExtraNav()
	{
		return $this->isVisibleExtraNav;
	}

	public function getNavPopupsConditions()
	{
		return '';
	}

	public function prepare_items()
	{
		global $wpdb;
		$table = $this->tablename;

		$query = 'SELECT '.implode(', ', $this->columns).' FROM '.$table;
		$this->customizeQuery($query);

		$totalItems = count($wpdb->get_results($query)); //return the total number of affected rows

		if ($this->previewPopup) {
			$totalItems -= 1;
		}
		$perPage = $this->rowsPerPage;

		$totalPages = ceil($totalItems/$perPage);

		$orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'ASC';
		$order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : '';

		if (isset($this->initialOrder) && empty($order)) {
			foreach ($this->initialOrder as $key => $value) {
				$order = $value;
				$orderby = $key;
			}
		}

		if (!empty($orderby) && !empty($order)) {
			$query .= ' ORDER BY '.$orderby.' '.$order;
		}

		$paged = isset($_GET["paged"]) ? (int)$_GET["paged"] : '';

		if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
			$paged = 1;
		}

		//adjust the query to take pagination into account
		if (!empty($paged) && !empty($perPage)) {
			$offset = ($paged - 1) * $perPage;
			$query .= ' LIMIT '.(int)$offset.','.(int)$perPage;
		}

		$this->set_pagination_args(array(
			"total_items" => $totalItems,
			"total_pages" => $totalPages,
			"per_page" => $perPage,
		));

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		$items = $wpdb->get_results($query, ARRAY_N);
		/*Remove popup data when its class does not exist.*/
		$this->customizeRowsData($items);

		$this->items = $items;
	}

	public function customizeRowsData(&$items) {

	}

	public function get_sortable_columns() {
		return $this->sortableColumns;
	}

	public function display_rows()
	{
		//get the records registered in the prepare_items method
		$records = $this->items;

		//get the columns registered in the get_columns and get_sortable_columns methods
		list($columns, $hidden) = $this->get_column_info();

		if (!empty($records)) {
			foreach($records as $rec) {
				echo '<tr>';

				$this->customizeRow($rec);
				for ($i = 0; $i<count($rec); $i++) {
					echo '<td>'.stripslashes($rec[$i]).'</td>';
				}

				echo '</tr>';
			}
		}
	}

	public function customizeRow(&$row)
	{

	}

	public function customizeQuery(&$query)
	{

	}

	public function __toString()
	{
		$this->prepare_items(); ?>
		<form method="get" id="posts-filter">
		<p class="search-box">
			 <input type="hidden" name="post_type" value="popupbuilder" />
			 <?php $this->search_box('search', 'search_id'); ?>
		</p>
		<?php $this->display();?>
		</form>
		<?php
		return '';
	}

	// parent class method overriding
	public function extra_tablenav($which)
	{
		$isVisibleExtraNav = $this->getIsVisibleExtraNav();

		if (!$isVisibleExtraNav) {
			return '';
		}
		

		

		
		?>
		<div class="alignleft actions daterangeactions">
			<label class="screen-reader-text" for="sgpb-subscription-popup"><?php _e('Filter by popup', SG_POPUP_TEXT_DOMAIN)?></label>
			<?php echo $this->getNavPopupsConditions(); ?>

			<label class="screen-reader-text" for="sgpb-subscribers-dates"><?php _e('Filter by date', SG_POPUP_TEXT_DOMAIN)?></label>
			<?php  echo $this->getNavDateConditions(); ?>
			
			<input name="filter_action" id="post-query-submit" class="button" value="<?php _e('Filter', SG_POPUP_TEXT_DOMAIN)?>" type="submit">
		</div>
		<?php
	}
}
