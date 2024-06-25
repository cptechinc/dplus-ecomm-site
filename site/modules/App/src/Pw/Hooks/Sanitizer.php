<?php namespace App\Pw\Hooks;
// ProcessWire
use ProcessWire\HookEvent;
use ProcessWire\WireData;
	// use ProcessWire\Sanitizer as PwSanitizer;

/**
 * Sanitizer
 * Adds Hooks for Sanitizer
 * 
 * @static self $instance
 */
class Sanitizer extends WireData {
	private static $instance;
	
/* =============================================================
	Constructors / Inits
============================================================= */
	/** @return self */
	public static function instance() {
		if (empty(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

/* =============================================================
	Hooks
============================================================= */
	public function addHooks() {
		$sanitizer = $this->sanitizer;
		
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
}
