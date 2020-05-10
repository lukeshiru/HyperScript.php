<?php
require_once "./HyperScript.php";

use function HyperScript\associativeArrayExtract;
use function HyperScript\associativeArrayMap;
use function HyperScript\createElement as h;
use function HyperScript\defaultValue;

$SecureAnchor = fn ($props) => h("a", array_merge(["rel" => "noopener noreferrer"], $props));

$LinkList = function ($props) use ($SecureAnchor) {
	[$propsWithoutLinks, $links] = associativeArrayExtract($props, 'links');

	return h(
		"ul",
		$propsWithoutLinks,
		...(associativeArrayMap(
			@defaultValue($links, []),
			fn ($name, $link) => h("li", h($SecureAnchor, ["href" => $link], $name))
		))
	);
};

echo h($LinkList, [
	"links" => [
		"Twitter" => "https://lshi.ru/tw",
		"Facebook" => "https://lshi.ru/fb",
		"Instagram" => "https://lshi.ru/ig"
	]
]);
