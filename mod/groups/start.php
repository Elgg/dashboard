<?php
	/**
	 * Elgg groups plugin
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	/**
	 * Initialise the groups plugin.
	 * Register actions, set up menus
	 */
	function groups_init()
	{
		global $CONFIG;
		
		// Set up the menu for logged in users
		if (isloggedin()) 
		{
			add_menu(elgg_echo('groups'), $CONFIG->wwwroot . "pg/groups/" . $_SESSION['user']->username,array(
				menu_item(elgg_echo('groups:new'), $CONFIG->wwwroot."pg/groups/" . $_SESSION['user']->username . "/new/"),
				menu_item(elgg_echo('groups:yours'), $CONFIG->wwwroot . "pg/groups/" . $_SESSION['user']->username),
				menu_item(elgg_echo('groups:all'), $CONFIG->wwwroot . "pg/groups/" . $_SESSION['user']->username . "/world/"),
			),'groups');
		}
		else
		{
			add_menu(elgg_echo('groups'), $CONFIG->wwwroot . "mod/groups/",array(
						menu_item(elgg_echo('groups:all'),$CONFIG->wwwroot."mod/groups/all.php"),
					));
		}
		
		// Register a page handler, so we can have nice URLs
		register_page_handler('groups','groups_page_handler');
		
		// Register a URL handler for groups
		register_entity_url_handler('groups_url','group','all');
		
		// Register an icon handler for groups
		register_page_handler('icon','groups_icon_handler');
		
		// Register some actions
		register_action("groups/edit",false, $CONFIG->pluginspath . "groups/actions/edit.php");
		register_action("groups/join",false, $CONFIG->pluginspath . "groups/actions/join.php");
		register_action("groups/leave",false, $CONFIG->pluginspath . "groups/actions/leave.php");
		register_action("groups/joinrequest",false, $CONFIG->pluginspath . "groups/actions/joinrequest.php");
		
		register_action("groups/addtogroup",false, $CONFIG->pluginspath . "groups/actions/addtogroup.php");
		
		
		// For now, we'll hard code the groups profile items as follows:
		// TODO make this user configurable
		
		// Language short codes must be of the form "groups:key"
		// where key is the array key below
		$CONFIG->group = array(
		
			'title' => 'text',
			'description' => 'longtext',
			//'location' => 'tags',
			'interests' => 'tags',
			//'skills' => 'tags',
			//'contactemail' => 'email',
			//'phone' => 'text',
			//'mobile' => 'text',
			'website' => 'url',
							   
		);
	}
	
	/**
	 * Group page handler
	 *
	 * @param array $page Array of page elements, forwarded by the page handling mechanism
	 */
	function groups_page_handler($page) 
	{
		global $CONFIG;
		
		// The username should be the file we're getting
		if (isset($page[0])) {
			set_input('username',$page[0]);
		}
		
		if (isset($page[1])) 
		{
    		switch($page[1]) 
    		{
    			case "new":  
    				include($CONFIG->pluginspath . "groups/new.php");
          		break;
    			case "world":  
   					include($CONFIG->pluginspath . "groups/all.php");
          		break;
    		}
		}
		else
		{
			// Include the standard profile index
			include($CONFIG->pluginspath . "groups/index.php");
		}
		
	}
	
	/**
	 * Populates the ->getUrl() method for group objects
	 *
	 * @param ElggEntity $entity File entity
	 * @return string File URL
	 */
	function groups_url($entity) {
		
		global $CONFIG;
		
		return $CONFIG->url . "pg/view/" . $entity->getGUID() . "/";
		
	}
	
	// Make sure the groups initialisation function is called on initialisation
	register_elgg_event_handler('init','system','groups_init');
?>