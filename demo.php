<?php
include "./HyperScript.php";

use function HyperScript\associativeArrayExtract;
use function HyperScript\associativeArrayMap;
use function HyperScript\createElement as h;

$SecureAnchor = function ($props) {
	return h("a", array_merge(["rel" => "noopener noreferrer"], $props));
};

$LinkList = function ($props) use ($SecureAnchor) {
	[$propsWithoutLinks, $links] = associativeArrayExtract($props, 'links');

	return h(
		"ul",
		$propsWithoutLinks,
		...(is_null($links) ? [] : associativeArrayMap($links, function ($name, $link) use ($SecureAnchor) {
			return h("li", null, h($SecureAnchor, ["href" => $link], $name));
		}))
	);
};

echo h($LinkList, [
	"links" => [
		"Twitter" => "https://lshi.ru/tw",
		"Facebook" => "https://lshi.ru/fb",
		"Instagram" => "https://lshi.ru/ig"
	]
]);
