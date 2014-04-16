<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the delete-action, it deletes an item
 *
 * @author Kemper & Schlomski GmbH <info@kemper-schlomski.de>
 */
class BackendEditortemplateManagerDelete extends BackendBaseActionDelete
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		$this->id = $this->getParameter('id', 'int');
		$this -> theme = $this -> getParameter("theme","string",NULL);
		$this -> model = new BackendEditortemplateManagerModel($this -> theme);
		// does the item exist
		if($this->id !== null && $this -> model -> exists($this->id))
		{
			parent::execute();
			$this->record = (array) $this -> model -> get($this->id);

			$this -> model -> delete($this->id);

			BackendModel::triggerEvent(
				$this->getModule(), 'after_delete',
				array('id' => $this->id)
			);

			$this->redirect(BackendModel::createURLForAction('index') . '&report=deleted&var=' . urlencode($this->record['title']));
		}
		else $this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
	}
}
