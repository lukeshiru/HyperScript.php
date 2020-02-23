<?php

namespace HyperScript {
	/**
	 * Associative array map.
	 *
	 * @param array $array Array to map trough.
	 * @param callable $callback Callback with shape ($key, $value) => $newValue
	 * @return array
	 */
	function associativeArrayMap($array = [], $callback)
	{
		return array_map(
			function ($key, $value) use ($callback) {
				return $callback($key, $value);
			},
			array_keys($array),
			$array
		);
	}

	/**
	 * Extracts the given key from the array, returns a tuple [$arrayWithoutKey, $keyValue].
	 *
	 * @param array $array Target Array.
	 * @param string $key Key to extract.
	 * @return string[]|mixed[] Tuple [$arrayWithoutKey, $keyValue].
	 */
	function associativeArrayExtract($array = [], $key)
	{
		return [array_filter(
			$array,
			function ($array_key) use ($key) {
				return $array_key != $key;
			},
			ARRAY_FILTER_USE_KEY
		), isset($array[$key]) ? $array[$key] : null];
	}

	/**
	 * Create tag with given props.
	 *
	 * @param string $tagName TagName of HTML element.
	 * @param array $props HTML element props.
	 * @return string Generated HTML.
	 */
	function createTag($tagName, $props = [])
	{
		$selfClosingTagsNames = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'];
		$isSelfClosing = in_array($tagName, $selfClosingTagsNames);
		[$propsWithoutChildren, $children] = associativeArrayExtract($props, 'children');
		$tagNameWithProps = $tagName . (count($propsWithoutChildren) == 0 ? '' : ' ') . join(
			' ',
			associativeArrayMap(
				$propsWithoutChildren,
				function ($key, $prop) {
					return "$key=\"$prop\"";
				}
			)
		);
		$childrenString = join(is_null($children) ? [] : $children);

		return $isSelfClosing
			? "<$tagNameWithProps/>"
			: "<$tagNameWithProps>$childrenString</$tagName>";
	}

	/**
	 * HyperScript like function to create elements.
	 *
	 * @param string|callable $type Type of element (tagName or function).
	 * @param array $props Associative array with props of element ("propName" => propValue).
	 * @param string[] ...$children Children of element.
	 * @return string
	 */
	function createElement($type, $props = [], ...$children)
	{
		$noNullProps = is_null($props) ? [] : $props;
		$propsWithChildren = array_merge(
			$noNullProps,
			["children" => count($children) > 0 ? $children : (isset($noNullProps["children"]) ? $noNullProps["children"] : [])]
		);
		$output = is_string($type)
			? createTag($type, $propsWithChildren)
			: $type($propsWithChildren);
		return is_array($output) ? join($output) : $output;
	}
}
