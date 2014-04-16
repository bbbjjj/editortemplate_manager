<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * Installer for the Editor-Template  Manager module
 *
 * @author Kemper & Schlomski GmbH <info@kemper-schlomski.de>
 */
class EditortemplateManagerInstaller extends ModuleInstaller
{
	public function install()
	{
		//assuming that if this directory not exists, the version is incompatible!
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/backend/core/layout/editor_templates')) {
			$this -> addWarning("This Module is out of date or you manually deleted /backend/core/layout/editor_templates");
			return;
		}
	
		// install the module in the database
		$this->addModule('editortemplate_manager');

		// install the locale, this is set here beceause we need the module for this
		$this->importLocale(dirname(__FILE__) . '/data/locale.xml');

		$this->setModuleRights(1, 'editortemplate_manager');

		$this->setActionRights(1, 'editortemplate_manager', 'index');
		$this->setActionRights(1, 'editortemplate_manager', 'add');
		$this->setActionRights(1, 'editortemplate_manager', 'edit');
		$this->setActionRights(1, 'editortemplate_manager', 'delete');

		// add extra's
		$subnameID = $this->insertExtra('editortemplate_manager', 'block', 'EditortemplateManager', null, null, 'N', 1000);
		$this->insertExtra('editortemplate_manager', 'block', 'EditortemplateManagerDetail', 'detail', null, 'N', 1001);

		$navigationModulesId = $this->setNavigation(null, 'Modules');
		$navigationclassnameId = $this->setNavigation(
			$navigationModulesId,
			'EditortemplateManager',
			'editortemplate_manager/index',
			array('editortemplate_manager/add','editortemplate_manager/edit')
		);
	}
}
