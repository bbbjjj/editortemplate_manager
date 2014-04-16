/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * Interaction for the Editor-Template  Manager module
 *
 * @author Kemper & Schlomski GmbH <info@kemper-schlomski.de>
 */
jsBackend.editortemplate_manager =
{
	// constructor
	init: function()
	{
		// do meta
		if($('#templateName').length > 0) $('#templateName').doMeta();
	}
}

$(jsBackend.editortemplate_manager.init);
