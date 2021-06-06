<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
       
        return view('user');
    }

    public function getUsers(Request $request) {
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
         $totalRecords = User::select('count(*) as allcount')->count();
         $totalRecordswithFilter = User::select('count(*) as allcount')->where('name', 'like', '%' .$searchValue . '%')->count();

         // Fetch records
         $records = User::orderBy($columnName,$columnSortOrder)
         ->where('users.name', 'like', '%' .$searchValue . '%')
         ->select('users.*')
         ->skip($start)
         ->take($rowperpage)
         ->get();

         $data_arr = array();
         
         foreach($records as $record){
            $id = $record->id;
            $name = $record->name;
            $email = $record->email;
            $role = $record->role;
            $status = $record->status;

            
            $btn = '<button type="button" name="edit" id="'.$id.'" class="edit btn btn-warning btn-sm">Edit</button>';

            $data_arr[] = array(
              "id" => $id,
              "name" => $name,
              "email" => $email,
              "role"  => $role,
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
            $data = User::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    public function update(Request $request, User $User){
        $rules = array(
            'edit_user_name'        =>  'required',
            'edit_user_role'        =>  'required',
            'edit_user_status'  =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails() ){
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $user = User::findOrFail($request->edit_hidden_id);
        if(Auth::user()->email === $user->email) {
            return response()->json(['error' => 'You cant edit yourself']);
        }

        $user->name = $request->edit_user_name;
        $user->role = $request->edit_user_role;
        $user->status = $request->edit_user_status;
        $user->save();

        return response()->json(['success' => 'User is successfully updated']);

    }

    public function store(Request $request) {
      $out = new \Symfony\Component\Console\Output\ConsoleOutput();
      $out->writeln("Hello from Terminal");
        $rules = array(
            'user_name'     =>  'required',
            'user_email'     =>  'required|email|unique:users,email,' . Auth::user()->id,
            'user_role'     =>  'required',
            'user_status'   =>  'required'
        );
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'name'      =>  $request->user_name,
            'email'     =>  $request->user_email,
            'password'  =>  "123456aA",
            'role'      =>  $request->user_role,
            'status'    =>  $request->user_status
        );
        $out->writeln($form_data);
        User::create($form_data);

        return response()->json(['success' => 'User Added successfully.']);

    }
}
