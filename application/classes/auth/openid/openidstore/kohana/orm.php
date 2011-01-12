<?php

/**
 * This file supplies a Memcached store backend for OpenID servers and
 * consumers.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: See the COPYING file included in this distribution.
 *
 * @package OpenID
 * @author JanRain, Inc. <openid@janrain.com>
 * @copyright 2005-2008 Janrain, Inc.
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache
 */

/**
 * Require base class for creating a new interface.
 */
require_once 'Auth/OpenID.php';
require_once 'Auth/OpenID/Interface.php';
require_once 'Auth/OpenID/HMAC.php';
require_once 'Auth/OpenID/Nonce.php';

/**
 * This is a filesystem-based store for OpenID associations and
 * nonces.  This store should be safe for use in concurrent systems on
 * both windows and unix (excluding NFS filesystems).  There are a
 * couple race conditions in the system, but those failure cases have
 * been set up in such a way that the worst-case behavior is someone
 * having to try to log in a second time.
 *
 * Most of the methods of this class are implementation details.
 * People wishing to just use this store need only pay attention to
 * the constructor.
 *
 * @package OpenID
 */
class Auth_OpenID_OpenIDStore_Kohana_ORM extends Auth_OpenID_OpenIDStore {

	public function __construct()
	{

	}

	/**
	 * Store an association in the association directory.
	 */
	public function storeAssociation($server_url, $association)
	{
		$association = ORM::factory('openid_association');
		
		$association->server_url = $server_url;
		$association->handle = $association->handle;
		$association->secret = $association->secret;
		$association->issued = $association->issued;
		$association->lifetime = $association->lifetime;
		$association->assoc_type = $association->assoc_type;

		$association->save();
	}

	/**
	 * Retrieve an association. If no handle is specified, return the
	 * association with the most recent issue time.
	 *
	 */
	public function getAssociation($server_url, $handle = NULL)
	{
		if ($handle === NULL)
		{
			$association = ORM::factory('openid_association')
				->where('server_url', '=', $server_url)
				->order_by('id', 'DESC')
				->find_all();
		}
		else
		{
			$association = ORM::factory('openid_association')
				->where('server_url', '=', $server_url)
				->where('handle', '=', $handle)
				->order_by('id', 'DESC')
				->find();


			$associations = array();

			if ($association->loaded())
			{
				$associations[] = $association;
			}
		}
	}

	/**
	 * Remove an association if it exists. Do nothing if it does not.
	 *
	 * @return bool $success
	 */
	public function removeAssociation($server_url, $handle)
	{
		if ($handle === NULL)
		{
			$associations = ORM::factory('openid_association')
				->where('server_url', '=', $server_url)
				->find_all();
		}
		else
		{
			$associations = ORM::factory('openid_association')
				->where('server_url', '=', $server_url)
				->where('handle', '=', $handle)
				->find_all();
		}

		foreach ($associations as $association)
		{
			$association->delete();
		}

		if (count($associations) > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Return whether this nonce is present. As a side effect, mark it
	 * as no longer present.
	 *
	 * @return bool $present
	 */
	public function useNonce($server_url, $timestamp, $salt)
	{
		global $Auth_OpenID_SKEW; // Ewww

		if (abs($timestamp - time()) > $Auth_OpenID_SKEW)
			return false;

		$nonce = ORM::factory('openid_nonce');
		
		$nonce->server_url = $server_url;
		$nonce->timestamp = $timestamp;
		$nonce->salt = $salt;

		try
		{
			$nonce->save();
			return TRUE;
		}
		catch (Exception $e)
		{
			return FALSE;
		}
	}

	public function cleanupAssociations()
	{
		$associations = ORM::factory('openid_association')
			->where(DB::expr('issued + lifetime'), '<', time())
			->find_all();

		foreach ($associations as $association)
		{
			$association->delete();
		}

		return count($associations);
	}

	public function cleanupNonces()
	{
		global $Auth_OpenID_SKEW;

		$nonces = ORM::factory('openid_nonce')
			->where('timestamp', '<', time() - $Auth_OpenID_SKEW)
			->find_all();

		foreach ($nonces as $nonce)
		{
			$nonce->delete();
		}

		return count($nonces);
	}
}


