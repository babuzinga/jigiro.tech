<div class="content" data-item="{$subitem->id}" {if !empty($hidden)}style="display:none"{/if}>
  <div class="c-header">
    <span>Дата создания: {$subitem->date_created|date_format:'%d.%m.%Y %H:%M'}</span>
    {if !empty($subitem->date_editing)}<span>Дата редактировния: {$subitem->date_editing}</span>{/if}
    <span class="link">Редактировать</span>
    <span class="link">Удалить</span>
  </div>
  <div class="c-body">
    {$subitem->note}
  </div>
</div>