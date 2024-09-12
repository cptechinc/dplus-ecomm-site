<?php namespace App\Ecomm\Services;
// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// ProcessWire
use ProcessWire\WireData;
use ProcessWire\WireInputData;
// App
use App\Ecomm\Abstracts\Services\AbstractEcommCrudService;
use App\Util\Emails\Twig;

/**
 * ContactUs
 * Provides ContactUs Services
 * 
 */
class ContactUs extends AbstractEcommCrudService {
	const SESSION_NS = 'contact-us';

	protected static $instance;

/* =============================================================
	Constructors / Inits
============================================================= */

/* =============================================================
	Public
============================================================= */

/* =============================================================
	CRUD Reads
============================================================= */
	

/* =============================================================
	CRUD Processing
============================================================= */
	/**
	 * Process Request
	 * @param  WireInputData $input
	 * @return bool
	 */
	protected function processInput(WireInputData $input) {
		switch ($input->text('action')) {
			case 'contact-us':
				return $this->processContactUs($input);
		}
	}

	/**
	 * Parse Data, Send Email
	 * @param  WireInputData $input
	 * @return bool
	 */
	private function processContactUs(WireInputData $input) {
		$data = new WireData();
		$data->firstname = $input->text('firstname');
		$data->lastname  = $input->text('lastname');
		$data->email     = $input->email('email');
		$data->phonenumber = $input->text('phone');
		$data->subject   = $input->text('subject');
		$data->message   = $input->textarea('message');
		
		if (empty($data->email) || empty($data->firstname)) {
			return false;
		}

		if ($this->emailContactInfo($data) === false) {
			return false;
		}
		$this->setSessionVar('emailed', true);
		return true;
	}

	/**
	 * Return If email was sent
	 * @param  WireData $data
	 * @return bool
	 */
	private function emailContactInfo(WireData $data) {
		$html = $this->renderEmailHtml($data);
		
		//Create an instance; passing `true` enables exceptions
		$mail = new PHPMailer(true);
		$mail->isSMTP(); 
		$mail->setFrom('no-reply@example.com', 'Mailer');
		$mail->addAddress('paul@cptechinc.com', 'Paul');     //Add a recipient
		$mail->addReplyTo('no-reply@example.com', 'Information');

		$mail->isHTML(true);                                  //Set email format to HTML
		$mail->Subject = 'Contact Us Submission';
		$mail->Body    = $html;
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		
		try {
			$mail->send();
		} catch (Exception $e) {
			return false;
		}
		return true;
	}

	private function renderEmailHtml(WireData $data) {
		$data->siteUrl = $this->pages->get('/')->httpUrl;
		$data->siteName = $this->pages->get('/')->title;

		$twigWrapper = new Twig();
		$twigWrapper->initTwig();
		return $twigWrapper->twig->render('contact-us-submission/email.twig', ['data' => $data]);
	}

/* =============================================================
	Dplus Requests
============================================================= */
	
}