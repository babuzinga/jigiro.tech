<li><a href="{$host_name}"{if !empty($current_controller) && $current_controller eq index} class="active"{/if}>Главная</a></li>
{if !empty($current_user)}
  <li><a href="{$host_name}/saved/"{if !empty($current_controller) && $current_controller eq saved} class="active"{/if}>Сохраненное</a></li>
{/if}