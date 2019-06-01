@extends('layouts.app')
@section('title', 'グループ '.$group->name)

@section('content')
<div class="container">

	<table class="table table__dd">
		<tbody>
			<tr>
				<th><label for="name">グループ名</label></th>
				<td>
					<p>{{ $group->name }}</p>
				</td>
			</tr>
			<tr>
				<th><label for="email">アカウント</label></th>
				<td>
					<p>{{ $group->account }}</p>
				</td>
			</tr>
		</tbody>
	</table>

	<hr />
	<div class="text-right">
		<a href="{{ url('/settings/groups') }}" class="btn btn-default">一覧へ戻る</a>
		<a href="{{ url('/settings/groups/'.urlencode($group->id).'/edit') }}" class="btn btn-primary">編集する</a>
	</div>

</div>
@endsection
