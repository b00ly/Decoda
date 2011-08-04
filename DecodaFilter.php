<?php

abstract class DecodaFilter extends DecodaAbstract {
	
	/**
	 * Type constants.
	 */
	const TYPE_NONE = 0;
	const TYPE_INLINE = 1;
	const TYPE_BLOCK = 2;
	const TYPE_BOTH = 3;

	/**
	 * Supported tags.
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_tags = array();
	
	/**
	 * Default tag configurations.
	 * 
	 * @access private
	 * @var array
	 */
	private $__defaults = array(
		'tag' => '',
		'template' => '',
		'type' => self::TYPE_BLOCK,
		'allowed' => self::TYPE_BOTH,
		'lineBreaks' => true,
		'selfClose' => false,
		'parent' => array(),
		'attributes' => array(),
		'map' => array(),
		'format' => '',
		'pattern' => ''
	);

	/**
	 * Return a tag if it exists, and merge with defaults.
	 * 
	 * @access public
	 * @param string $tag
	 * @return array
	 */
	public function tag($tag) {
		if (isset($this->_tags[$tag])) {
			return $this->_tags[$tag] + $this->__defaults;
		}
		
		return null;
	}
	
	/**
	 * Return all tags.
	 * 
	 * @access public
	 * @return array
	 */
	public function tags() {
		return $this->_tags;
	}

	/**
	 * Parse the node and its content into an HTML tag.
	 * 
	 * @access public
	 * @param array $tag
	 * @param string $content
	 * @return string 
	 */
	public function parse(array $tag, $content) {
		$setup = $this->tag($tag['tag']);
		
		if (empty($setup)) {
			return;
		}
		
		$attributes = $tag['attributes'];
		$xhtml = $this->_parser->config('xhtml');
		$attr = '';
		$tag = $setup['tag'];
		
		if (is_array($tag)) {
			$tag = $tag[$xhtml];
		}
		
		if ($setup['lineBreaks']) {
			$content = nl2br($content, $xhtml);
		}

		// If content doesn't match the pattern, don't wrap in a tag
		if (empty($attributes['default']) && !empty($setup['pattern'])) {
			if (!preg_match($setup['pattern'], $content)) {
				return $content;
			}
			
			$attributes['default'] = $content;
		}
		
		// Format attributes
		if (!empty($setup['format'])) {
			$attr = ' '. $setup['format'];

			foreach ($attributes as $key => $value) {
				$attr = str_replace('{'. $key .'}', $value, $attr);
			}
		} else {
			foreach ($attributes as $key => $value) {
				if (isset($setup['map'][$key])) {
					$key = $setup['map'][$key];
				}

				$attr .= ' '. $key .'="'. $value .'"';
			}
		}
		
		$parsed = '<'. $tag . $attr;
		
		if ($setup['selfClose']) {
			$parsed .= $xhtml ? '/>' : '>';
		} else {
			$parsed .= '>'. $content .'</'. $tag .'>';
		}
		
		return $parsed;
	}
	
}