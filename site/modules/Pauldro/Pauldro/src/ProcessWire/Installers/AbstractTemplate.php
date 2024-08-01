<?php namespace Pauldro\ProcessWire\Installers;
// ProcessWire
use ProcessWire\Config;
use ProcessWire\Fieldgroup;
use ProcessWire\Template;

/**
 * AbstractTemplate
 * Template class for installing ProcessWire Templates
 * 
 * @static ProcessWire $pw ProcesWire Instance
 */
abstract class AbstractTemplate extends AbstractStaticPwInstaller {
	const NAME  = '';
	const LABEL = '';
	const FIELDS = ['title'];
	const ALLOW_CHILDREN    = false;
	const ALLOW_PAGINATION  = false;
	const ALLOW_URLSEGMENTS = false;
	const IS_SINGLE_USE     = false;
	const ALLOWED_PARENT_TEMPLATES = [];
	const ALLOWED_CHILD_TEMPLATES  = [];
	// {"html":"text\/html","txt":"text\/plain","json":"application\/json","xml":"application\/xml"}
	const CONTENT_TYPE = 'html';

	/**
	 * Install Template
	 * @return bool
	 */
	protected static function _install() {
		if (static::installFieldgroup() === false) {
			return false;
		}
		$t = static::templateFromDatabase();
		if (empty($t)) {
			$t = static::newTemplate();
		}
		static::setTemplateProperties($t);
		return $t->save();
	}

	/**
	 * Install Fieldgroup
	 * @return bool
	 */
	public static function installFieldgroup() {
		$fg =  static::fieldgroupFromDatabase();
		if (empty($fg)) {
			$fg = static::newFieldgroup();
		}
		static::setFieldgroupfields($fg);
		return $fg->save();
	}

/* =============================================================
	Template Returns
============================================================= */
	/**
	 * Return Template from Database
	 * @return Template|null
	 */
	public static function templateFromDatabase() {
		return self::pw('templates')->get(static::NAME);
	}

	/**
	 * Return new Template
	 * @return Template
	 */
	public static function newTemplate() {
		$t = new Template();
		return $t;
	}

	/**
	 * Return if Template Exists
	 * @return bool
	 */
	public static function exists() {
		return empty(self::templateFromDatabase()) === false;
	}

/* =============================================================
	Template Properties
============================================================= */
	/**
	 * Set Properties of Template
	 * @param  Template $t
	 * @return bool
	 */
	protected static function setTemplateProperties(Template $t) {
		$t->name  = static::NAME;
		$t->label = static::LABEL;
		$t->fieldgroup = static::fieldgroupFromDatabase();
		static::setTemplatePropertiesUrl($t);
		static::setTemplatePropertiesFamily($t);
		static::setTemplatePropertiesContentType($t);
		return true;
	}

	/**
	 * Set Template Properties related to URL scheme
	 * @param  Template $t
	 * @return bool
	 */
	protected static function setTemplatePropertiesUrl(Template $t) {
		$t->allowPageNum     = intval(static::ALLOW_PAGINATION);
		$t->slashPageNum     = intval(static::ALLOW_PAGINATION);
		$t->urlSegments      = intval(static::ALLOW_URLSEGMENTS);
		$t->slashUrlSegments = 1;
		return true;
	}

	/**
	 * Set Template Properties related to family
	 * @param  Template $t
	 * @return bool
	 */
	protected static function setTemplatePropertiesFamily(Template $t) {
		$t->noParents = static::IS_SINGLE_USE ? -1 : 0;
		$t->parentTemplates(static::ALLOWED_PARENT_TEMPLATES);
		$t->noChildren = intval(static::ALLOW_CHILDREN === false);
		$t->childTemplates(static::ALLOWED_CHILD_TEMPLATES);
		return true;
	}

	/**
	 * Set Template Properties related to Content Type
	 * @param  Template $t
	 * @return bool
	 */
	protected static function setTemplatePropertiesContentType(Template $t) {
		if (static::CONTENT_TYPE == 'html') {
			return true;
		}
		
		/** @var Config */
		$config = self::pw('config');

		if (array_key_exists(static::CONTENT_TYPE, $config->contentTypes) === false) {
			return true;
		}
		$t->contentType = static::CONTENT_TYPE;
		return true;
	}

/* =============================================================
	Fieldgroups
============================================================= */
	/**
	 * Return Fieldgroup from Database
	 * @return Fieldgroup|null
	 */
	public static function fieldgroupFromDatabase() {
		return self::pw('fieldgroups')->get(static::NAME);
	}

	/**
	 * Return new fieldgroup
	 * @return Fieldgroup
	 */
	public static function newFieldgroup() {
		$f = new Fieldgroup();
		$f->name = static::NAME;
		return $f;
	}

	/**
	 * Set Fields for Fieldgroup
	 * @param  Fieldgroup $f
	 * @return bool
	 */
	protected static function setFieldgroupfields(Fieldgroup $f) {
		foreach ($f->find('name!=' . implode('|', static::FIELDS)) as $field) {
			$f->remove($field);
		}
	
		foreach (static::FIELDS as $fieldname) {
			$f->add($fieldname);
		}
		return true;
	}
}