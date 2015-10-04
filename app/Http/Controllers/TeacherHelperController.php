<?php namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Http\Requests;
use App\TeacherHelper;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade;

class TeacherHelperController extends Controller {

	/**
	* Display a listing of the resource.
	*
	* @return Response
	*/
	public function index()
	{
		try {
			JWTAuth::parseToken();
			$token = JWTAuth::getToken();
		} catch (Exception $e){
			return response()->json(['error' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
		}

		$tokenOwner = JWTAuth::toUser($token);

		$user = User::where('email', $tokenOwner->email)->first();

		$helpers = [];

		if($user->is('departmenthead') || $user->is('departmentsecretary') || $user->is('directionsecretary')){

			$helpers = \DB::table('teacher_helpers_users')
			->join('teacher_helpers', 'teacher_helpers.id', '=', 'teacher_helpers_users.teacher_helper_id')
			->join('users', 'users.id', '=', 'teacher_helpers_users.user_id')
			->join('courses_teacher_helpers', 'courses_teacher_helpers.helper_id', '=', 'teacher_helpers_users.id', 'left outer')
			->join('courses', 'courses.id', '=', 'courses_teacher_helpers.course_id', 'left outer')
			->join('centers_teacher_helpers', 'centers_teacher_helpers.helper_id', '=', 'teacher_helpers_users.id', 'left outer')
			->join('centers', 'centers.id', '=', 'centers_teacher_helpers.center_id', 'left outer')
			// ->where('teacher_helpers_users.active', '=', true)
			->orderBy('teacher_helpers.type', 'asc')
			->orderBy('users.id', 'asc')
			->select(
			'users.name as user_name', 'users.lastname as user_lastname', 'users.email as user_email',
			'users.id as user_id', 'users.local_phone', 'users.cell_phone',
			'users.state', 'users.municipality', 'users.address',
			'teacher_helpers.id as teacher_helper_id',
			'courses.name as course_name', 'courses.id as course_id',
			'centers.name as center_name', 'centers.id as center_id',
			'teacher_helpers.updated_at', 'teacher_helpers.created_at',
			'teacher_helpers_users.id as thu_id',
			'teacher_helpers.type as type', 'courses_teacher_helpers.active as course_active', 'centers_teacher_helpers.active as center_active',
			'teacher_helpers_users.contest_id'
			)
			->get();

			foreach($helpers as $helper){
				$pre = \DB::table('preapproved_users')
				->where('email', '=', $helper->user_email)
				->orderBy('updated_at', 'desc')
				->get();
				$helper->files = \DB::table('fileentries')
				->where('fileentries.preapproved_id', '=', $pre[0]->id)
				->orderBy('updated_at', 'desc')
				->get();
			}
			$pendings = \DB::table("preapproved_users")
				->join('contests', 'contests.id', '=', 'preapproved_users.contest_id', 'left outer')
				->join('center_contest', 'center_contest.contest_id', '=', 'preapproved_users.contest_id', 'left outer')
				->join('centers', 'centers.id', '=', 'center_contest.center_id', 'left outer')
				->join('contest_course', 'contest_course.contest_id', '=', 'preapproved_users.contest_id', 'left outer')
				->join('courses', 'courses.id', '=', 'contest_course.course_id', 'left outer')
				->select('preapproved_users.id as preapproved_users_id','preapproved_users.email as email', 'preapproved_users.name as name',
				'preapproved_users.lastname as lastname', 'preapproved_users.personal_id as personal_id', 'preapproved_users.activated as activated',
				'preapproved_users.type as type',
				'centers.id as center_id', 'centers.name as center_name',
				'courses.id as course_id', 'courses.name as course_name')
				->where('preapproved_users.activated', '=', false)
				->groupBy('preapproved_users.id', 'centers.id', 'courses.id')
				->get();


		}
		else if($user->is('centercoordinator') || $user->is('coursecoordinator')){
			$centers = $user->professor->centerCoordinator;
			$courses = $user->professor->courseCoordinator;

			$centers_ids = array();
			$courses_ids = array();

			foreach($centers as $center){
				array_push($centers_ids, $center->id);
			}
			foreach($courses as $course){
				array_push($courses_ids, $course->id);
			}
			// return response()->json([
			// 	'response' => $courses_ids
			// 	]);
			$helpers = \DB::table('teacher_helpers_users')
			->join('teacher_helpers', 'teacher_helpers.id', '=', 'teacher_helpers_users.teacher_helper_id')
			->join('users', 'users.id', '=', 'teacher_helpers_users.user_id')
			->join('courses_teacher_helpers', 'courses_teacher_helpers.helper_id', '=', 'teacher_helpers_users.id', 'left outer')
			->join('courses', 'courses.id', '=', 'courses_teacher_helpers.course_id', 'left outer')
			->join('centers_teacher_helpers', 'centers_teacher_helpers.helper_id', '=', 'teacher_helpers_users.id', 'left outer')
			->join('centers', 'centers.id', '=', 'centers_teacher_helpers.center_id', 'left outer')
			// ->where('teacher_helpers_users.active', '=', true)
			->orderBy('teacher_helpers_users.active', 'desc')
			->orderBy('teacher_helpers.type', 'asc')
			->orderBy('users.id', 'asc')
			->whereIn('courses_teacher_helpers.course_id', $courses_ids)
			->orWhereIn('centers_teacher_helpers.center_id', $centers_ids)
			->select(
			'users.name as user_name', 'users.lastname as user_lastname', 'users.email as user_email',
			'users.id as user_id', 'users.local_phone', 'users.cell_phone',
			'users.state', 'users.municipality', 'users.address',
			'teacher_helpers.id as teacher_helper_id',
			'courses.name as course_name', 'courses.id as course_id',
			'centers.name as center_name', 'centers.id as center_id',
			'teacher_helpers.updated_at', 'teacher_helpers.created_at',
			'teacher_helpers_users.id as thu_id',
			'teacher_helpers.type as type', 'courses_teacher_helpers.active as course_active', 'centers_teacher_helpers.active as center_active',
			'teacher_helpers_users.contest_id'
			)
			->get();

			foreach($helpers as $helper){
				$pre = \DB::table('preapproved_users')
				->where('email', '=', $helper->user_email)
				->orderBy('updated_at', 'desc')
				->get();
				$helper->files = \DB::table('fileentries')
				->where('fileentries.preapproved_id', '=', $pre[0]->id)
				->orderBy('updated_at', 'desc')
				->get();
			}

			$pendings = \DB::table("preapproved_users")
				->join('contests', 'contests.id', '=', 'preapproved_users.contest_id', 'left outer')
				->join('center_contest', 'center_contest.contest_id', '=', 'preapproved_users.contest_id', 'left outer')
				->join('centers', 'centers.id', '=', 'center_contest.center_id', 'left outer')
				->join('contest_course', 'contest_course.contest_id', '=', 'preapproved_users.contest_id', 'left outer')
				->join('courses', 'courses.id', '=', 'contest_course.course_id', 'left outer')
				->select('preapproved_users.id as preapproved_users_id','preapproved_users.email as email', 'preapproved_users.name as name',
				'preapproved_users.lastname as lastname', 'preapproved_users.personal_id as personal_id', 'preapproved_users.activated as activated',
				'preapproved_users.type as type',
				'centers.id as center_id', 'centers.name as center_name',
				'courses.id as course_id', 'courses.name as course_name')
				->whereIn('contest_course.course_id', $courses_ids)
				->orWhereIn('center_contest.center_id', $centers_ids)
				->where('preapproved_users.activated', '=', false)
				->groupBy('preapproved_users.id', 'centers.id', 'courses.id')
				->get();

		}

		return response()->json([
			'helpers' => $helpers,
			'pendings' => $pendings
			]);

			return response()->json([ 'message' => 'No autorizado' ], 404);
		}

		public function idunico(Request $request, $id)
		{
			try {
				JWTAuth::parseToken();
				$token = JWTAuth::getToken();
			} catch (Exception $e){
				return response()->json(['error' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
			}

			$tokenOwner = JWTAuth::toUser($token);

			$user = User::where('email', $tokenOwner->email)->first();

			//editar
			if ($request->status == 1) {
				DB::table('teacher_helpers')
            		->where('id', '=', $request->id)
            		->update(['id' => $request->nuevo]);

            	return response()->json(['success'=>true]);
			}
			//borrar
			else if ($request->status == 2) {
				DB::table('teacher_helpers')
					->where('id', '=', $request->id)->delete();

				return response()->json(['success'=>true]);
			}
			//agregar
			else if ($request->status == 3) {
				DB::table('users')
					->insert(['id' => $request->nuevo, 'available' => $request->available, 'reserved' => $request->reserved]);

				return response()->json(['success'=>true]);
			}
		}

		public function buscarid()
		{
			try {
				JWTAuth::parseToken();
				$token = JWTAuth::getToken();
			} catch (Exception $e){
				return response()->json(['error' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
			}

			$tokenOwner = JWTAuth::toUser($token);

			$user = User::where('email', $tokenOwner->email)->first();

			$buscar = \DB::table('teacher_helpers')
				->where('available', '=', true)
				->where('reserved', '=', false)
				->orderBy('id', 'asc')
				->select('id')
				->get();

			return response()->json(['id' => $buscar]);

		}


		/**
		* Store a newly created resource in storage.
		*
		* @return Response
		*/
		public function store()
		{
			//
		}

		/**
		* Display the specified resource.
		*
		* @param  int  $id
		* @return Response
		*/
		public function show($id)
		{
			try {
				JWTAuth::parseToken();
				$token = JWTAuth::getToken();
			} catch (Exception $e){
					return response()->json(['error' => $e->getMessage()], HttpResponse::HTTP_UNAUTHORIZED);
			}
			$tokenOwner = JWTAuth::toUser($token);

			$user = User::where('email', $tokenOwner->email)->first();

			if($user->id == $id){
				$helper = \DB::table('teacher_helpers_users')
				->join('teacher_helpers', 'teacher_helpers.id', '=', 'teacher_helpers_users.teacher_helper_id')
				->join('users', 'users.id', '=', 'teacher_helpers_users.user_id')
				->join('courses_teacher_helpers', 'courses_teacher_helpers.helper_id', '=', 'teacher_helpers_users.id', 'left outer')
				->join('courses', 'courses.id', '=', 'courses_teacher_helpers.course_id', 'left outer')
				->join('centers_teacher_helpers', 'centers_teacher_helpers.helper_id', '=', 'teacher_helpers_users.id', 'left outer')
				->join('centers', 'centers.id', '=', 'centers_teacher_helpers.center_id', 'left outer')
				// ->where('teacher_helpers_users.active', '=', true)
				->orderBy('teacher_helpers.type', 'asc')
				->orderBy('users.id', 'asc')
				->select(
				'users.name as user_name', 'users.lastname as user_lastname', 'users.email as user_email',
				'users.id as user_id', 'users.local_phone', 'users.cell_phone',
				'users.state', 'users.municipality', 'users.address',
				'teacher_helpers.id as teacher_helper_id',
				'courses.name as course_name', 'courses.id as course_id',
				'centers.name as center_name', 'centers.id as center_id',
				'teacher_helpers.updated_at', 'teacher_helpers.created_at',
				'teacher_helpers_users.id as thu_id',
				'teacher_helpers.type as type', 'courses_teacher_helpers.active as course_active', 'centers_teacher_helpers.active as center_active',
				'teacher_helpers_users.contest_id'
				)
				->where('users.id', '=', $id)
				->first();


				$pre = \DB::table('preapproved_users')
				->where('email', '=', $helper->user_email)
				->orderBy('updated_at', 'desc')
				->get();

				$files = array();
				$types = array("id","rif","proof","kardex","bank","photo");

				foreach($types as $type){
					array_push($files, \DB::table('fileentries')
					->where('fileentries.preapproved_id', '=', $pre[0]->id)
					->orderBy('updated_at', 'desc')
					->where('type', '=', $type)
					->first());
				}


				$helper->files = $files;
				return response()->json([
					'helper' => $helper
					]);
			}
			return response()->json([ 'message' => 'No autorizado' ], 404);

		}

		/**
		* Update the specified resource in storage.
		*
		* @param  int  $id = id en la tabla teacher_helpers_users
		* @return Response
		*/

		public function prueba($id)
		{
			$helper = \DB::table('teacher_helpers_users')
			->join('teacher_helpers', 'teacher_helpers.id', '=', 'teacher_helpers_users.teacher_helper_id')
			->join('users', 'users.id', '=', 'teacher_helpers_users.user_id')
			->join('courses_teacher_helpers', 'courses_teacher_helpers.helper_id', '=', 'teacher_helpers_users.id', 'left outer')
			->join('courses', 'courses.id', '=', 'courses_teacher_helpers.course_id', 'left outer')
			->join('centers_teacher_helpers', 'centers_teacher_helpers.helper_id', '=', 'teacher_helpers_users.id', 'left outer')
			->join('centers', 'centers.id', '=', 'centers_teacher_helpers.center_id', 'left outer')
			// ->where('teacher_helpers_users.active', '=', true)
			->orderBy('teacher_helpers_users.active', 'desc')
			->orderBy('teacher_helpers.type', 'asc')
			->orderBy('users.id', 'asc')
			->where('teacher_helpers_users.user_id', '=', $id)
			->select(
			'users.name as user_name', 'users.lastname as user_lastname', 'users.email as user_email',
			'users.id as user_id', 'users.local_phone', 'users.cell_phone',
			'users.state', 'users.municipality', 'users.address',
			'teacher_helpers.id as teacher_helper_id',
			'courses.name as course_name', 'courses.id as course_id',
			'centers.name as center_name', 'centers.id as center_id',
			'teacher_helpers.updated_at', 'teacher_helpers.created_at',
			'teacher_helpers_users.id as thu_id',
			'teacher_helpers.type as type', 'courses_teacher_helpers.active as course_active', 'centers_teacher_helpers.active as center_active',
			'teacher_helpers_users.contest_id'
			)
			->first();
			$today = Carbon::now();
			// $todayFormated = Carbon::createFromFormat('d/M/Y', $today)->toDateString();
			$data = array('name'=>'John Smith', 'id' => $id, 'helper' => $helper);
			$pdf = \DPDF::loadView('prueba', $data)->setPaper('a4');
			$pdf->save('temp.pdf');


			$mergerpdf = new \Clegginabox\PDFMerger\PDFMerger;

			$storagePath  = \Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();

			$mergerpdf->addPDF('temp.pdf', 'all');

			$kardexFileName = \App\Fileentry::where('filename', '=', $id.'kardex.pdf')->orderBy('updated_at', 'desc')->first();
			$kardexFile = \Storage::disk('local')->get($kardexFileName->filename);

			$rifFileName = \App\Fileentry::where('filename', '=', $id.'rif.pdf')->orderBy('updated_at', 'desc')->first();
			$rifFile = \Storage::disk('local')->get($rifFileName->filename);

			// $idFileName = \App\Fileentry::where('type', '=', 'id')->where('filename', 'like', $id."%")->orderBy('updated_at', 'desc')->first();
			// $idFile = \Storage::disk('local')->get($idFileName->filename);

			$mergerpdf->addPDF($storagePath."/".$kardexFileName->filename);
			$mergerpdf->addPDF($storagePath."/".$rifFileName->filename);
			// $mergerpdf->addPDF($storagePath."/".$idFileName->filename);

			// $mergerpdf->addPDF($rifFile);
			return $mergerpdf->merge('browser','P');


			// $pdf = \DPDF::loadHTML('<h1>Test</h1>');
			// return $pdf->download('invoice.pdf');
		}

		/**
		* Update the specified resource in storage.
		*
		* @param  int  $id = id en la tabla teacher_helpers_users
		* @return Response
		*/
		public function update(Request $request, $id)
		{

			$current = \DB::table('teacher_helpers_users')
				->where('id', '=', $id)
				->where('contest_id', '=', $request->contest_id)
				// ->where('active', '=', true)
				->first();

			//Si se esta ratificando el preparador para ese concurso, se modifica la fecha de actualizacion
			if(!$request->retiring)
			{

				\DB::table('teacher_helpers_users')
					->where('id', '=', $id)
					->where('contest_id', '=', $request->contest_id)
					->where('active', '=', true)
					->update([
						'updated_at' => Carbon::now()
					]);

					return response()->json(['success'=>true]);

			}
			//Sino
			else if($request->retiring)
			{
				\Log::info('current->type'.$current->type);
				$current_type = $current->type;
				$currenthelper_id = $current->teacher_helper_id;


				$others = \DB::table('teacher_helpers_users')
					->where('teacher_helper_id', '=', $currenthelper_id)
					->where('active', '=', true)
					->where('id', '!=', $id)
					->get();

				$typeIsDifferent = false;
				$newtype = $current_type;

				$currenthelper = TeacherHelper::find($currenthelper_id);

				if($request->center_id){
						$currenthelper->removeCenter($request->center_id, $request->contest_id);
				}
				else if($request->course_id){
						$currenthelper->removeCourse($request->course_id, $request->contest_id);
				}

				$currenthelper->save();

				foreach($others as $other){
					if($other->type > $current_type){
						$typeIsDifferent = true;
						$newtype = $other->type;
					}
				}

				if(!$typeIsDifferent && $others == null){

					\DB::table('teacher_helpers_users')
						->where('id', '=', $id)
						->where('contest_id', '=', $request->contest_id)
						->where('active', '=', true)
						->update([
							'active' => false
					]);

					$currenthelper->clear();

					return response()->json(['success', true]);

				}
				else if(!$typeIsDifferent && $others != null){

					\DB::table('teacher_helpers_users')
						->where('id', '=', $id)
						->where('contest_id', '=', $request->contest_id)
						->where('active', '=', true)
						->update([
							'active' => false
					]);

					return response()->json(['success', true]);
				}

				else if($typeIsDifferent && $others != null){


					$old_centers = $currenthelper->centers();
					$old_courses = $currenthelper->courses();

					$old_centers_ids = array();
					$old_courses_ids = array();

					foreach($old_centers as $old_center){
							array_push($old_centers_ids, $old_center->id);
					}
					foreach($old_courses as $old_course){
							array_push($old_courses_ids, $old_course->id);
					}

					$newhelper = TeacherHelper::where('available', '=', true)
						->where('reserved', '=', false)
						->where('type', '=', $newtype)
						->first();

					if($newhelper == null){

						// \DB::table('teacher_helpers_users')
						// 	->where('id', '=', $id)
						// 	->where('contest_id', '=', $request->contest_id)
						// 	->where('active', '=', true)
						// 	->update([
						// 		'active' => false
						// ]);

						//Se queda con su ID de preparador anterior por no haber plazas disponibles del nuevo tipo
						return response()->json(['success', true]);
					}
					else{
						//Se le asigna un ID de preparador nuevo que cumpla con el nuevo tipo
						$helper_users = $currenthelper->user;
						$current_user = null;
						foreach($helper_users as $helper_user){
							$aux = \DB::table('teacher_helpers_users')
								->where('active', '=', true)
								->where('user_id', '=', $helper_user->id)
								->first();
								if($aux != null){
									$current_user = $aux;
								}
						}

						TeacherHelper::where('available', '=', true)
							->where('reserved', '=', false)
							->where('id', '=', $newhelper->id)
							->update([
								'available' => false
						]);
						\Log::info('current_user->id'.$current_user->id);
						$newhelper->user()->attach($current_user->id, ['contest_id'=> $request->contest_id, 'type' => $newtype]);



						foreach($old_centers_ids as $old_center_id){
							$newhelper->setCenter($old_center_id, $request->contest_id);
						}
						foreach($old_courses_ids as $old_course_id){
							$newhelper->setCourse($old_course_id, $request->contest_id);
						}
						$newhelper->save();
						$currenthelper->clear();

						\DB::table('teacher_helpers_users')
							->where('id', '=', $id)
							->where('contest_id', '=', $request->contest_id)
							->where('active', '=', true)
							->update([
								'active' => false
						]);
					}
					return response()->json(['success' => true]);
				}
			}
			return response()->json(['success', true]);
		}

			/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return Response
			*/
			public function destroy($id)
			{
				//
			}

		}
