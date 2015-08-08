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

		$id = \DB::table('preapproved_users')
			->where('personal_id', '=', $request["id"])
			->where('activated', '=', false)
			->orderBy('updated_at', 'desc')
			->first()
			->id;

		$file = $request['file'];
		if($file != ''){
			// Log::info(Request::all());
			// $file = Request::input('file');
			$extension = $file->getClientOriginalExtension();
			// Log::info($extension);
			Storage::disk('local')->put($request['id'].$request['type'].'.'.$extension,  File::get($file));
			$entry = new Fileentry();
			$entry->preapproved_id = $id;
			$entry->type = $request['type'];
			$entry->mime = $file->getClientMimeType();
			$entry->original_filename = $file->getClientOriginalName();
			$entry->filename = $request['id'].$request['type'].'.'.$extension;

			$entry->save();

			return response()->json([
				'entry' => $entry,
			]);
		}
		else{
			return response()->json([ 'error' => 404, 'message' => 'No se recibió ningún archivo' ], 404);
		}
	}

	public function get($filename){

		$entry = Fileentry::where('filename', '=', $filename)
			->orderBy('updated_at', 'desc')->firstOrFail();

		$file = Storage::disk('local')->get($entry->filename);

		return (new Response($file, 200))->header('Content-Type', $entry->mime);
	}

}
