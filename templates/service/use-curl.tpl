{include file="layout/header.tpl"}

<section>
  <div class="content">
    <p>
      Отправка curl-запросов на указанный адрес с передачей get или post параметров
    </p>

    <br/>

    <form onsubmit="sendRequest(); return false;">
      <input 
        type="text" 
        placeholder="Адрес запроса"
        id="url_request"
        class="form-text"
        value=""
        onclick="this.select();"
        >
      <div class="data-block radio">
        <span class="title">Тип передачи данных :</span>
        <input 
          type="radio" 
          id="rb1"
          name="method_type" 
          value="get_type"
          checked="checked"
          >
        <label for="rb1">GET</label>
        <input 
          type="radio" 
          id="rb2"
          name="method_type" 
          value="post_type"
          >
        <label for="rb2">POST</label>
      </div>
      <div class="data-block">
        <span class="title">Переменные :</span>

        <table id="variable-table">
        <tbody>
        <tr>
          <td>1.</td>
          <td>
            <input 
              type="text" 
              placeholder="Имя"
              class="form-text"
              value=""
              name="name[]"
            >
          </td>
          <td>&nbsp;&nbsp;=&nbsp;&nbsp;</td>
          <td>
            <input 
              type="text" 
              placeholder="Значение"
              class="form-text"
              value=""
              name="value[]"
            >
          </td>
          <td></td>
        </tr>
        </tbody>
        </table>

        <div>
          <span class="link" onclick="addVariableRow()">Добавить переменную</span>
        </div>
      </div>
      <div id="error" class="error hidden"></div>
      <button 
        type="button" 
        class="button" 
        id="submit_button" 
        onclick="sendRequestCurl()"
        >Отправить запрос</button>
    </form>

    <div class="spinner" id="preloader">
      <div class="double-bounce1"></div>
      <div class="double-bounce2"></div>
    </div>

    <div id="success" style="display:none;">
      <div id="media-container"></div>
    </div>
  </div>

  {include file="handlebars/variable-row-template.tpl"}
</section>

{include file="layout/footer.tpl"}