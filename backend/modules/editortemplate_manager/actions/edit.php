<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the edit-action, it will display a form with the item data to edit
 *
 * @author Kemper & Schlomski GmbH <info@kemper-schlomski.de>
 */
class BackendEditortemplateManagerEdit extends BackendBaseActionEdit
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		parent::execute();
		$this -> theme = $this -> getParameter("theme","string",NULL);
		$this -> model = new BackendEditortemplateManagerModel($this -> theme);
		$this->loadData();
		$this->loadForm();
		$this->validateForm();

		$this->parse();
		$this->display();
	}

	/**
	 * Load the item data
	 */
	protected function loadData()
	{
		$this->id = $this->getParameter('id', 'int', null);
		if($this->id == null || !$this -> model -> exists($this->id))
		{
			$this->redirect(
				BackendModel::createURLForAction('index') . '&error=non-existing'
			);
		}

		$this->record = $this -> model -> get($this->id);
	}

	/**
	 * Load the form
	 */
	protected function loadForm()
	{
		// create form
		$this->frm = new BackendForm('edit');

		$this->frm->addText('template_name' ,$this->record['template_name'], null, 'inputText title', 'inputTextError title');
		$this->frm->addText('template_description', $this->record['template_description']);
		$this->frm->addImage('template_image');
		$this->frm->addEditor('template_content', $this->record['template_content']);


	}

	/**
	 * Parse the page
	 */
	protected function parse()
	{
		parent::parse();

		// get url
		$url = BackendModel::getURLForBlock($this->URL->getModule(), 'detail');
		$url404 = BackendModel::getURL(404);

		// parse additional variables
		if($url404 != $url) $this->tpl->assign('detailURL', SITE_URL . $url);

		$this->tpl->assign('item', $this->record);
		if ($this -> theme != NULL) {
			$this->tpl->assign('themeName', $this->theme);
		}
	}

	/**
	 * Validate the form
	 */
	protected function validateForm()
	{
		if($this->frm->isSubmitted())
		{
			$this->frm->cleanupFields();

			// validation
			$fields = $this->frm->getFields();

			$fields['template_name']->isFilled(BL::err('FieldIsRequired'));
			$fields['template_description']->isFilled(BL::err('FieldIsRequired'));
			if($fields['template_image']->isFilled())
			{
				$fields['template_image']->isAllowedExtension(array('jpg', 'png', 'gif', 'jpeg'), BL::err('JPGGIFAndPNGOnly'));
				$fields['template_image']->isAllowedMimeType(array('image/jpg', 'image/png', 'image/gif', 'image/jpeg'), BL::err('JPGGIFAndPNGOnly'));
			}
			$fields['template_content']->isFilled(BL::err('FieldIsRequired'));
			
			
			if($this->frm->isCorrect())
			{
				$item['id'] = $this->id;
				$item['language'] = BL::getWorkingLanguage();

				$item['template_name'] = $fields['template_name']->getValue();
				$item['template_description'] = $fields['template_description']->getValue();

				// image provided?
				if($fields['template_image']->isFilled())
				{
					// build the image name
					$item['template_image'] = $fields['template_image']->getFileName();
					
					// upload the image & move image
					$fields['template_image']->moveFile($this -> model -> getImagePath() . $item['template_image']);
				}
				$item['template_content'] = $fields['template_content']->getValue();


				$this -> model -> update($item);
				$item['id'] = $this->id;

				BackendModel::triggerEvent(
					$this->getModule(), 'after_edit', $item
				);
				$this->redirect(
					BackendModel::createURLForAction('index') . '&report=edited&highlight=row-' . $item['id']
				);
			}
		}
	}
}
