<?php namespace Pauldro\Twig;
// Twig
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\CoreExtension;
use Twig\Extension\DebugExtension;
use Twig\TwigFilter, Twig\TwigFunction;
// Pauldro
use Pauldro\ProcessWire\AbstractStaticPwClass;
use Pauldro\Twig\Html;
// App
use App\Css;


/**
 * TwigDecorator
 * Adds Globals, Filters, Extensions to Twig Environment
 */
class TwigDecorator extends AbstractStaticPwClass {
	/**
	 * Add Pieces to Twig Environment
	 * @param  Environment      $twig
	 * @param  FilesystemLoader $loader
	 * @return bool
	 */
	public static function decorate(Environment $twig, FilesystemLoader $loader) {
		self::addExtensions($twig, $loader);
		self::addFilters($twig);
		self::addGlobals($twig, $loader);
		self::addFunctions($twig);
		self::setExtensionConfigs($twig);
		return true;
	}

/* =============================================================
	Extensions
============================================================= */
	/**
	 * Add Extensions
	 * @param  Environment $twig
	 * @return void
	 */
	private static function addExtensions(Environment $twig) {
		if (self::pw('config')->debug) {
			$twig->addExtension(new DebugExtension());
		}
	}

	/**
	 * Set Extensions' Config
	 * @param  Environment $twig
	 * @return void
	 */
	private static function setExtensionConfigs(Environment $twig) {
		$twig->getExtension(CoreExtension::class)->setNumberFormat(2, '.', '');	
	}

/* =============================================================
	Globals
============================================================= */
	/**
	 * Add Twig Global Variables
	 * @param  Environment      $twig
	 * @param  FilesystemLoader $loader
	 * @return void
	 */ 
	private static function addGlobals(Environment $twig, FilesystemLoader $loader) {
		$twig->addGlobal('TWIGLOADER', $loader);
		$twig->addGlobal('CSS', Css\Classes::instance());
		self::addGlobalsPw($twig);
	}

	/**
	 * Add Twig Globals From ProcessWire
	 * @param  Environment $twig
	 * @return void
	 */
	private static function addGlobalsPw(Environment $twig) {
		$pw = self::pw('config');

		foreach (['page', 'pages', 'config', 'user', 'languages', 'sanitizer', 'session', 'input', 'browseragent', 'urls'] as $variable) {
			$twig->addGlobal($variable, $pw->wire($variable));
		};
		$twig->addGlobal('homepage', $pw->wire('pages')->get('/'));
		$twig->addGlobal('site', $pw->wire('pages')->get('template=site-config'));
		$twig->addGlobal('APP', $pw->wire('modules')->get('App'));
	}

/* =============================================================
	Functions
============================================================= */
	/**
	 * Add Twig functions
	 * @param Environment $twig
	 * @return void
	 */
	private static function addFunctions(Environment $twig) {
		self::addFunctionsTwigObject($twig);
	}

	/**
	 * Add Functions to Return Lib\Twig\Html Objects
	 * @param  Environment $twig
	 * @return void
	 */
	private static function addFunctionsTwigObject(Environment $twig) {
		$function = new TwigFunction('twiginput', function (array $array) {
			$input = new Html\Input();
			$input->setFromArray($array);
			return $input;
		});
		$twig->addFunction($function);

		$function = new TwigFunction('twiginputgroup', function (array $array) {
			$input = new Html\InputGroup();
			$input->setFromArray($array);
			return $input;
		});
		$twig->addFunction($function);

		$function = new TwigFunction('twiginputgroupspan', function (array $array) {
			$input = new Html\InputGroupSpan();
			$input->setFromArray($array);
			return $input;
		});
		$twig->addFunction($function);

		$function = new TwigFunction('twigbutton', function (array $array) {
			$input = new Html\Button();
			$input->setFromArray($array);
			return $input;
		});
		$twig->addFunction($function);

		$function = new TwigFunction('twiglink', function (array $array) {
			$input = new Html\Link();
			$input->setFromArray($array);
			return $input;
		});
		$twig->addFunction($function);

		$function = new TwigFunction('twigtextarea', function (array $array) {
			$input = new Html\Textarea();
			$input->setFromArray($array);
			return $input;
		});
		$twig->addFunction($function);

		$function = new TwigFunction('twigselectsimple', function (array $array) {
			$input = new Html\SelectSimple();
			$input->setFromArray($array);
			return $input;
		});
		$twig->addFunction($function);

		$function = new TwigFunction('twigdatepicker', function (array $array) {
			$input = new Html\DatePicker();
			$input->setFromArray($array);
			return $input;
		});
		$twig->addFunction($function);
	}

/* =============================================================
	Filters
============================================================= */
	/**
	 * Add Filters
	 * @param  Environment $twig
	 * @return void
	 */
	private static function addFilters(Environment $twig) {
		self::addFiltersNumeric($twig);
		self::addFiltersString($twig);
	}

	/**
	 * Add TWig Filters for Numbers
	 * @param  Environment $twig
	 * @return void
	 */
	private static function addFiltersNumeric(Environment $twig) {
		$filter = new TwigFilter('currency', function ($money) {
			$money = floatval($money);
			return number_format($money, 2, '.', ",");
		});
		$twig->addFilter($filter);
	}

	/**
	 * Add Twig filters for Strings
	 * @param  Environment $twig
	 * @return void
	 */
	private static function addFiltersString(Environment $twig) {
		$filter = new TwigFilter('stripslashes', function ($str) {
			return stripslashes($str);
		});
		$twig->addFilter($filter);

		$yesno = new TwigFilter('yesorno', function ($trueorfalse) {
			return ($trueorfalse === true || strtoupper($trueorfalse) == 'Y') ? 'yes' : 'no';
		});
		$twig->addFilter($yesno);

		$convertdate = new TwigFilter('convertdate', function ($date, $format = 'm/d/Y') {
			$date = date($format, strtotime($date));
			return $date == '11/30/-0001' ? '' : $date;
		});
		$twig->addFilter($convertdate);

		$filter = new TwigFilter('strpad', [self::class, 'strpad'], ['is_safe' => ['html']]);
		$twig->addFilter($filter);
	}

/* =============================================================
	Supplemental
============================================================= */
	/**
	 * Twig wrapper for str_pad to Pad a string to a fixed length.
	 * example usage in template:
	 *
	 * {{ page.title|strpad(50,'-', 'left') }}
	 *
	 * @param $input
	 * @param $padlength
	 * @param string $padstring
	 * @param mixed $padtype
	 *
	 * @return string
	 */
	public static function strpad($input, $padlength, $padstring='', $padtype =  'right' ) {
		if ($padstring == '') {
			return $input;
		}

		if (is_string($padtype)) {
			switch ($padtype) {
				case 'left':
					$padtype = STR_PAD_LEFT;
					break;
				case 'both':
					$padtype = STR_PAD_BOTH;
					break;
				case 'right':
				default:
					$padtype = STR_PAD_RIGHT;
			}

		}
		return str_pad($input, $padlength, $padstring, $padtype);
	}
}