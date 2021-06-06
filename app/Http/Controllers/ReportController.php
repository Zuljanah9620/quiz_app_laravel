<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Question;
use App\Report;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'user']);
    }

    public function index() {
    	return view('report');
    }

    public function getReports(Request $request) {
    	$out = new \Symfony\Component\Console\Output\ConsoleOutput();
		$draw = $request->get('draw');
		$start = $request->get("start");
		$rowperpage = $request->get("length"); // Rows display per page

		$columnIndex_arr = $request->get('order');
		$columnName_arr = $request->get('columns');
		$order_arr = $request->get('order');
		$search_arr = $request->get('search');
		$columnIndex = $columnIndex_arr[0]['column']; // Column index
		$columnName = $columnName_arr[$columnIndex]['data']; // Column name
		$columnSortOrder = $order_arr[0]['dir']; // asc or desc
		$searchValue = $search_arr['value']; // Search value
		// Total records
		$totalRecords = Report::select('count(*) as allcount')->where('status', 'not like', '%done%')->count();
		$totalRecordswithFilter = Report::select('count(*) as allcount')->where('note', 'like', '%' .$searchValue . '%')->count();
		// Fetch records
		$out->writeln("Hello from Terminal");
		$out->writeln("Hello from Terminal");

		$records = Report::orderBy($columnName,$columnSortOrder)
		->join('question', 'question.id', '=', 'reports.question_id')
		->join('category', 'category.id', '=', 'question.category_id')
		->where('reports.note', 'like', '%' .$searchValue . '%')
		->where('reports.status', 'not like', '%done%' )
		->select('reports.*', 'category.name', 'question.question')
		->skip($start)
		->take($rowperpage)
		->get();
		$data_arr = array();

		$out->writeln("Hello from Terminal");
		foreach($records as $record){
			$out->writeln($record);
			$id = $record->id;
			$name = $record->name;
			$question_detail = $record->question;
			$note = $record->note;
			$status = $record->status;

			$btn = '<button type="button" name="edit" id="'.$id.'" class="edit btn btn-warning btn-sm">Edit</button>';

			$data_arr[] = array(
				"id" => $id,
				"name" => $name,
				"question" => $question_detail,
				"note" => $note,
				"status" => $status,
				"action" => $btn,
			);
		
		}
		$out->writeln("Hello from Terminal");
		

		$response = array(
		"draw" => intval($draw),
		"iTotalRecords" => $totalRecords,
		"iTotalDisplayRecords" => $totalRecordswithFilter,
		"aaData" => $data_arr
		);

		echo json_encode($response);
		exit;
    }

    public function edit($id) {
    	if(request()->ajax()) {
            $data = Report::findOrFail($id)
            ->join('question', 'question.id', '=', 'reports.question_id')
            ->join('category', 'category.id', '=', 'question.category_id')
            ->where('reports.id', 'like', '%' .$id . '%')
            ->select('reports.id', 'category.name', 'question.question', 'reports.note', 'reports.status')
            ->get();

            $out = new \Symfony\Component\Console\Output\ConsoleOutput();
            $out->writeln($data);
            return response()->json(['result' => $data]);
        }
    }

    public function update(Request $request) {
    	$rules = array(
            'status'   =>  'required',
        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $report = Report::findOrFail($request->hidden_id);
        $report->status = $request->status;
        $report->update();
        $report->save();

        return response()->json(['success' => 'Report updated successfully.']);
    }
}
