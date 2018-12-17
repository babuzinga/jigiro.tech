</main>
<footer>
  <div>
    &copy; {$project_name}
    &mdash;
    {if !empty($current_user)}
      {$current_user->login}
    {else}
      <a href="{$host_name}/user/">Login</a>
    {/if}
  </div>
</footer>
</body>
</html>