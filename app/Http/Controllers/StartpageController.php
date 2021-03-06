<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserSubEmail;
use App\UserGroupRelation;
use App\UserProjectRelation;

class StartpageController extends Controller
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
	}

	/**
	 * はじめの画面
	 * ログイン時はダッシュボードを表示する。
	 */
	public function startpage(){
		$user = Auth::user();
		if( !$user ){
			return view('startpage.index');
		}
		if( !$user->email_verified_at ){
			return view('auth.verify');
		}

		$subEmails = UserSubEmail::where(['user_id'=>$user->id])->get();

		$groups = UserGroupRelation
			::where(['user_id'=>$user->id])
			->leftJoin('groups', 'user_group_relations.group_id', '=', 'groups.id')
			->orderBy('groups.name')
			->get();

		$projects = UserProjectRelation
			::where(['user_id'=>$user->id])
			->leftJoin('projects', 'user_project_relations.project_id', '=', 'projects.id')
			->orderBy('projects.name')
			->get();

		return view(
			'startpage.dashboard',
			[
				'profile' => $user,
				'sub_emails'=>$subEmails,
				'groups'=>$groups,
				'projects'=>$projects,
			]
		);
	}
}
