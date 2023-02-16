<?php
/**
 * Pagination Class
 *
 * Handles to manage custom pagination
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Woo_Pr_Pagination_Public{


	var $total_num_pages = -1;
	var $limit = null;
	var $target_page = ""; 
	var $page = 1;
	var $adjacents = 2;
	var $show_counter_num = false;
	var $className = "pagination";
	var $parameterName = "page";
	var $url_friendly = false;

	/* next and previous button*/
	var $nextT = "";
	var $nextI = "&#187;";
	var $prevT = "";
	var $prevI = "&#171;";

	/*****/
	var $calculate = false;

	public function __construct( $ajaxpagination = 'woo_pr_ajax_pagination' ) {
		$this->nextT = esc_html__("Next",'woopoints');
		$this->prevT = esc_html__("Previous",'woopoints');
		$this->ajaxpagination = $ajaxpagination;
	}


	/**
     * Set Total items
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * 
    */
	public function items($value) {
		$this->total_num_pages = (int) $value;
	}		

	/**
     * Set show per page
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * 
    */
	public function limit($value) {
		$this->limit = (int) $value;
	}		

	/**
     * Set sent the page value
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * 
    */
	public function target($value){ 
		$this->target_page = $value;
	}		

	/**
     * Set Current page
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * 
    */
	public function currentPage($value) {
		$this->page = (int) $value;
	}		

	/**
     * Set adjacent pages should be shown on each side of the current page
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * 
    */
	public function adjacents($value) { 
		$this->adjacents = (int) $value;
	}		

	/**
     * Set counter should be show or not
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * 
    */
	public function showCounter($value="") {
		$this->show_counter_num = ($value === true) ? true : false;
	}

	/**
     * Set the class name of the pagination div
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * 
    */
	public function changeClass($value="") {
		$this->className=$value;
	}

	/**
     * Set next button label
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * 
    */
	public function nextLabel($value) { 
		$this->nextT = $value;
	}
	
	/**
     * Set next button icon
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * 
    */
	public function nextIcon($value) {
		$this->nextI = $value;
	}


	/**
     * Set prev button label
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * 
    */
	public function prevLabel($value) { 
		$this->prevT = $value;
	}


	/**
     * Set prev button Icon
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * 
    */
	public function prevIcon($value){ 
		$this->prevI = $value;
	}

	
	/**
     * Set class name of the pagination div
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * 
    */
	public function parameterName($value=""){
		$this->parameterName=$value;
	}


	/**
     * Set url should be urlFriendly
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * 
    */
	public function url_friendly_function($value="%"){
			if(preg_match('/^ *$/i', $value)){
					$this->url_friendly=false;
					return false;
				}
			$this->url_friendly=$value;
		}	

	var $pagination;

	public function pagination(){

	}

	/**
     * Show the pagination
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * 
    */
	public function show(){
			if(!$this->calculate)
				if($this->calculate())
					echo "<div class=\"$this->className\">$this->pagination</div>\n";
	}

	/**
     * return the pagination
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * 
    */
	public function getOutput(){
			if(!$this->calculate)
				if($this->calculate())
					return "<div class=\"$this->className\">$this->pagination</div>\n";
	}


	/**
     * return link
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * 
    */
	public function get_pagenum_link($id){
		if(strpos($this->target_page,'?')===false) {
			if($this->url_friendly)
					return "javascript:void(0);";
				else
					return "javascript:void(0);";
		} else {						
			$addpar = '';						
			if(isset($_GET['search_action_name']) && !empty($_GET['search_action_name']) ) {
				$addpar .= 'search_action_name='.$_GET['search_action_name'].'&';
			} 						
			if(isset($_GET['search_action_email']) && $_GET['search_action_email'] != '' ) {
				$addpar .= 'search_action_email='.$_GET['search_action_email'].'&';
			} 						
			if(isset($_GET['orderby']) && !empty($_GET['orderby']) ) {
				$addpar .= 'orderby='.$_GET['orderby'].'&';
			} 
			if(isset($_GET['order']) && !empty($_GET['order']) ) {
				$addpar .= 'order='.$_GET['order'].'&';
			} 
			return "javascript:void(0);" ;
		}	
	}		


	/**
     * Handle to calculate the pagination
     *
     * @package WooCommerce - Points and Rewards
     * @since 1.0.0
     * 
    */
	public function calculate() {

		$this->pagination = "";
		$this->calculate == true;
		$error = false;
		if($this->url_friendly and $this->url_friendly != '%' and strpos($this->target_page,$this->url_friendly)===false){

				esc_html_e('You specified a wildcard to replace, but it does not exist in the target', 'woopoints')."<br />";
				$error = true;
			}elseif($this->url_friendly and $this->url_friendly == '%' and strpos($this->target_page,$this->url_friendly)===false){
				esc_html_e('Wildcard % needs to be specified in the target to replace the page number', 'woopoints')."<br />";
				$error = true;
			}

		if($this->total_num_pages < 0){
			esc_html_e('It is necessary to specify the number of pages ', 'woopoints').$class->items(1000)."<br />";
				$error = true;
		}

		if($this->limit == null){
					esc_html_e('It is necessary to specify the limit of items to show per page', 'woopoints').$class->limit(10)."<br />";
				$error = true;
		}

		if($error) {
			return false;
		}

		$n = trim($this->nextT.' '.$this->nextI);
		$p = trim($this->prevI.' '.$this->prevT);				

		/* Setup vars for query. */
		if($this->page) {
			$start = ($this->page - 1) * $this->limit;             //first item to display on this page
		}
		else{
			$start = 0;                                //if no page var is given, set start to 0			
		}

		/* Setup page vars for display. */
		$prev = $this->page - 1;                            //previous page is page - 1
		$next = $this->page + 1;                            //next page is page + 1
		$lastpage = ceil($this->total_num_pages/$this->limit);        //lastpage is = total pages / items per page, rounded up.
		$lpm1 = $lastpage - 1;                        //last page minus 1				

		/* 
			Now we apply our rules and draw the pagination object. 
			We're actually saving the code to a variable in case we want to draw it more than once.
		*/
		if($lastpage > 1){

			if($this->page){

					//anterior button
					if($this->page > 1)
							$this->pagination .= "<a class='page-numbers1 prev' href=\"".esc_url($this->get_pagenum_link($prev))."\"  onclick = \"return $this->ajaxpagination('".$prev."')\">$p</a>";
						else
							$this->pagination .= "<span class=\"page-numbers1 disabled\">$p</span>";
				}

			//pages	
			if ($lastpage < 7 + ($this->adjacents * 2)){//not enough pages to bother breaking it up
					for ($counter = 1; $counter <= $lastpage; $counter++){
							if ($counter == $this->page)
									$this->pagination .= "<span class=\"page-numbers1 current\">$counter</span>";
								else
									$this->pagination .= "<a class='page-numbers1' href=\"".esc_url($this->get_pagenum_link($counter))."\" onclick = \"return $this->ajaxpagination('".$counter."')\" >$counter</a>";
						}
				}

			elseif($lastpage > 5 + ($this->adjacents * 2)){//enough pages to hide some

					//close to beginning; only hide later pages
					if($this->page < 1 + ($this->adjacents * 2)){
							for ($counter = 1; $counter < 4 + ($this->adjacents * 2); $counter++){
									if ($counter == $this->page)
											$this->pagination .= "<span class=\"page-numbers1 current\">$counter</span>";
										else
											$this->pagination .= "<a class='page-numbers1' href=\"".esc_url($this->get_pagenum_link($counter))."\" onclick = \"return $this->ajaxpagination('".$counter."')\" >$counter</a>";
								}

							$this->pagination .= "<span class='dots'>...</span>";
							$this->pagination .= "<a class='page-numbers1' href=\"".esc_url($this->get_pagenum_link($lpm1))."\" onclick = \"return $this->ajaxpagination('".$lpm1."')\" >$lpm1</a>";
							$this->pagination .= "<a class='page-numbers1' href=\"".esc_url($this->get_pagenum_link($lastpage))."\" onclick = \"return $this->ajaxpagination('".$lastpage."')\">$lastpage</a>";
						}

					//in middle; hide some front and some back
					elseif($lastpage - ($this->adjacents * 2) > $this->page && $this->page > ($this->adjacents * 2)){
							$this->pagination .= "<a class='page-numbers1' href=\"".esc_url($this->get_pagenum_link(1))."\" onclick = \"return $this->ajaxpagination('1')\" >1</a>";
							$this->pagination .= "<a class='page-numbers1' href=\"".esc_url($this->get_pagenum_link(2))."\" onclick = \"return $this->ajaxpagination('2')\" >2</a>";

							$this->pagination .= "<span class='dots'>...</span>";
							for ($counter = $this->page - $this->adjacents; $counter <= $this->page + $this->adjacents; $counter++)
								if ($counter == $this->page)
										$this->pagination .= "<span class=\"page-numbers1 current\">$counter</span>";
									else
										$this->pagination .= "<a href=\"".esc_url($this->get_pagenum_link($counter))."\" onclick = \"return $this->ajaxpagination('".$counter."')\" >$counter</a>";


							$this->pagination .= "<span class='dots'>...</span>";
							$this->pagination .= "<a class='page-numbers1' href=\"".esc_url($this->get_pagenum_link($lpm1))."\" onclick = \"return $this->ajaxpagination('".$lpm1."')\" >$lpm1</a>";
							$this->pagination .= "<a class='page-numbers1' href=\"".esc_url($this->get_pagenum_link($lastpage))."\" onclick = \"return $this->ajaxpagination('".$lastpage."')\"  >$lastpage</a>";
						}

					//close to end; only hide early pages
					else{
						$this->pagination .= "<a class='page-numbers1' href=\"".esc_url($this->get_pagenum_link(1))."\" onclick = \"return $this->ajaxpagination('1')\"   >1</a>";

						$this->pagination .= "<a class='page-numbers1' href=\"".esc_url($this->get_pagenum_link(2))."\" onclick = \"return $this->ajaxpagination('2')\"  >2</a>";


						$this->pagination .= "<span class='dots'>...</span>";

						for ($counter = $lastpage - (2 + ($this->adjacents * 2)); $counter <= $lastpage; $counter++)
							if ($counter == $this->page)
									$this->pagination .= "<span class=\"page-numbers1 current\">$counter</span>";
								else
									$this->pagination .= "<a class='page-numbers1' href=\"".esc_url($this->get_pagenum_link($counter))."\" onclick = \"return $this->ajaxpagination('".$counter."')\" >$counter</a>";
					}
				}

			if($this->page){
				//siguiente button
				if ($this->page < $counter - 1)
						$this->pagination .= "<a class='page-numbers1 next' href=\"".esc_url($this->get_pagenum_link($next))."\" onclick = \"return $this->ajaxpagination('".$next."')\" >$n</a>";
					else
						$this->pagination .= "<span class=\"page-numbers1 disabled\">$n</span>";
					if($this->show_counter_num)$this->pagination .= "<div class=\"pagination_data\">($this->total_num_pages Pages)</div>";
			}
		}

		return true;
	}
}