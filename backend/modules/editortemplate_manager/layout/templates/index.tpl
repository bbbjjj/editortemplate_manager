{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

{option:themeName}
<div class="pageTitle">
	<h2>
		{$lblEditortemplateManager|ucfirst} - Theme Templates - {$themeName}
	</h2>
	<div class="buttonHolderRight">
		<a href="{$var|geturl:'add'}&amp;theme={$themeName}" class="button icon iconAdd" title="{$lblAddEditortemplateManager|ucfirst}">
			<span>{$lblAddEditortemplateManager|ucfirst}</span>
		</a>
	</div>
</div>

{option:themeDataGrid}
	<div class="dataGridHolder">
		{$themeDataGrid}
	</div>
{/option:themeDataGrid}

{option:!themeDataGrid}
	{$msgNoItems}
{/option:!themeDataGrid}
{/option:themeName}
<div class="pageTitle">
	<h2>
		{$lblEditortemplateManager|ucfirst} - Globale Templates
	</h2>
	<div class="buttonHolderRight">
		<a href="{$var|geturl:'add'}" class="button icon iconAdd" title="{$lblAddEditortemplateManager|ucfirst}">
			<span>{$lblAddEditortemplateManager|ucfirst}</span>
		</a>
	</div>
</div>

{option:dataGrid}
	<div class="dataGridHolder">
		{$dataGrid}
	</div>
{/option:dataGrid}

{option:!dataGrid}
	{$msgNoItems}
{/option:!dataGrid}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}
