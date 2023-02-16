<?php

namespace OM4\Zapier\Payload\Item;

use OM4\Zapier\Payload\Base\Item;

defined( 'ABSPATH' ) || exit;

/**
 * Implement base structure requirements for Note Object.
 *
 * @deprecated 2.0.0
 */
class Note extends Item {

	/**
	 * Holds the type information for validate
	 *
	 * @var array
	 */
	protected static $property_types = array(
		'note'         => 'string',
		'date'         => 'string',
		'author'       => 'string',
		'author_email' => 'string',
	);

	/**
	 * Note/Comment.
	 *
	 * @var  string
	 */
	protected $note;

	/**
	 * Date of the Note/Comment.
	 *
	 * @var  string
	 */
	protected $date;

	/**
	 * Author of the Note/Comment.
	 *
	 * @var  string
	 */
	protected $author;

	/**
	 * Note/Comment Author's Email Address.
	 *
	 * @var  string
	 */
	protected $author_email;
}
