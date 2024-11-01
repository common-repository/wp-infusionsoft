<?php
/*
	Plugin Name: WP Infusionsoft
	Plugin URI: http://taylorlovett.com/wordpress-plugins
	Description: WP Infusionsoft is a plugin for handling and displaying web forms created by the popular email marketing site Infusionsoft. Simply create a web form in the easy-to-use admin panel and paste the form code i.e. [infusion form=1] in to any page, post, category, or archive in which you want the form to show. Also comes with a web form widget to drag-and-drop in to your sidebar.
	Version: 1.0.0
	Author: <a href="http://www.taylorlovett.com" title="Maryland Wordpress Developer">Taylor Lovett</a>
	Author URI: http://www.taylorlovett.com
*/

/*
	Copyright (C) 2010-2011 Taylor Lovett, taylorlovett.com (admin@taylorlovett.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 3 of the License, or
	(at your option) any later version.
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
require_once('infusionsoftdb.php');
if (!class_exists('Infusionsoft')) {
	class Infusionsoft extends InfusionsoftDB {
		var $adminOptionsName = 'infusionAdminOptions';
		var $widgetOptionsName = 'widget_infusionOptin';
		
		function Infusionsoft() {
			parent::InfusionsoftDB();
		}
		
		function getAdminOptions() {
			$infusionAdminOptions = array('show_widget_home' => 1, 'show_widget_pages' => 1, 'show_widget_singles' => 1, 'show_widget_categories' => 1, 'show_widget_archives' => 1); // defaults
			$infusionOptions = get_option($this->adminOptionsName);
			if (!empty($infusionOptions)) {
				foreach ($infusionOptions as $key => $option)
					$infusionAdminOptions[$key] = $option;
			}
			update_option($this->adminOptionsName, $infusionAdminOptions);
			return $infusionAdminOptions;
		}
		
		function init() {
			$this->getAdminOptions();
			$this->registerSidebar();
		}
		
		
		function registerSidebar() {
			register_sidebar_widget(__('Infusionsoft Optin'), array($this, 'widget_infusionOptin'));
			register_widget_control('Infusionsoft Optin', array($this, 'infusionOptin_control'), 300, 200);
		}
		
		function infusionOptin_control() {
			$option = get_option($this->widgetOptionsName);
			if (empty($option)) $option = array('title' => 'Infusionsoft Optin', 'hidden_code' => '', 'submit_button_text' => 'Submit', 'add_name' => 1, 'add_phone' => 0, 'add_address' => 0);
			if ($_POST[widget_infusionOptin_title]) {
				$option[title] = parent::encodeOption($_POST[widget_infusionOptin_title]);
				$option[hidden_code] = parent::encodeOption($_POST[widget_infusionOptin_hiddenCode]);
				$option[submit_button_text] = parent::encodeOption($_POST[widget_infusionOptin_submitButtonText]);
				$option[add_name] = $_POST[widget_infusionOptin_addName];
				$option[add_phone] = $_POST[widget_infusionOptin_addPhone];
				$option[add_address] = $_POST[widget_infusionOptin_addAddress];
				update_option($this->widgetOptionsName, $option);
				$option = get_option($this->widgetOptionsName);
			}
			$addName_checked = ($option[add_name] == 1) ? 'checked="checked"' : '';
			$addPhone_checked = ($option[add_phone] == 1) ? 'checked="checked"' : '';
			$addAddress_checked = ($option[add_address] == 1) ? 'checked="checked"' : '';
			?>
            <p><label for="widget_infusionOptin_title">Title:<input class="widefat" type="text" name="widget_infusionOptin_title" value="<?php echo parent::decodeOption($option['title'], 1, 0); ?>" /></label></p>
            <p><label for="widget_infusionOptin_hiddenCode">* Hidden Code:<input class="widefat" type="text" name="widget_infusionOptin_hiddenCode" value="<?php echo parent::decodeOption($option['hidden_code'], 1, 0); ?>" /></label></p>
            <p><label for="widget_infusionOptin_submitButtonText">Submit Button Text:<input class="widefat" type="text" name="widget_infusionOptin_submitButtonText" value="<?php echo parent::decodeOption($option['submit_button_text'], 1, 0); ?>" /></label></p>
            <p><label for="widget_infusionOptin_addName"><input <?php echo $addName_checked; ?> type="checkbox" name="widget_infusionOptin_addName" value="1" />Add Name Field</label></p>
            <p><label for="widget_infusionOptin_addPhone"><input <?php echo $addPhone_checked; ?> type="checkbox" name="widget_infusionOptin_addPhone" value="1" />Add Phone Field</label></p>
            <p><label for="widget_infusionOptin_addAddress"><input <?php echo $addAddress_checked; ?> type="checkbox" name="widget_infusionOptin_addAddress" value="1" />Add Address Field</label></p>
            <?php
		}
		
		function widget_infusionOptin($args) {
			extract($args);
			$admin_option = $this->getAdminOptions();
			if ((is_front_page() and $admin_option[show_widget_home] != 1) or (is_single() and $admin_option[show_widget_singles] != 1) or 
				(is_page() and $admin_option[show_widget_pages] != 1) or (is_category() and $admin_option[show_widget_categories] != 1) or 
				(is_archive() and $admin_option[show_widget_archives] != 1))
				return false;
			$option = get_option($this->widgetOptionsName);
			if (empty($option)) $option = array('title' => 'Infusionsoft Optin', 'hidden_code' => '', 'submit_button_text' => 'Submit', 'add_name' => 1, 'add_phone' => 0, 'add_address' => 0);
			echo $before_widget; ?>
			<form method="post" action="https://pcisecure.Infusionsoft.com/AddForms/processFormSecure.jsp" class="infusionform-side">
<?php echo parent::decodeOption($option[hidden_code], 1, 1) . "\n" . $before_title . parent::decodeOption($option[title], 1, 1) . $after_title; ?>
			<ul>
            <?php if ($option[add_name] == 1) : ?>
            <li><label for="Contact0FirstName">First Name:</label><input type="text" name="Contact0FirstName" /></li>
            <li><label for="Contact0LastName">Last Name:</label><input type="text" name="Contact0LastName" /></li>
            <?php endif; ?>
            <li><label for="Contact0Email">* Email:</label><input type="text" name="Contact0Email" /></li>
            <?php if ($option[add_phone] == 1) : ?>
            <li><label for="Contact0Phone1">Phone:</label><input type="text" name="Contact0Phone1" /></li>
            <?php endif; ?>
            <?php if ($option[add_address] == 1) : ?>
            <li><label for="Contact0StreetAddress1">Address</label><input type="text" name="Contact0StreetAddress1" /></li>
            <li><label for="Contact0City">City</label><input type="text" name="Contact0City" /></li>
            <li><label for="Contact0State">State</label><input type="text" name="Contact0State" /></li>
            <li><label for="Contact0PostalCode">Zip</label><input type="text" name="Contact0PostalCode" /></li>
            <?php endif; ?>
			</ul>
            
            <p><input type="submit" value="<?php echo parent::decodeOption($option[submit_button_text], 1, 0); ?>" /></p>
            
            </form>
			<?php echo $after_widget;
		}
		
		function addHeaderCode() {
			?>
			<!-- WP Infusionsoft -->
			<link rel="stylesheet" href="<?php echo get_option('siteurl'); ?>/wp-content/plugins/infusionsoft/infusionsoft.css" type="text/css" media="screen" />
			<?php	
		}
		
		function printAdminPage() {
			$admin_options = $this->getAdminOptions();
			if ($_POST[create_form]) {
				parent::insertForm($_POST[form_name], $_POST[form_title], $_POST[submit_button_text], $_POST[hidden_code], $_POST[add_name], $_POST[add_phone], $_POST[add_address]);
			} elseif ($_POST[general_options]) {
				$admin_options[show_widget_categories] = $_POST[show_widget_categories];
				$admin_options[show_widget_singles] = $_POST[show_widget_singles];
				$admin_options[show_widget_pages] = $_POST[show_widget_pages];
				$admin_options[show_widget_archives] = $_POST[show_widget_archives];
				$admin_options[show_widget_home] = $_POST[show_widget_home];
				update_option($this->adminOptionsName, $admin_options);
			} elseif ($_POST[update])
				parent::updateForm($_POST[form_name], $_POST[form_title], $_POST[submit_button_text], $_POST[hidden_code], $_POST[add_name], $_POST[add_phone], $_POST[add_address], $_POST[fid]);
			elseif ($_POST[delete])
				parent::deleteForm($_POST[fid]);
			?>
            <div id="infusionsoft-admin">
            
                <div id="icon-themes" class="icon32"></div>
            
                <h2>WP Infusionsoft</h2>
            
                <div id="general-settings" class="postbox">
            
                    <h3 class="hndle"><span>General Settings</span></h3>
            
                    <div class="inside">
            
                        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            
                            <p>Show Sidebar InfusionSoft Optin Widget:</p>
            
                            <ul>
            
                                <li><label><input value="1" type="checkbox" name="show_widget_home" <?php if ($admin_options[show_widget_home] == 1) echo 'checked="checked"'; ?> /> On Homepage</label></li>
            
                                <li><label><input value="1" type="checkbox" name="show_widget_pages" <?php if ($admin_options[show_widget_pages] == 1) echo 'checked="checked"'; ?> /> On Pages</label></li>
            
                                <li><label><input value="1" type="checkbox" name="show_widget_singles" <?php if ($admin_options[show_widget_singles] == 1) echo 'checked="checked"'; ?> /> On Single Posts</label></li>
            
                                <li><label><input value="1" type="checkbox" name="show_widget_categories" <?php if ($admin_options[show_widget_categories] == 1) echo 'checked="checked"'; ?> /> On Categories</label></li>
            
                                <li><label><input value="1" type="checkbox" name="show_widget_archives" <?php if ($admin_options[show_widget_archives] == 1) echo 'checked="checked"'; ?> /> On Archives</label></li>
            
                                <li><input type="submit" value="Update" name="general_options" /></li>
            
                            </ul>
            
                        </form>
            
                    </div>
            
                </div>
            
                <div id="create-forms" class="postbox">
            
                    <h3 class="hndle"><span>Create A New Form</span></h3>
            
                    <div class="inside">
            
                        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            
                            <ul>
            
                                <li><label for="form_name">Form Name:</label> <input type="text" name="form_name" /> (Must be unique)</li>
            
                                <li><label for="form_title">Form Title Header:</label> <input type="text" name="form_title" /></li>
            
                                <li><label for="submit_button_text">Submit Button Text:</label> <input type="text" name="submit_button_text" /></li>
            
                                <li><label for="hidden_code">Hidden Code:</label> <input type="text" name="hidden_code" /></li>
            
                                <li><label for="add_name"><input <?php echo $addName_checked; ?> type="checkbox" name="add_name" value="1" />Add Name Field</label></li>
            
                                <li><label for="add_phone"><input <?php echo $addPhone_checked; ?> type="checkbox" name="add_phone" value="1" />Add Phone Field</label></li>
            
                                <li><label for="add_address"><input <?php echo $addAddress_checked; ?> type="checkbox" name="add_address" value="1" />Add Address Field</label></li>
            
                                <li><input type="submit" value="Create Form" name="create_form" /></li>
            
                            </ul>
            
                        </form>
            
                    </div>
            
                </div>
            
                <div id="manage-forms" class="postbox">
            
                    <h3 class="hndle"><span>Manage Forms</span></h3>
            
                    <div class="inside">
            
                        <ul>
            
                            <li>
            
                                <ul>
            
                                    <li>Form Name</li>
            
                                    <li>Form Title</li>
            
                                    <li>Submit Text</li>
            
                                    <li>Hidden Code</li>
            
                                    <li class="short">Name Field</li>
            
                                    <li class="short">Phone Field</li>
            
                                    <li class="short">Address Field</li>
            
                                    <li>Code to Show Form in Blog</li>
            
                                </ul>
            
                            </li>
            
                        <?php
            
                        $forms = parent::selectAllForms();
            
                        $i = 0;
            
                        foreach ($forms as $form) { ?>
            
                            <li>
            
                                <form method="post" <?php if ($i % 2 != 0) echo 'class="evenrow"'; ?> action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            
                                    <input type="text" name="form_name" value="<?php echo $form->form_name; ?>" />
            
                                    <input type="text" name="form_title" value="<?php echo $form->form_title; ?>" />
            
                                    <input type="text" name="submit_button_text" value="<?php echo $form->submit_button_text; ?>" />
            
                                    <input type="text" name="hidden_code" value="<?php echo $form->hidden_code; ?>" />
            
                                    <input type="checkbox" name="add_name" value="1" <?php if ($form->add_name == 1) echo 'checked="checked"'; ?> />
            
                                    <input type="checkbox" name="add_phone" value="1" <?php if ($form->add_phone == 1) echo 'checked="checked"'; ?> />
            
                                    <input type="checkbox" name="add_address" value="1" <?php if ($form->add_address == 1) echo 'checked="checked"'; ?> />
            
                                    <input type="hidden" name="fid" value="<?php echo $form->id; ?>" />
            
                                    <input type="text" name="showcode" value="[infusion form=<?php echo $form->id; ?>]" />
            
                                    <input type="submit" value="Update" name="update" />
            
                                    <input type="submit" value="Delete" name="delete" /> (ID: <?php echo $form->id; ?>)
            
                                </form>
            
                             </li>	
            
                        <?php
            
                            $i++;
            
                        }
            
                        ?>
            
                        </ul>
            
                    </div>
            
                </div>
            
            </div>
            <?php
		}
		
		function contentFilter($content) {
			$matches = array();
			preg_match_all('/\[infusion form=([0-9]+)\]/si', $content, $matches);
			for ($i = 0; $i < count($matches[0]); $i++) {
				$content = str_replace($matches[0][$i], $this->getFormCode($matches[1][$i]), $content);	
			}
			return $content;
		}
		
		function getFormCode($fid) {
			$form = parent::selectForm($fid, '');
			$out = '<form method="post" action="https://pcisecure.Infusionsoft.com/AddForms/processFormSecure.jsp" class="infusionform">' . parent::decodeOption($form->hidden_code, 1, 1) . "\n";
			$out .= '<h3 class="title">' . parent::decodeOption($form->form_title, 1, 1) . '</h3>' . "\n" . '<ul>';
			if ($form->add_name == 1) {
				$out .= '<li><label for="Contact0FirstName">First Name:</label><input type="text" name="Contact0FirstName" /></li>' . "\n";
				$out .= '<li><label for="Contact0LastName">Last Name:</label><input type="text" name="Contact0LastName" /></li>' . "\n";
			}
			$out .= '<li><label for="Contact0Email">* Email:</label><input type="text" name="Contact0Email" /></li>' . "\n";
			if ($form->add_phone == 1) {
				$out .= '<li><label for="Contact0Phone1">Phone:</label><input type="text" name="Contact0Phone1" /></li>' . "\n";
			} if ($form->add_address == 1) {
				$out .= '<li><label for="Contact0StreetAddress1">Address</label><input type="text" name="Contact0StreetAddress1" /></li>' . "\n";
				$out .= '<li><label for="Contact0City">City</label><input type="text" name="Contact0City" /></li>' . "\n";
				$out .= '<li><label for="Contact0State">State</label><input type="text" name="Contact0State" /></li>' . "\n";
				$out .= '<li><label for="Contact0PostalCode">City</label><input type="text" name="Contact0PostalCode" /></li>' . "\n";
			}
			$out .= '</ul><p><input type="submit" value="' . parent::decodeOption($form->submit_button_text, 1, 0) . '" /></li>' . "\n" . '</form>';
			return $out;
		}
	}
}
$infusion = new Infusionsoft();
if (!function_exists('Infusionsoft_ap')) {
	function Infusionsoft_ap() {
		global $infusion;
		if (!isset($infusion)) return;
		if (function_exists('add_options_page')) {
			add_options_page('WP Infusionsoft', 'WP Infusionsoft', 9, basename(_FILE_), array(&$infusion, 'printAdminPage'));	
		}
	}
}
if (isset($infusion)) {
	add_action('wp_head', array(&$infusion, 'addHeaderCode'), 1);
	add_action('admin_head', array(&$infusion, 'addHeaderCode'), 1);
	add_action('activate_Infusionsoft/Infusionsoft.php', array(&$infusion, 'init'));
	add_action('plugins_loaded', array(&$infusion, 'init'), 1);
	add_filter('the_content', array(&$infusion, 'contentFilter'));
	
}
add_action('admin_menu', 'Infusionsoft_ap');
?>