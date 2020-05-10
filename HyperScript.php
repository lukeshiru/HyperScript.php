<?php

namespace HyperScript {
	/**
	 * Returns value if isset and is not null.
	 *
	 * @param mixed $value Value to check.
	 * @param mixed $default Default value.
	 * @returns $value or $default depending on if $value is defined an not null.
	 */
	function defaultValue($value, $default = null)
	{
		return isset($value) && !is_null($value) ? $value : $default;
	}

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
			fn ($key, $value) => $callback($key, $value),
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
		return [
			array_filter(
				$array,
				fn ($array_key) => $array_key != $key,
				ARRAY_FILTER_USE_KEY
			),
			@defaultValue($array[$key])
		];
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
		$selfClosingTagsNames = [
			'area',
			'base',
			'br',
			'col',
			'embed',
			'hr',
			'img',
			'input',
			'link',
			'meta',
			'param',
			'source',
			'track',
			'wbr'
		];
		$isSelfClosing = in_array($tagName, $selfClosingTagsNames);
		[$propsWithoutChildren, $children] = associativeArrayExtract($props, 'children');
		$tagNameWithProps = $tagName . (count($propsWithoutChildren) == 0 ? '' : ' ') . join(
			' ',
			associativeArrayMap(
				$propsWithoutChildren,
				fn ($key, $prop) => "$key=\"$prop\""
			)
		);
		$childrenString = join(@defaultValue($children, []));

		return $isSelfClosing
			? "<$tagNameWithProps/>"
			: "<$tagNameWithProps>$childrenString</$tagName>";
	}

	/**
	 * HyperScript like function to create elements.
	 *
	 * @param string|callable $type Type of element (tagName or function).
	 * @param string|array $propsOrChild Associative array with props of element
	 * `["propName" => propValue]`, or first child when no props.
	 * @param string[] ...$children Children of element.
	 * @return string
	 */
	function createElement($type, $propsOrChild = [], ...$children)
	{
		$propsIsChild = is_string($propsOrChild);
		$props = $propsIsChild ? [] : @defaultValue($propsOrChild, []);
		$propsWithChildren = array_merge(
			$props,
			[
				"children" => ($propsIsChild || count($children) > 0)
					? $propsIsChild ? array_merge([$propsOrChild], $children) : $children
					: (@defaultValue($props["children"], []))
			]
		);
		$output = is_string($type)
			? createTag($type, $propsWithChildren)
			: $type($propsWithChildren);

		return is_array($output) ? join($output) : $output;
	}
}
