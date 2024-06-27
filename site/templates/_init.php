<?php
// Pauldro ProcessWire
use Pauldro\ProcessWire\FileHasher;
// Dplus
use Dplus\Database\Connectors as DplusDbConnectors;


/** @var ProcessWire\Config $config */
/** @var ProcessWire\WireInput $input */

$connectedDatax  = DplusDbConnectors\Dplus::instance()->connect();
$connectedDpluso = DplusDbConnectors\Dpluso::instance()->connect();

if ($connectedDatax === false || $connectedDpluso === false) {
	// TODO: handle bad db connections
}

include_once($modules->get('Mvc')->controllersPath().'vendor/autoload.php');


if ($input->get->offsetExists('action') === false && $page->template->name != 'json') {
	$fh = FileHasher::instance();

	$styles = $config->styles;
	$styles->append($fh->getHashUrl('vendor/bootstrap/css/bootstrap.min.css'));
	$styles->append($fh->getHashUrl('vendor/font-awesome/css/font-awesome.min.css'));
	$styles->append('https://fonts.googleapis.com/css?family=Roboto:300,400,700');
	$styles->append($fh->getHashUrl('vendor/bootstrap-select/css/bootstrap-select.min.css'));
	$styles->append($fh->getHashUrl('vendor/owl.carousel/assets/owl.carousel.css'));
	$styles->append($fh->getHashUrl('vendor/owl.carousel/assets/owl.theme.default.css'));
	$styles->append($fh->getHashUrl('styles/style.blue.css'));
	$styles->append($fh->getHashUrl('styles/custom.css'));

	$scripts = $config->scripts;
	$scripts->append($fh->getHashUrl('vendor/jquery/jquery.min.js'));
	$scripts->append($fh->getHashUrl('vendor/popper.js/umd/popper.min.js'));
	$scripts->append($fh->getHashUrl('vendor/bootstrap/js/bootstrap.min.js'));
	$scripts->append($fh->getHashUrl('vendor/jquery.cookie/jquery.cookie.js'));
	$scripts->append($fh->getHashUrl('vendor/waypoints/lib/jquery.waypoints.min.js'));
	$scripts->append($fh->getHashUrl('vendor/jquery.counterup/jquery.counterup.min.js'));
	$scripts->append($fh->getHashUrl('vendor/owl.carousel/owl.carousel.min.js'));
	$scripts->append($fh->getHashUrl('vendor/owl.carousel2.thumbs/owl.carousel2.thumbs.min.js'));
	$scripts->append($fh->getHashUrl('scripts/jquery.parallax-1.1.3.js'));
	$scripts->append($fh->getHashUrl('vendor/bootstrap-select/js/bootstrap-select.min.js'));
	$scripts->append($fh->getHashUrl('vendor/jquery.scrollto/jquery.scrollTo.min.js'));
	$scripts->append($fh->getHashUrl('scripts/front.js'));
	$scripts->append($fh->getHashUrl('scripts/custom.js'));

	/** @var ProcessWire\Twig */
	$MTWIG = $modules->get('Twig');
	$MTWIG->initTwig();
	$TWIG = $MTWIG->twig;

	$site    = $pages->get('template=site-config');
	$homepage = $pages->get('/');
}