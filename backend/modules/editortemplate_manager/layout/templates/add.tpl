{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblEditortemplateManager|ucfirst}: {$lblAdd}</h2>
</div>

{form:add}
	<label for="templateName">{$lblTemplateName|ucfirst}</label>
	{$txtTemplateName} {$txtTemplateNameError}

	<div id="pageUrl">
		<div class="oneLiner">
			{option:detailURL}<p><span><a href="{$detailURL}">{$detailURL}/<span id="generatedUrl"></span></a></span></p>{/option:detailURL}
		</div>
	</div>


	<div class="tabs">
		<ul>
			<li><a href="#tabContent">{$lblContent|ucfirst}</a></li>
		</ul>

		<div id="tabContent">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td id="leftColumn">

						<div class="box">
							<div class="heading">
								<h3>
									<label for="templateDescription">{$lblTemplateDescription|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
								</h3>
							</div>
							<div class="options">
								{$txtTemplateDescription} {$txtTemplateDescriptionError}
							</div>
						</div>

						<div class="box">
							<div class="heading">
								<h3>
									<label for="templateContent">{$lblTemplateContent|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
								</h3>
							</div>
							<div class="optionsRTE">
								{$txtTemplateContent} {$txtTemplateError}
							</div>
						</div>


					</td>

					<td id="sidebar">

							<div class="box">
								<div class="heading">
									<h3>
										<label for="templateImage">{$lblTemplateImage|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
									</h3>
								</div>
								<div class="options">
									{$fileTemplateImage} {$fileTemplateImageError}
								</div>
							</div>


					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="fullwidthOptions">
		<div class="buttonHolderRight">
			<input id="addButton" class="inputButton button mainButton" type="submit" name="add" value="{$lblPublish|ucfirst}" />
		</div>
	</div>
{/form:add}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}
