<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Casefolders;

use DB;

class CasefolderController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }
	//Show New Folder Create
    public function show(){
		return view('new-folder');
	}
	//Create New Folder
    public function createfolder(){
		$this->validate(request(), [
        'foldername' => 'required',
    ]);
	if(request()->has('case')){
		$caseid = request()->case;
		$countfoldername = DB::table('casefolders')
		                    ->where('folder_name', '=',request('foldername'))
							->where('user_id', '=',auth()->id())
							->where('case_id', '=',$caseid)
							->where('parent_folder_id', '=',0)
							->count();
	}else{
		$caseid = 0;
	}
	$redirecturl='/case-files?case='.$caseid;
	if(request()->has('folder')){
		$parentfolderid = request()->folder;
		$redirecturl='/case-files?case='.$caseid.'&folder='.$parentfolderid;
		$countfoldername = DB::table('casefolders')
							->where('folder_name', '=',request('foldername'))
							->where('user_id', '=',auth()->id())
							->where('case_id', '=',$caseid)
							->where('parent_folder_id', '=',$parentfolderid)
							->count();
	}else{
		$parentfolderid = 0;
		
	}
	if($countfoldername==1){
		return redirect($redirecturl)->with('error', 'Folder Name Already Exists!');
	}else{
		$folder= new Casefolders;
		$folder->folder_name=request('foldername');		
		$folder->parent_folder_id=$parentfolderid;		
		$folder->case_id=$caseid;
		$folder->user_id=auth()->id();
        $folder->save();
		return redirect($redirecturl)->with('success', 'Folder Created Successfully!');
	}
		
        	
	}
}
