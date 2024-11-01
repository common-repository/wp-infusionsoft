<?php
/*
	WP Infusionsoft DB class is a parent to the Infusionsoft Class
	By Taylor Lovett - http://www.taylorlovett.com
*/
if (!class_exists('InfusionsoftDB')) {
	class InfusionsoftDB {
		var $forms_table;
		function InfusionsoftDB() {
			global $wpdb;
			$this->forms_table = $wpdb->prefix . 'infusionsoft_forms';
			$this->createTables();
		}
		
		function encodeOption($option) {
			//if ($escape_quotes == 1) $option = mysql_escape_r
			return htmlspecialchars($option, ENT_QUOTES);
		} 
			
		function decodeOption($option, $strip_slashes = 1, $decode_html_chars = 1) {
			if ($strip_slashes == 1) $option = stripslashes($option);
			if ($decode_html_chars == 1) $option = htmlspecialchars_decode($option);
			return $option;
		}
		
		function createTables() {
			global $wpdb;
			if(!$this->tablesExist()) {
				$sql = " CREATE TABLE `".$this->forms_table."` (
						`id` INT( 11 ) NOT NULL auto_increment,
						`form_name` VARCHAR( 100 ) NOT NULL ,
						`form_title` VARCHAR( 200 ) NOT NULL ,
						`submit_button_text` VARCHAR( 200 ) NOT NULL ,
						`hidden_code` TEXT NOT NULL ,
						`add_name` INT( 1 ) NOT NULL DEFAULT '0',
						`add_phone` INT( 1 ) NOT NULL DEFAULT '0',
						`add_address` INT( 1 ) NOT NULL DEFAULT '0',
						PRIMARY KEY ( `id` )
						) ENGINE = MYISAM AUTO_INCREMENT=1 ";
				require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
				dbDelta($sql);
				return true;
			}
			return false;
		}
		
		function insertForm($form_name, $form_title, $submit_button_text, $hidden_code, $add_name, $add_phone, $add_address) {
			global $wpdb;
			if (empty($form_name)) return false;
			$test = $this->selectForm('', $form_name);
			if (empty($test)) {
				$wpdb->query("INSERT INTO " . $this->forms_table . " VALUES('', '".$this->encodeOption($form_name)."', '".$this->encodeOption($form_title)."', '".$this->encodeOption($submit_button_text)."', '".$this->encodeOption($hidden_code)."', '$add_name', '$add_phone', '$add_address')");
				return true;
			}
			return false;
		}
		
		function tablesExist() {
			global $wpdb;
			return 	($wpdb->get_var("show tables like '". $this->forms_table . "'") == $this->forms_table);
		}
		
		function updateForm($form_name, $form_title, $submit_button_text, $hidden_code, $add_name, $add_phone, $add_address, $fid) {
			global $wpdb;
			if (empty($form_name)) return false;
			$test = $this->selectForm('', $form_name);
			if (!empty($test) and $test->id != $fid) // if form_name is different then make sure it is unique
				return false;
			$wpdb->query("UPDATE " . $this->forms_table . " SET form_name='".$this->encodeOption($form_name)."', form_title='".$this->encodeOption($form_title)."', submit_button_text='".$this->encodeOption($submit_button_text)."', hidden_code='".$this->encodeOption($hidden_code)."', add_name='$add_name', add_phone='$add_phone', add_address='$add_address' WHERE id='$fid'");
			return true;
		}
		
		function deleteForm($fid) {
			global $wpdb;
			$wpdb->query("DELETE FROM " . $this->forms_table . " WHERE id='$fid'");
			return true;
		}
		
		function selectAllForms() {
			global $wpdb;
			return $wpdb->get_results("SELECT * FROM " . $this->forms_table . " ORDER BY form_name ASC");	
		}
		
		function selectForm($fid, $form_name) {
			global $wpdb;
			return $wpdb->get_row("SELECT * FROM " . $this->forms_table . " WHERE id='$fid' or form_name = '$form_name'");
		}
	}
}
?>