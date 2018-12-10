<?php

class Controller_Note extends Controller {
  public function index() {
    $mod = new Model_Note();
    $note = $mod->findByQuery("SELECT * FROM note", "id DESC");

    $view = new View();
    $view->add('note', $note);
    $view->template = 'note/note.tpl';

    return $view->render();
  }

  public function ajaxAdd() {
    $note = Request::postStr("note");
    if (empty($note)) ajax(array2json(array("error" => "Введите текст")));

    $mod = new Model_Note();
    $mod->date_created  = time();
    $mod->date_editing  = "";
    $mod->note          = $note;
    $mod->save();

    $view = new View();
    $view->add('subitem', $mod);
    $view->add('hidden', true);
    $view->template = 'note/item.tpl';

    ajax(array2json(array("complete" => $view->render())));
  }
}