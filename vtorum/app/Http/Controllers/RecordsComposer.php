<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class RecordsComposer extends Controller
{
    public function compose(View $view)
    {
        $view->with('records', Record.php::all());
    }
}
