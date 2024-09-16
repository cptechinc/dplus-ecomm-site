<?php namespace App\Site\Pages\Hooks;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\Page;
use ProcessWire\Pages;
// App
use App\Pw\Hooks\AbstractStaticHooksAdder;

/**
 * BlogP
 * Add hooks for blog page
 */
class Blog extends AbstractStaticHooksAdder {
	const TEMPLATE = 'blog';

/* =============================================================
	Hooks
============================================================= */	
	/**
	 * Add hooks
	 * @return void
	 */
	public static function addHooks() {
		$m = self::pwModuleApp();
		$tpl = self::TEMPLATE;

		$m->addHook("Page(template=$tpl)::recent", function(HookEvent $event) {
			/** @var Page */
			$page = $event->object;
			/** @var Pages */
			$pages = self::pw('pages');
			$limit = $event->arguments(0) ? $event->arguments(0) : 3;
			$event->return = $pages->find("template=blog-post,limit=$limit,sort=-created");
		});
	}
}