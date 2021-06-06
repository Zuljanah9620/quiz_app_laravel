<?php

namespace App\Http\Controllers;

use App\User;
use App\Category;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class CategoryController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth', 'user']);
    }

    public function index()
    {
       
        return view('category');
    }

    public function getCategories(Request $request) {
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
         $totalRecords = Category::select('count(*) as allcount')->count();
         $totalRecordswithFilter = Category::select('count(*) as allcount')->where('name', 'like', '%' .$searchValue . '%')->count();

         // Fetch records
         $records = Category::orderBy($columnName,$columnSortOrder)
         ->where('category.name', 'like', '%' .$searchValue . '%')
         ->select('category.*')
         ->skip($start)
         ->take($rowperpage)
         ->get();

         $data_arr = array();
         
         foreach($records as $record){
            $id = $record->id;
            $image_url = $record->image_url;
            $lang_code = $record->lang_code;
            $name = $record->name;
            $status = $record->status;

            
            $btn = '<button type="button" name="edit" id="'.$id.'" class="edit btn btn-warning btn-sm">Edit</button>';
            $btn .= '&nbsp;&nbsp;&nbsp;<button type="button" name="delete" id="'.$id.'" class="delete btn btn-danger btn-sm">Delete</button>';

            $data_arr[] = array(
              "id" => $id,
              "name" => $name,
              "image_url" => $image_url,
              "lang_code"  => $lang_code,
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

    public function edit($id) {
        if(request()->ajax()) {
            $data = Category::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    public function update(Request $request){
      $out = new \Symfony\Component\Console\Output\ConsoleOutput();
      $out->writeln("Hello from Terminal");
        $rules = array(
            'edit_category_name'        =>  'required',
            'edit_category_image'        =>  'mimes:png,jpg,jpeg',
            'edit_category_status'  =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails() ){
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $out->writeln("Hello from Terminalaaaa");
        $file_path = '';
        $category = Category::findOrFail($request->edit_hidden_id);
        $out->writeln("Hello from Terminalbbbb");
        if($request->file('edit_category_image'))
        {
            $file_name = $request->file('edit_category_image')->getClientOriginalName();

            $request->edit_category_image->move(public_path('images/category'), $file_name);

            $file_path = '/images/category/'.$file_name;
        }
        else {
            if($category->image_url) {
                $file_path = $category->image_url;
            }
        }
        $out->writeln("Hello from Terminalsdfdfg");
        $form_data = array(
            'name'      =>  $request->edit_category_name,
            'image_url'     =>  $file_path,
            'status'    =>  $request->edit_category_status,
        );
        $out->writeln("Hello from Terminaldfgdfg");
        $category->update($form_data);
        $category->save();

        $out->writeln("Hello from Terminasddasdal");
        return response()->json(['success' => 'Category is successfully updated']);

    }

    public function store(Request $request) {
      $out = new \Symfony\Component\Console\Output\ConsoleOutput();
      $out->writeln("Hello from Terminal");
        $rules = array(
            'category_name'    =>  'required|max:15',
            'category_status'  =>  'required',
            'category_image'   =>  'required|mimes:png,jpg,jpeg'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $out->writeln("Hello from Terminalyy");
        $file_path = '';

        if($request->file('category_image'))
        {
            $file_name = $request->file('category_image')->getClientOriginalName();

            $request->category_image->move(public_path('images/category'), $file_name);

            $file_path = '/images/category/'.$file_name;
        }
        $out->writeln("Hello from Terminalgg");
        $form_data = array(
            'name'        =>  $request->category_name,
            'image_url'  =>  $file_path,
            'status'      =>  $request->category_status,
        );
        $out->writeln("Hello from Terminalqq");
        Category::create($form_data);
        $out->writeln("Hello from Terminalasd");
        return response()->json(['success' => 'Category Added successfully.']);

    }

    public function destroy($id)
    {
      $out = new \Symfony\Component\Console\Output\ConsoleOutput();
      $out->writeln("Hello from Terminal");
        $questions = Question::where("category_id", $id)->get();
        foreach ($questions as $question) {
          # code...
          $out->writeln($question);
          $question->delete();
        }
        $data = Category::findOrFail($id);
        $data->delete();
    }
}
