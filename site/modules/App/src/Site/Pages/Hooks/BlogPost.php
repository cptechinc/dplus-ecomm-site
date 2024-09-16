<?php namespace App\Site\Pages\Hooks;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\Page;
use ProcessWire\User;
use ProcessWire\Users;
// App
use App\Pw\Hooks\AbstractStaticHooksAdder;

/**
 * BlogPost
 * Add hooks for blog-post pages
 */
class BlogPost extends AbstractStaticHooksAdder {
	const TEMPLATE = 'blog-post';

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

		$m->addHookProperty("Page(template=$tpl)::writer", function(HookEvent $event) {
			/** @var Page */
			$page = $event->object;
			/** @var Users */
			$users = self::pw('users');
			/** @var User */
			$user = $users->get($page->created_users_id);
			$event->return = $user;
		});

	}
}