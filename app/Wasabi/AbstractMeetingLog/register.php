<?php
namespace App\Wasabi\AbstractMeetingLog;

use App\User;
use App\Wasabi\AbstractMeetingLog\Models\WasabiappAbstractMeetingLogPage;

class register{

	/**
	 * Wasabi App Config を生成する
	 * このメソッドは、 config/wasabi.php に登録する設定値を生成します。
	 */
	public static function config(){
		return [
			'id' => 'AbstractMeetingLog',
			'name' => 'Meeting Log Manager',
			'api' => 'App\\Wasabi\\AbstractMeetingLog\\register::api',
			'web' => 'App\\Wasabi\\AbstractMeetingLog\\register::web',
		];
	}

	/**
	 * Wasabi App: ウェブページを実行する
	 */
	public static function web($request, $project_id, $params){
		ob_start();
		var_dump($project_id, $params);
		$fin = ob_get_clean();

		return view(
			'App\Wasabi\AbstractMeetingLog::index',
			['main'=>$fin]
		);
	}

	/**
	 * Wasabi App: APIを実行する
	 */
	public static function api($request, $project_id, $params){
		$apiName = null;
		if( array_key_exists(0, $params) ){ $apiName = $params[0]; }
		$method = null;
		if( array_key_exists(1, $params) ){ $method = $params[1]; }

		$path = null;
		if( $request->has('path') ){
			$path = $request->path;
		}
		$path_md5 = null;
		if( is_string($path) && strlen($path) ){
			$path_md5 = md5($path);
		}

		if($apiName == 'page'){
			if( !strlen($path) ){
				return [ 'result' => false, 'error_message' => 'Parameter "path" is required.' ];
			}

			$page = WasabiappPickles2Page::where(['project_id'=>$project_id, 'path_md5'=>$path_md5])
				->first();

			$rtn = array();
			$rtn['result'] = true;
			$rtn['error_message'] = null;
			if( !$page ){
				$rtn['result'] = false;
				$rtn['error_message'] = 'Page not found.';
			}
			$rtn['project_id'] = ( $page ? $page->project_id : null );
			$rtn['path'] = ( $page ? $page->path : null );
			$rtn['title'] = ( $page ? $page->title : null );
			$rtn['assignee_id'] = ( $page ? $page->assignee_id : null );
			$rtn['status'] = ( $page ? $page->status : null );
			$rtn['end_date'] = ( $page ? $page->end_date : null );

			$assigned_user = null;
			if( strlen($rtn['assignee_id']) ){
				$assigned_user = User::find($rtn['assignee_id']);
			}
			$rtn['assignee'] = array();
			$rtn['assignee']['id'] = ( $assigned_user ? $assigned_user->id : null );
			$rtn['assignee']['name'] = ( $assigned_user ? $assigned_user->name : null );

			return $rtn;

		}elseif($apiName == 'update_page'){
			if( !strlen($path) ){
				return [ 'result' => false, 'error_message' => 'Parameter "path" is required.' ];
			}

			// WasabiappPickles2Page
			$page = WasabiappPickles2Page::where(['project_id'=>$project_id, 'path_md5'=>$path_md5])
				->first();

			if( !$page ){
				// 新規
				$page = new WasabiappPickles2Page();
				$page->path_md5 = $path_md5;
				$page->path = $path;
				$page->project_id = $project_id;

				$page->title = $request->get('title');
				$page->assignee_id = $request->get('assignee_id');
				$page->status = $request->get('status');
				$page->end_date = $request->get('end_date');

				$page->save();
			}else{
				// 更新
				$values = array();
				if( $request->has('title') ){ $values['title'] = $request->get('title'); }
				if( $request->has('assignee_id') ){ $values['assignee_id'] = $request->get('assignee_id'); }
				if( $request->has('status') ){ $values['status'] = $request->get('status'); }
				if( $request->has('end_date') ){ $values['end_date'] = $request->get('end_date'); }

				WasabiappPickles2Page::where(['project_id'=>$project_id, 'path_md5'=>$path_md5])->update($values);
			}

			return [
				'result' => true,
				'error_message' => null,
			];
		}

		return [
			'result' => false,
			'error_message' => 'API not found.',
		];
	}

}
