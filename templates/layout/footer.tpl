</main>
<footer>
  <div>
    &copy; {$project_name}
    &mdash;
    12-2018 / {$smarty.now|date_format:"%m-%Y"}
    &mdash;
    {if !empty($current_user)}
      {$current_user->login} - <a href="{$host_name}/user/logout/">Logout</a>
    {else}
      <a href="{$host_name}/user/logon/">Logon</a>
    {/if}
  </div>
</footer>
</body>
</html>