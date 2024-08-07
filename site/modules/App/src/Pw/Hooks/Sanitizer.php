<?php namespace App\Pw\Hooks;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\Sanitizer as PwSanitizer;

/**
 * Sanitizer
 * Adds Hooks for Sanitizer
 */
class Sanitizer extends AbstractStaticHooksAdder {
/* =============================================================
	Hooks
============================================================= */
	public static function addHooks() {
		$sanitizer = self::pwSanitizer();
		
		$sanitizer->addHook('yn', function(HookEvent $event) {
			$value = strtoupper($event->arguments(0));
			$event->return = $value == 'Y' ? 'Y' : 'N';
		});

		$sanitizer->addHook('ynbool', function(HookEvent $event) {
			$value = strtoupper($event->arguments(0));
			$event->return = $value == 'Y';
		});

		$sanitizer->addHook('stringLength', function(HookEvent $event) {
			$sanitizer = $event->object;
			$value  = $event->arguments(0);
			$length = $event->arguments(1);
			$event->return = substr($sanitizer->string($value), 0, $length);
		});

		$sanitizer->addHook('sortColumnForPw', function(HookEvent $event) {
			$sanitizer = $event->object;
			$col  = $event->arguments(0);
			$dir  = $event->arguments(1);

			if ($dir == 'DESC') {
				$event->return = '-' . $col;
				return true;
			}
			$event->return = $col;
		});
	}

/* =============================================================
	Supplemental
============================================================= */
	/**
	 * Return Sanitizer
	 * @return PwSanitizer
	 */
	private static function pwSanitizer() {
		return self::pw('sanitizer');
	}
}
