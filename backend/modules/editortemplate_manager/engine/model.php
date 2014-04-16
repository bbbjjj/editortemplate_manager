<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * In this file we store all generic functions that we will be using in the Editor-Template  Manager module
 *
 * @author Kemper & Schlomski GmbH <info@kemper-schlomski.de>
 */

class BackendEditortemplateManagerModel
{
	
	private $webPath = "/backend/core/layout/editor_templates/";
	private $templatePath;
	private $templateJS = "templates.js";
	private $templateImages = "images/";
	
	private $data;

	public function __construct($theme = NULL) {
		if ($theme != NULL) {
			$this -> webPath = "/frontend/themes/".$theme."/core/layout/editor_templates/";
		}
		$this -> templatePath = $_SERVER['DOCUMENT_ROOT'].$this -> webPath;
		if (!file_exists($this -> templatePath . $this -> templateImages)) {
			mkdir($this -> templatePath . $this -> templateImages,0755,true);
		}
		if (file_exists($this -> templatePath . $this -> templateJS)) {
			$this -> data = json_decode(file_get_contents($this -> templatePath . $this -> templateJS),true);
		} else {
			$this -> data = array();
		}

	}
	

	private function save() {
		$this -> data = array_values($this -> data);
		file_put_contents($this -> templatePath . $this -> templateJS,json_encode($this -> data));
	}

	public function getIndex() {
		$index = array();
		for ($i=0; $i<count($this -> data); $i++) {
			$item = $this -> data[$i];
			$index[] = array(
				"id" => $i+1,
				"template_name" => $item["title"],
				"template_description" => $item["description"]
			);
		}
		return $index;
	}

	public function getImagePath() {
		return $this -> templatePath . $this -> templateImages;
	}
	/**
	 * Delete a certain item
	 *
	 * @param int $id
	 */
	public function delete($id)
	{	
		$id = (int)$id - 1;
		$item = $this -> data[$id];
		if (array_key_exists("file",$item)) {
			@unlink($this -> templatePath . $this -> templateImages . basename($item["image"]));
			@unlink($this -> templatePath . basename($item["file"]));
		}
		unset($this -> data[$id]);
		$this -> save();
	}


	/**
	 * Checks if a certain item exists
	 *
	 * @param int $id
	 * @return bool
	 */
	public function exists($id)
	{
		return (count($this -> data) >= (int)$id);
	}

	/**
	 * Fetches a certain item
	 *
	 * @param int $id
	 * @return array
	 */
	public function get($id)
	{
		$id = (int)$id - 1;
		$item = $this -> data[$id];
		if (array_key_exists("html",$item)) {
			$content = $item["html"];
		} else {
			$content = file_get_contents($this -> templatePath . basename($item['file']));
		}
		$record = array(
			"id" => $id + 1,
			"template_name" => $item["title"],
			"template_description" => $item["description"],
			"template_image" => $item["image"],
			"template_content" => $content
		);
		return $record;
	}

	
	/**
	 * Insert an item in the database
	 *
	 * @param array $item
	 * @return int
	 */
	public function insert(array $item)
	{
		$filename = preg_replace("/[_]+/","_",preg_replace("/[^a-z0-9_]/","_",strtolower($item['template_name']))."_".time().".html");
		file_put_contents($this -> templatePath . $filename, $item["template_content"]);
		$record = array(
			"title" => $item['template_name'],
			"description" => $item['template_description'],
			"image" => $this -> webPath . $this -> templateImages . $item["template_image"],
			"file" => $this -> webPath . $filename
		);
		$this -> data[] = $record;
		$this -> save();
		return count($this -> data);
	}	

	/**
	 * Updates an item
	 *
	 * @param array $item
	 */
	public function update(array $item)
	{
		$dataitem = $this -> data[$item["id"] - 1];
		if (array_key_exists('template_image',$item)) {
			@unlink($this -> templatePath . $this -> templateImages . basename($dataitem["image"]));
		} else {
			$item['template_image'] = basename($dataitem['image']);
		}
		@unlink($this -> templatePath . basename($dataitem["file"]));
		$filename = preg_replace("/[_]+/","_",preg_replace("/[^a-z0-9_]/","_",strtolower($item['template_name']))."_".time().".html");
		file_put_contents($this -> templatePath . $filename, $item["template_content"]);
		$record = array(
			"title" => $item['template_name'],
			"description" => $item['template_description'],
			"image" => $this -> webPath . $this -> templateImages . $item["template_image"],
			"file" => $this -> webPath . $filename
		);
		$this -> data[$item["id"] - 1] = $record;
		$this -> save();
		return $item['id'];
	}

}
