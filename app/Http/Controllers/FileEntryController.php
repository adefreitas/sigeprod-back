<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Request;
use App\Fileentry;
use Log;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;

class FileEntryController extends Controller {

	public function index(){
		$entries = Fileentry::all();
		return response()->json([
	            'entries' => $entries,
	        ]);
	}
	
	public function add(){
		// Log::info(Request::all());
		$request = Request::all();
		$file = $request['file'];
		Log::info(Request::all());
		// $file = Request::input('file');
		$extension = $file->getClientOriginalExtension();
		Log::info($extension);
		Storage::disk('local')->put($file->getFilename().'.'.$extension,  File::get($file));
		$entry = new Fileentry();
		$entry->preapproved_id = $request['id'];
		$entry->mime = $file->getClientMimeType();
		$entry->original_filename = $file->getClientOriginalName();
		$entry->filename = $file->getFilename().'.'.$extension;
		
		$entry->save();
		
		return response()->json([
			'entry' => $entry,
		]);
	}

}
