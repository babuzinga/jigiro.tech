<li><a href="{$host_name}"{if !empty($current_controller) && $current_controller eq index} class="active"{/if}>Главная</a></li>
{if !empty($current_user)}
  <li>
    <a 
      href="{$host_name}/services/"
      {if !empty($current_controller) && $current_controller eq service} class="active"{/if}
      >Сервисы</a>
  </li>

  <li>
    <a
      href="{$host_name}/files/saved/"
      {if !empty($current_controller) && $current_controller eq files} class="active"{/if}
      >Сохраненное</a>
  </li>
{/if}