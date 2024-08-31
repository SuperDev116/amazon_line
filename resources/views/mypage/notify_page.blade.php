@extends("layouts.mypage")

@php
	$errors = App\Models\Error::paginate(10);
@endphp

@section('content')
<section class="content">
	<div class="block-header">
		<div class="row">
			<div class="col-lg-7 col-md-6 col-sm-12">
				<h2>ログ一覧</h2>
				<ul class="breadcrumb">
					<li class="breadcrumb-item"><a href="/"><i class="zmdi zmdi-home"></i> Amazon</a></li>
					<li class="breadcrumb-item active">ログ一覧</li>
				</ul>
				<button class="btn btn-primary btn-icon mobile_menu" type="button"><i class="zmdi zmdi-sort-amount-desc"></i></button>
			</div>
			<div class="col-lg-5 col-md-6 col-sm-12">
				<button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i class="zmdi zmdi-arrow-right"></i></button>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row clearfix">
			<div class="col-12">
				<div class="card widget_2 big_icon">
					<div class="body">
						<div class="table-responsive">
							<table class="table table-hover table-striped product_item_list c_table theme-color mb-0">
								<thead>
									<tr>
										<th colspan="1" rowspan="1" style="text-align: center;">No</th>
										<th colspan="1" rowspan="1" style="text-align: center;">日付</th>
										<th colspan="1" rowspan="1" style="text-align: center;">通知内容</th>
										<th colspan="1" rowspan="1" style="text-align: center;">ユーザー</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($errors as $e)
										<tr data-id="{{ $e->id }}">
											<td colspan="1" rowspan="1" style="text-align: center;">{{ $loop->iteration + ($errors->currentPage() - 1) * 10 }}</td>
											<td colspan="1" rowspan="1" style="text-align: center;"><?php echo date("Y-m-d H:i:s", strtotime('+9 hours', strtotime($e->created_at))) ?></td>
											<td colspan="1" rowspan="1" style="text-align: center;">
												<a href="{{ $e->link }}" target="_blank">{{ $e->code }}</a>
											</td>
											<td colspan="1" rowspan="1" style="text-align: center;">{{ $e->recipient }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
					@if (count($errors)) {{ $errors->onEachSide(1)->links('mypage.pagination') }} @endif

				</div>
			</div>
		</div>
	</div>
</section>
@endsection