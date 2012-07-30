<?php 
/**
 * Part of the Registry bundle for Laravel.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Registry
 * @version    1.0
 * @author     Cinkmedia Limited
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2012, Cinkmedia Limited
 * @link       http://cinkmedia.com
 */

namespace Registry;

use Config;
use DB;
class Registry {

	/**
	 * DB registry values
	 *
     */
	protected static $registry;
	
	/**
	 * Override registry values
	 *
     */
	protected static $override;
	
	/**
	 * The database table to store the registry in
	 *
     */	
	protected static $regTable = 'registry';
	
	/**
	 * Constructor
	 *
	 * @access	public
	 */
	public static function _init()
	{
		// get the table name from config
		$tableName = strtolower(Config::get('registry::registry.table'));
		if(!empty($tableName))
		{
			// set table name to the one in config
			self::$regTable	= $tableName;
		}
		
		self::_registry_read();
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Delete registry key
	 *
	 * @access	public
	 * @param	string	name
	 * @return	bool	
	 */
	public static function deleteItem($name) 
	{
		// unset the overriden value
		unset(self::$override[$name]);
		// unset the registry value
		unset(self::$registry[$name]);
		// delete from database
		return DB::table(self::$regTable)->where('key', '=', $name)->delete();
	}
	
	// --------------------------------------------------------------------

	/**
	 * Get value from registry
	 *
	 * @access	public
	 * @param	string	name
	 * @return	string
	 */
	public static function getItem($name) 
	{
		// get registry value
		return isset(self::$override[$name]) ? self::$override[$name] : (isset(self::$registry[$name]) ? self::$registry[$name] : null);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Reset registry value to database stored value
	 *
	 * @access	public
	 * @param	string	name
	 * @return	void
	 */
	public static function resetItem($name) 
	{
		// unset the overriden value
		unset(self::$override[$name]);
	}

	// --------------------------------------------------------------------
	
	/**
	 * Save all overriden keys to the database
	 *
	 * @access	public
	 * @return	void
	 */
	public static function save() 
	{
		// save each overriden item
		foreach(self::$override as $key => $value)
		{
			self::setItem($key, $value, true);
		}
	}

	// --------------------------------------------------------------------
    
	/**
	 * Set the registry item
	 *
	 * @access	public
	 * @param	string	name
	 * @param	string	value
	 * @param	bool	save
	 * @return	void
	 */
	public static function setItem($name, $value, $save = false) 
	{
		if($save === true)
		{
			// delete any overriden value
			unset(self::$override[$name]);
			// update the registry
			self::$registry[$name] = $value;
			// check if the value already exists in the DB
			$regs = DB::table(self::$regTable)
				->where('key', '=', $name)
				->get(self::$regTable.'.*');
			if(!empty($regs))
			{
				// update the key's value
				DB::table(self::$regTable)
					->where('key', '=', $name)
					->update(array('value' => $value));
			}else{
				// insert the key and value
				DB::table(self::$regTable)->insert(array('key' => $name, 'value' => $value));
			}
		}else{
			// not saving, just override the value
			self::$override[$name] = $value;
		}
		
	}

	
	
	// --------------------------------------------------------------------

	/**
	 * Load registry from database
	 *
	 * @return void
	 */
	private static function _registry_read()
	{
		$regs = DB::table(self::$regTable)
				->get(self::$regTable.'.*');
		foreach($regs as $reg)
		{
			self::$registry[$reg->key] = $reg->value;
		}
	}	
}