</main>
<footer>
  <div>
    &copy; {$project_name}
    &mdash;
    {if !empty($current_user)}
      {$current_user->login} - <a href="{$host_name}/user/logout/">Выход</a>
    {else}
      <a href="{$host_name}/user/">Logout</a>
    {/if}
  </div>
</footer>
</body>
</html>