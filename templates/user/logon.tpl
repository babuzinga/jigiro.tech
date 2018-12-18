{include file="layout/header.tpl"}

<section>
  <div class="content">
    <h1>Авторизация</h1>
    <br/>
    <form action="{$host_name}/user/logon" method="post">
      <label>
        Имя
        <input class="form-text" type="text" value="" name="login">
        {if !empty($error.login)}<div class="error">{$error.login}</div>{/if}
      </label>
      <label>
        Пароль
        <input class="form-text" type="password" value="" name="password">
        {if !empty($error.password)}<div class="error">{$error.password}</div>{/if}
      </label>
      <input name="submit" class="button" type="submit" value="Вход">
    </form>
  </div>
</section>

{include file="layout/footer.tpl"}