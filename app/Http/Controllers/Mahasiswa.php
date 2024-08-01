<?php

namespace App\Http\Controllers\Tech;

use App\Models\Mahasiswa;
use App\Models\RegMhasiswa;
use App\Models\Jurusan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PasienController extends Controller
{
    public function index()
        {
            $this->view('auth',['tech']);
        }
    }