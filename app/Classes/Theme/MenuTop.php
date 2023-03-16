<?php

namespace App\Classes\Theme;

use App\Models\MenuManager;
// use App\Models\Theme;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

class MenuTop
{

  public static function sidebar()
  {
    $menuManager = new MenuManager;
    $roleId = isset(Auth::user()->roles[0]) ? Auth::user()->roles[0]->id : NULL;
    $menu_list = $menuManager->getall($roleId);
    $roots = $menu_list->where('parent_id', 0) ?? array();
    return self::tree($roots, $menu_list, $roleId);
  }




//   public static function tree2($roots, $menu_list, $roleId, $parentId = 0)
//   {

//     $html = ' <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
//                 <div class="collapse navbar-collapse" id="topnav-menu-content">
//                     <ul class="navbar-nav">
//                         <li class="nav-item dropdown">
//                             <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
//                                 data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
//                                 <i class="icon nav-icon" data-eva="grid-outline"></i>
//                                 <span data-key="t-dashboards">Dashboards</span> <div class="arrow-down"></div>
//                             </a>
//                             <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
//                                 <a href="index-2.html" c data-key="t-ecommerce">Ecommerce</a>
//                                 <a href="dashboard-saas.html" class="dropdown-item" data-key="t-saas">Saas</a>
//                                 <a href="dashboard-crypto.html" class="dropdown-item" data-key="t-crypto">Crypto</a>
//                             </div>
//                         </li>


//                     </ul>
//                 </div>
//             </nav>';


//     return $html;

//   }


  public static function tree($roots, $menu_list, $roleId, $parentId = 0)
  {
    $html = '<ul class="navbar-nav">';
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
              <li  class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href ="'.(isset($item->path_url) ? $item->path_url  : 'javascript: void(0);').'">
                        <i class="' . (!$item->menu_permission_id ? $item->icon : $item->menupermission->icon) . '" ></i>
                        <span class="menu-item">'. (!$item->menu_permission_id ? $item->title : $item->menupermission->title) . '</span>
                    </a>

          ';
        }else{
          $html .= '
          <li class="nav-item">
            <a class="nav-link" href ="'.(!$item->menu_permission_id ? 'javascript: void(0);' : $item->menupermission->path_url).'">
                <i class="' . (!$item->menu_permission_id ? $item->icon : $item->menupermission->icon) . '"></i>
                <span class="menu-item">'. (!$item->menu_permission_id ? $item->title : $item->menupermission->title) . '</span>
            </a>

          ';
        }

        if ($find->count()) {
          $html .= self::children($find, $menu_list, $roleId, $item['id']);
        }
    $html .= '</li>';
      }

    return $html;

  }


  public static function children($roots, $menu_list, $roleId, $parentId = 0){
    $segment ='/'.request()->segment(1). '/' .request()->segment(2);
    foreach ($roots as $item) {
     //   $show = (isset($item->menupermission->path_url)  ?  ($segment == $item->menupermission->path_url) ? 'show' : '' : '');
        $html = '<div class="dropdown-menu">';
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

       ';
      }else{
        $html .= '
             <a class="dropdown-item" href="'.(!$item->menu_permission_id ? ($item->path_url ? $item->path_url : '#') : $item->menupermission->path_url).'">
                <i  style="width:20px;" class="' . (!$item->menu_permission_id ? $item->icon : $item->menupermission->icon) . '"  >
                </i>
                ' . (!$item->menu_permission_id ? $item->title : $item->menupermission->title) . '
              </a>
            ';
      }
    }
    $html .= '</div>';

    return $html;
  }


}
