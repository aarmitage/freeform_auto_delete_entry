<?php if ( ! defined('APP_VER')) exit('No direct script access allowed');


/**
 * Freeform Extension
 * 
 * @author    Andrew Armitage <andrew@armitageonline.co.uk>
 * @copyright Copyright (c) 2015 Andrew Armitage
 * @license   none
 */

class Freeform_no_save_end_ext {

	var $name           = 'Freeform auto delete entry';
	var $version        = '1.0';
	var $description    = 'Deletes the form entry from the database using freeform_module_insert_end hook. Notifications unaffected.';
	var $settings_exist = 'n';
	var $docs_url       = '';

	/**
	 * Class Constructor
	 */
	function __construct()
	{
		// Make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();
	}

	// --------------------------------------------------------------------

	/**
	 * Activate Extension
	 */
	function activate_extension()
	{
		// add the row to exp_extensions
		$this->EE->db->insert('extensions', array(
			'class'    => get_class($this),
			'method'   => 'freeform_module_insert_end',
			'hook'     => 'freeform_module_insert_end',
			'settings' => '',
			'priority' => 10,
			'version'  => $this->version,
			'enabled'  => 'y'
		));
	}

	/**
	 * Update Extension
	 */
	function update_extension($current = '')
	{
		// Nothing to change...
		return FALSE;
	}

	/**
	 * Disable Extension
	 */
	function disable_extension()
	{
		// Remove all freeform_module_insert_end rows from exp_extensions
		$this->EE->db->where('class', get_class($this))
		             ->delete('extensions');
	}

	// --------------------------------------------------------------------

	/**
	 * freeform_module_insert_end hook
	 */
	public function freeform_module_insert_end($inputs, $entry_id, $form_id, $obj)
	{
		if ( ! $obj->edit AND
	  	( ! $obj->multipage OR
	    ($obj->multipage AND $obj->last_page)
	    )
	   )
	  {
	  	//delete the entry id in the table suffixed with the form id
			$delete = $this->EE->db->delete('exp_freeform_form_entries_'.$form_id, array('entry_id' => $entry_id)); 
		}	
	}
} //end ext class