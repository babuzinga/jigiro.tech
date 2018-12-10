<div class="content">
  <div class="c-body">
    <h2>Добавить</h2>
    <form class="add-item">
      <label>Текст заметки:</label>
      <span class="error"></span>
      <textarea></textarea>
      <input
        type="button"
        class="button2"
        value="Добавить"
        onclick="addAjaxItems(this, 'note-items', '/note/ajax_add/')"
        >
    </form>
  </div>
</div>