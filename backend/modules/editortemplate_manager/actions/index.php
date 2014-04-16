<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the index-action (default), it will display the overview of Editor-Template  Manager posts
 *
 * @author Kemper & Schlomski GmbH <info@kemper-schlomski.de>
 */
class BackendEditortemplateManagerIndex extends BackendBaseActionIndex
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		parent::execute();
		$this -> model = new BackendEditortemplateManagerModel();
		if (FrontendTheme::getTheme() == "core") {
			$this -> themeName = NULL;
		} else {
			$this -> themeName = FrontendTheme::getTheme();
			$this -> themeModel = new BackendEditortemplateManagerModel($this -> themeName);
		}
		$this->loadDataGrid();
		$this->parse();
		$this->display();
	}

	/**
	 * Load the dataGrid
	 */
	protected function loadDataGrid()
	{	
		
		$source = new SpoonDatagridSourceArray($this -> model -> getIndex());
		$this->dataGrid = new BackendDataGrid($source);
		

		// check if this action is allowed
		if(BackendAuthentication::isAllowedAction('edit'))
		{
			$this->dataGrid->addColumn(
				'edit', null, BL::lbl('Edit'),
				BackendModel::createURLForAction('edit') . '&amp;id=[id]',
				BL::lbl('Edit')
			);
			$this->dataGrid->setColumnURL(
				'template_name', BackendModel::createURLForAction('edit') . '&amp;id=[id]'
			);
		}

		if ($this -> themeName != NULL) {
			$themeSource = new SpoonDatagridSourceArray($this -> themeModel -> getIndex());
			$this->themeDataGrid = new BackendDataGrid($themeSource);
			if(BackendAuthentication::isAllowedAction('edit')) {
				$this->themeDataGrid->addColumn(
					'edit', null, BL::lbl('Edit'),
					BackendModel::createURLForAction('edit') . '&amp;theme='.$this -> themeName.'&amp;id=[id]',
					BL::lbl('Edit')
				);
				$this->themeDataGrid->setColumnURL(
					'template_name', BackendModel::createURLForAction('edit') . '&amp;theme='.$this -> themeName.'&amp;id=[id]'
				);
			}
		}

	}

	/**
	 * Parse the page
	 */
	protected function parse()
	{
		// parse the dataGrid if there are results
		$this->tpl->assign('dataGrid', (string) $this->dataGrid->getContent());
		if ($this -> themeName != NULL) {	
			$this->tpl->assign('themeName',$this -> themeName);
			$this->tpl->assign('themeDataGrid',(string) $this->themeDataGrid->getContent());
		}
	}
}
