<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the add-action, it will display a form to create a new item
 *
 * @author Kemper & Schlomski GmbH <info@kemper-schlomski.de>
 */
class BackendEditortemplateManagerAdd extends BackendBaseActionAdd
{
	/**
	 * Execute the actions
	 */
	public function execute()
	{
		parent::execute();

		//Create Model
		$this -> theme = $this -> getParameter("theme","string",NULL);
		$this -> model = new BackendEditortemplateManagerModel($this -> theme);
		
		$this->loadForm();
		$this->validateForm();

		$this->parse();
		$this->display();
	}

	/**
	 * Load the form
	 */
	protected function loadForm()
	{
		$this->frm = new BackendForm('add');

		$this->frm->addText('template_name', null, null, 'inputText title', 'inputTextError title');
		$this->frm->addText('template_description');
		$this->frm->addImage('template_image');
		$this->frm->addEditor('template_content');

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
			else $fields['template_image']->addError(BL::err('FieldIsRequired'));
			$fields['template_content']->isFilled(BL::err('FieldIsRequired'));

			if($this->frm->isCorrect())
			{
				// build the item
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
				

				// insert it
				$item['id'] = $this -> model -> insert($item);
				
				BackendModel::triggerEvent(
					$this->getModule(), 'after_add', $item
				);
				$this->redirect(
					BackendModel::createURLForAction('index') . '&report=added&highlight=row-' . $item['id']
				);
			}
		}
	}
}
