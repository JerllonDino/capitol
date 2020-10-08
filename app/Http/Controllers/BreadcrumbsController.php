<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

use App\Http\Requests;

use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BreadcrumbsController extends Controller
{
    public static function generate(Request $request) {
        # Gets root and paths from URL
		# Also get list of existing routes
        $paths = explode('/', $request->path());
		$routes = Route::getRoutes();
        
        # Generate breadcrumbs from paths
        $breadcrumbs = array();
        $consolidated_path = '';
        foreach ($paths as $i => $path) {
            $consolidated_path .= '/' . $path;
            $path = preg_replace('/_+/', ' ', $path);
			# Insert valid paths
			try {
				$request = Request::create($consolidated_path);
				$routes->match($request);
				$title = (is_numeric($path)) ? ucwords(preg_replace('/_+/', ' ', $paths[$i - 1])) . ' View' : ucwords($path);
                array_push(
                    $breadcrumbs,
                    [
                        'title' => $title,
                        'url' => url($consolidated_path),
                    ]
                );
			}
			catch (NotFoundHttpException $e) { }
			catch (MethodNotAllowedHttpException $e) { }
			
        }
        return $breadcrumbs;
    }
}
