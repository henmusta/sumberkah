<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice;
use App\Models\KonfirmasiJo;
use App\Models\Customer;
use App\Models\Joborder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Traits\NoUrutTrait;
use Carbon\Carbon;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PDF;
use Throwable;

class InvoiceCustomController extends Controller
{
    public function create(Request $request)
    {

      $config['page_title'] = "Tambah Invoice Custom";
      $page_breadcrumbs = [
        ['url' => route('backend.invoice.index'), 'title' => "Data Invoice v"],
        ['url' => '#', 'title' => "Tambah Invoice Custom"],
      ];
      return view('backend.invoice.create', compact('page_breadcrumbs', 'config'));
    }
}
