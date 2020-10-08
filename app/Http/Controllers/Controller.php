<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
    
    # Base variable for all controllers
    # Contains all data to be sent to views
    protected $base = array();
    
    public function __construct(Request $request) {
        Session::put('permission', SessionController::getuserpermissions());
        $this->base = array(
            'site_title' => Config::get('base.site_title'),
            'can_read' => Config::get('base.can_read'),
            'can_write' => Config::get('base.can_write'),
            'can_delete' => Config::get('base.can_delete'),
            'navigation' => NavigationController::generate(),
            'breadcrumbs' => BreadcrumbsController::generate($request),
        );
    }
}
