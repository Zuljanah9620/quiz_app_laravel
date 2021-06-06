<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Question;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'user']);
    }

    public function index() {
    	$categories = Category::all()->where('status', 'active');
    	$response_data = array();
    	foreach ($categories as $category) {
    		$out = new \Symfony\Component\Console\Output\ConsoleOutput();
			$out->writeln("Hello from Terminal");
    		$id = $category->id;
    		$totalRecords = Question::select('count(*) as allcount')->where('category_id', $id)->count();
			$name = $category->name;
			$lang_code = $category->lang_code;
			$image_url = $category->image_url;

			$response_data[] = array(
				"id" => $id,
				"name" => $name,
				"lang_code" => $lang_code,
				"image_url"  => $image_url,
				"totalRecords" => $totalRecords,
			);
    	}
    	return view('question')->with("response_data", $response_data);
    }

    public function questionIndex($id) {
    	$category_name = Category::findOrFail($id);
    	$category_id = $id;
    	$category_name = $category_name->name;
    	return view('question_manage', compact('category_id', 'category_name'));
    }

    public function getQuestions(Request $request, $id) {
    	$out = new \Symfony\Component\Console\Output\ConsoleOutput();
		$out->writeln("Hello from Terminal");
		$draw = $request->get('draw');
		$start = $request->get("start");
		$rowperpage = $request->get("length"); // Rows display per page

		$columnIndex_arr = $request->get('order');
		$columnName_arr = $request->get('columns');
		$order_arr = $request->get('order');
		$search_arr = $request->get('search');
		$out->writeln("Hello from Terminal");
		$columnIndex = $columnIndex_arr[0]['column']; // Column index
		$columnName = $columnName_arr[$columnIndex]['data']; // Column name
		$columnSortOrder = $order_arr[0]['dir']; // asc or desc
		$searchValue = $search_arr['value']; // Search value
		// Total records
		$totalRecords = Question::select('count(*) as allcount')->where('category_id', $id)->count();
		$totalRecordswithFilter = Question::select('count(*) as allcount')->where('category_id',  $id)->where('question', 'like', '%' .$searchValue . '%')->count();
		// Fetch records
		$records = Question::orderBy($columnName,$columnSortOrder)
		->where("category_id", $id)
		->where('question.question', 'like', '%' .$searchValue . '%')
		->select('question.*')
		->skip($start)
		->take($rowperpage)
		->get();
		$out->writeln("Hello from Terminal");
		$data_arr = array();


		foreach($records as $record){
			$id = $record->id;
			$question = $record->question;
			$level = $record->level;
			$status = $record->status;


			$btn = '<button type="button" name="edit" id="'.$id.'" class="edit btn btn-warning btn-sm">Edit</button>';
			$btn .= '&nbsp;&nbsp;&nbsp;<button type="button" name="detail" id="'.$id.'" class="detail btn btn-info btn-sm">Detail</button>';
			$btn .= '&nbsp;&nbsp;&nbsp;<button type="button" name="delete" id="'.$id.'" class="delete btn btn-danger btn-sm">Delete</button>';

			$data_arr[] = array(
				"id" => $id,
				"question" => $question,
				"level" => $level,
				"status" => $status,
				"action" => $btn,
			);
		
		}
		

		$response = array(
		"draw" => intval($draw),
		"iTotalRecords" => $totalRecords,
		"iTotalDisplayRecords" => $totalRecordswithFilter,
		"aaData" => $data_arr
		);

		echo json_encode($response);
		exit;
    }

    public function store(Request $request) {
    	$out = new \Symfony\Component\Console\Output\ConsoleOutput();
		$out->writeln("Hello from Terminal");
    	$rules = array(
            'question'     =>  'required|max:100',
            'true_answer'     =>  'required',
            'false_answer1'     =>  'required',
            'false_answer2'   =>  'required',
            'false_answer3'   =>  'required',
            'level'   =>  'required',
            'status'   =>  'required',
        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $out->writeln("Hello from Terminal");
        $form_data = array(
            'question'      =>  $request->question,
            'true_answer'     =>  $request->true_answer,
            'false_answer1'     =>  $request->false_answer1,
            'false_answer2'     =>  $request->false_answer2,
            'false_answer3'     =>  $request->false_answer3,
            'level'     =>  $request->level,
            'category_id'     =>  $request->id,
            'points'	=> "10",
            'status'    =>  $request->status,
        );
        $out->writeln("Hello from Terminal");
        Question::create($form_data);

        return response()->json(['success' => 'Question Added successfully.']);
    }

    public function store_csv(Request $request) {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln("Hello from Terminal");
        $rules = array(
            'csv_file'     =>  'required',
        );

        $error = Validator::make($request->all(), $rules);
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $out->writeln("Hello from Terminal");

        $csv_file = $request->file('csv_file');

        $filename = $csv_file->getClientOriginalName();
        $extension = $csv_file->getClientOriginalExtension();
        $tempPath = $csv_file->getRealPath();
        $fileSize = $csv_file->getSize();
        $mimeType = $csv_file->getMimeType();

        $maxFileSize = 2097152;
        $out->writeln("Hello from Terminal");
        if($fileSize > $maxFileSize) {
            return response()->json(['error' => "Csv file oversize"]);
        }

        $location = 'uploads';
        $out->writeln("Hello from Terminal");
        // Upload file
        $csv_file->move($location,$filename);

        // Import CSV to Database
        $filepath = public_path($location."/".$filename);

        // Reading file
        $csv_file = fopen($filepath,"r");

        $importData_arr = array();
        $i = 0;

        while (($filedata = fgetcsv($csv_file, 1000, ",")) !== FALSE) {
            $num = count($filedata );

            // Skip first row (Remove below comment if you want to skip the first row)
            if($i == 0){
                $i++;
                continue; 
            }
            for ($c=0; $c < $num; $c++) {
                $importData_arr[$i][] = $filedata [$c];
            }
            $i++;
        }
        fclose($csv_file);

        foreach($importData_arr as $importData){
            $out->writeln("Hello from Terminalxxx");
            $out->writeln($importData[1]);
            $insertData = array(
                'question'      =>  $importData[0],
                'true_answer'     =>  $importData[1],
                'false_answer1'     =>  $importData[2],
                'false_answer2'     =>  $importData[3],
                'false_answer3'     =>  $importData[4],
                'level'     =>  'easy',
                'category_id'     =>  $request->id,
                'points'    => '16',
                'status'    =>  $importData[5],
            );
            $out->writeln("Hello from Terminalxxx1");
            Question::create($insertData);
        }

        return response()->json(['success' => 'Question Added successfully.']);
    }

    public function edit($id) {
    	if(request()->ajax()) {
            $data = Question::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    public function update(Request $request) {
    	$out = new \Symfony\Component\Console\Output\ConsoleOutput();
		$out->writeln("Hello from Terminal");
    	$rules = array(
            'question'     =>  'required|max:100',
            'true_answer'     =>  'required',
            'false_answer1'     =>  'required',
            'false_answer2'   =>  'required',
            'false_answer3'   =>  'required',
            'level'   =>  'required',
            'status'   =>  'required',
        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $out->writeln("Hello from Terminal");
        $form_data = array(
            'question'      =>  $request->question,
            'true_answer'     =>  $request->true_answer,
            'false_answer1'     =>  $request->false_answer1,
            'false_answer2'     =>  $request->false_answer2,
            'false_answer3'     =>  $request->false_answer3,
            'level'     =>  $request->level,
            'category_id'     =>  $request->id,
            'points'	=> "10",
            'status'    =>  $request->status,
        );
        $out->writeln("Hello from Terminal");
        $question = Question::findOrFail($request->hidden_id);
        $question->update($form_data);
        $question->save();

        return response()->json(['success' => 'Question updated successfully.']);
    }

    public function show($id) {
        if(request()->ajax()) {
            $data = Question::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    public function destroy($id) {
        $data = Question::findOrFail($id);
        $data->delete();
    }
}
