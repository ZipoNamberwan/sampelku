<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PetugasController extends Controller
{
    function pclIndex()
    {
        return view('pcl/index');
    }

    function pmlIndex()
    {
        return view('pml/index');
    }

    function recommendation()
    {
        return view('pml/recommendation');
    }

    function status()
    {
        return view('pml/status');
    }
}
