<?php

namespace app\controllers\admin;

use app\controllers\api\Edits as EditsApiController;
use app\model\Suggestion;
use app\core\Functions;

class Edits extends EditsApiController
{
  public function index($limit = null, $state = null)
  {
    $limit = $limit ?? 20;
    $this->data['limit'] = $limit;
    $this->data['state'] = $state;
    if($this->model->list($limit, $state)) $this->data['edits'] = array_map('self::repl', $this->model->list($limit, $state));
  }

  public function edit($id = null)
  {
    if($id) {
      $this->data['data'] = self::repl($this->model->getEditDetailsById($id));
      $edit = $this->model->getById($id);

      if($edit['type'] === "suggestion") {
        $this->data['suggestion_data'] = (new Suggestion)->getById($edit['object_id']);
      }
      $this->data['object_edits'] = $this->model->getObjectEdits($this->data['data']['object_type'], $this->data['data']['object_id']);
    }
  }

  // humanize edit data 
  private static function repl($edit) {
    switch($edit['edit_type']) {
      case "suggestion":
        $edit['lang_edit_type'] = getString('SUGGESTION')." - ";
        switch($edit['edit_sub_type']) {
          case 0:
           $edit['lang_edit_sub_type'] = getString('SUPERMARKETS');
           break;
          case 1:
           $edit['lang_edit_sub_type'] = getString('CATEGORY');
           break;
          case 2:
           $edit['lang_edit_sub_type'] = getString('TAGS');
           break;
          case 3:
           $edit['lang_edit_sub_type'] = getString('IMAGE');
           break;
          case 4:
           $edit['lang_edit_sub_type'] = getString('IMAGE_INGREDIENTS');
           break;
          case 5:
           $edit['lang_edit_sub_type'] = getString('IMAGE_OTHER');
           break;
          case 6:
           $edit['lang_edit_sub_type'] = getString('NOTE');
           break;
          case 7:
           $edit['lang_edit_sub_type'] = getString('INGREDIENTS');
           break;
          case 8:
           $edit['lang_edit_sub_type'] = getString('BARCODE');
           break;
          case 9:
           $edit['lang_edit_sub_type'] = getString('REPORT');
           break;
          default:
            $edit['lang_edit_sub_type'] = getString('OTHER');
        }
        break;
      case "update":
        $edit['lang_edit_type'] = getString('UPDATE');
        break;
      case "accept":
        $edit['lang_edit_type'] = getString('ACCEPTED');
        break;
      case "deny":
        $edit['lang_edit_type'] = getString('DENIED');
        break;
      case "recover":
        $edit['lang_edit_type'] = getString('RECOVERED');
        break;
      default:
    }
    switch($edit['object_type']) {
      // case "product":
      //   $edit['lang_object_type'] = getString('PRODUCT').": ";
      //   break;
      case "locale":
        $edit['lang_object_type'] = getString('LOCALE');
        break;
    }
    return $edit;
  }

}
