<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Decoda;
use Decoda\Filter\AbstractFilter;
use Decoda\Hook\CodeHook;

/**
 * Provides tags for code block and variable elements.
 *
 * @package	mjohnson.decoda.filters
 */
class CodeFilter extends AbstractFilter {

	/**
	 * Supported tags.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(
		'code' => array(
			'template' => 'code',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH,
			'lineBreaks' => Decoda::NL_PRESERVE,
			'preserveTags' => true,
			'attributes' => array(
				'default' => self::ALPHA,
				'hl' => self::NUMERIC
			),
			'mapAttributes' => array(
				'default' => 'lang'
			),
			'stripContent' => true
		),
		'var' => array(
			'htmlTag' => 'code',
			'displayType' => Decoda::TYPE_INLINE,
			'allowedTypes' => Decoda::TYPE_INLINE
		)
	);

	/**
	 * Add any hook dependencies.
	 *
	 * @access public
	 * @param \Decoda\Decoda $decoda
	 * @return \Decoda\Filter\CodeFilter
	 */
	public function setupHooks(Decoda $decoda) {
		$decoda->addHook(new CodeHook());

		return $this;
	}

}