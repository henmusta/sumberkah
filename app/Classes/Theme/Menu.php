<?php

namespace App\Classes\Theme;

use App\Models\MenuManager;
// use App\Models\Theme;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

class Menu
{

  public static function sidebar()
  {
    $menuManager = new MenuManager;
    $roleId = isset(Auth::user()->roles[0]) ? Auth::user()->roles[0]->id : NULL;
    $menu_list = $menuManager->getall($roleId);
    $roots = $menu_list->where('parent_id', 0) ?? array();
    return self::tree($roots, $menu_list, $roleId);
  }



  public static function tree($roots, $menu_list, $roleId, $parentId = 0)
  {
    $html = '<ul class="metismenu list-unstyled" id="side-menu">';
    $segment ='/'.request()->segment(1). '/' .request()->segment(2);
    foreach ($roots as $item) {
        $find = $menu_list->where('parent_id', $item['id']);
        if($find->count()){
          $mm_active = "";
          $segment_child ='/'.request()->segment(1). '/' .request()->segment(2);
          foreach ($find as $val) {
              if( $val->menupermission->path_url == $segment_child){
                  $mm_active = "mm-active" ;
              }
          }
          $html .= '
              <li class="'.$mm_active.'">
                    <a class="'.($find->count() > 0  ? "has-arrow" : '').'" href ="'.(isset($item->path_url) ? $item->path_url  : 'javascript: void(0);').'">
                        <i class="' . (!$item->menu_permission_id ? $item->icon : $item->menupermission->icon) . '" ></i>
                        <span class="menu-item">'. (!$item->menu_permission_id ? $item->title : $item->menupermission->title) . '</span>
                    </a>

          ';
        }else{
          $html .= '
          <li >
          <a  href ="'.(!$item->menu_permission_id ? 'javascript: void(0);' : $item->menupermission->path_url).'">
             <i class="' . (!$item->menu_permission_id ? $item->icon : $item->menupermission->icon) . '"></i>
              <span class="menu-item">'. (!$item->menu_permission_id ? $item->title : $item->menupermission->title) . '</span>
          </a>
          ';
        }

        if ($find->count()) {
          $html .= self::children($find, $menu_list, $roleId, $item['id']);
        }
        $html .= '</li>';
        $html .= '</li>';
      }

    return $html;

  }


  public static function children($roots, $menu_list, $roleId, $parentId = 0){
    $segment ='/'.request()->segment(1). '/' .request()->segment(2);
    foreach ($roots as $item) {
     //   $show = (isset($item->menupermission->path_url)  ?  ($segment == $item->menupermission->path_url) ? 'show' : '' : '');
        $html = '<ul class="sub-menu" aria-expanded="false">';
    }


    foreach ($roots as $item) {

      $find = $menu_list->where('parent_id', $item['id']);
      $active = (isset($item->menupermission->path_url)  ?  ($segment == $item->menupermission->path_url) ? 'mm-active' : '' : '');
      if ($find->count() > 0) {

        // $segment_child ='/'.request()->segment(1). '/' .request()->segment(2);
        // foreach ($find as $val) {
        //     if( ){
        //         $active = "mm-active" ;
        //     }
        // }
        $htmlChildren = self::children($find, $menu_list, $roleId, $item['id']);
        $html .= '
        <li class="'.$active.'">
            <a>
            <i class="' . (!$item->menu_permission_id ? $item->icon : $item->menupermission->icon) . '"></i>
                ' . (!$item->menu_permission_id ? $item->title : $item->menupermission->title) . '
            </a>
            '.$htmlChildren.'
        </li>';
      }else{
        $html .= '
        <li class="'.$active.'">
            <a href="'.(!$item->menu_permission_id ? ($item->path_url ? $item->path_url : '#') : $item->menupermission->path_url).'">
            <i  style="width:20px;" class="' . (!$item->menu_permission_id ? $item->icon : $item->menupermission->icon) . '"  >
            </i>
                ' . (!$item->menu_permission_id ? $item->title : $item->menupermission->title) . '
            </a>
        </li>';
      }
    }
    $html .= '</ul>';
    return $html;
  }
}


