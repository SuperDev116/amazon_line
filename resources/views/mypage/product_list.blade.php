@extends("layouts.mypage")

@php
	$user = Auth::user();
@endphp

@section('content')
<section class="content">
	<div class="block-header">
		<div class="row">
			<div class="col-lg-7 col-md-6 col-sm-12">
				<h2>商品一覧</h2>
				<ul class="breadcrumb">
					<li class="breadcrumb-item"><a href="/"><i class="zmdi zmdi-home"></i> Amazon</a></li>
					<li class="breadcrumb-item active">商品一覧</li>
				</ul>
				<button class="btn btn-primary btn-icon mobile_menu" type="button"><i
						class="zmdi zmdi-sort-amount-desc"></i></button>
			</div>
			<div class="col-lg-5 col-md-6 col-sm-12">
				<!-- <a style="color: white;" href="{{ route('csv_down') }}"
					class="btn btn-primary btn-icon float-right"><i class="zmdi zmdi-download"></i></a> -->
				<button class="btn btn-danger btn-icon float-right" type="button"
					onclick="deleteProduct('{{ $user->id }}')"><i class="zmdi zmdi-delete"></i></button>
				<button class="btn btn-primary btn-icon float-right" type="button" id="save-file"><i
						class="zmdi zmdi-download"></i></button>
				<!-- <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i
						class="zmdi zmdi-arrow-right"></i></button> -->
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
										<th colspan="1" rowspan="1" style="text-align: center;">画像</th>
										<th colspan="1" rowspan="1" style="text-align: center;">ASIN</th>
										<th colspan="1" rowspan="1" style="text-align: right;">登録価格</th>
										<th colspan="1" rowspan="1" style="text-align: right;">現在価格</th>
										<th colspan="1" rowspan="1" style="text-align: center;">下落％<br />（目標価格）</th>
										<!-- <th colspan="1" rowspan="1" data-breakpoints="md sm xs">再通知間隔</th> -->
										<th colspan="1" rowspan="1" data-breakpoints="md sm xs"
											style="text-align: center;">Keepa URL</th>
										<th colspan="1" rowspan="1" style="text-align: center;">操作</th>
									</tr>
								</thead>
								<tbody>
									@foreach($products as $product)
										<tr data-id="{{ $product->id }}">
											<td colspan="1" rowspan="1" style="text-align: center;">
												{{ $loop->iteration + ($products->currentPage() - 1) * 10 }}
											</td>
											<td colspan="1" rowspan="1" style="text-align: center;">
												<a href="{{ $product->url }}" target="_blank"><img
														src="{{ $product->image }}" title="{{ $product->url }}"
														alt="image" /></a>
											</td>
											<td colspan="1" rowspan="1" style="text-align: center;">
												{{ $product->asin }}</td>
											<td colspan="1" rowspan="1"
												style="color: #e47297; font-size: 16px; text-align: right;">
												¥{{ number_format($product->reg_price) }}</td>
											<td colspan="1" rowspan="1"
												style="color: #5cc5cd; font-size: 16px; text-align: right;">
												¥{{ number_format($product->price) }}</td>
											<td colspan="1" rowspan="1" style="text-align: center;">
												{{ $product->pro }}%<br />(¥{{ number_format($product->tar_price) }})
											</td>
											<!-- <td colspan="1" rowspan="1">{{ $user->interval }}</td> -->
											<td colspan="1" rowspan="1" style="text-align: center;">
												<a href="{{ 'https://keepa.com/#!product/5-' . $product->asin }}"
													target="_blank"><img style="width: 200px;"
														title="{{ 'https://keepa.com/#!product/5-' . $product->asin }}"
														src={{ 'https://graph.keepa.com/pricehistory.png?asin=' . $product->asin . '&domain=co.jp&salesrank=1' }} /></a>
											</td>
											<!-- <td colspan="1" rowspan="1">
												<a href="{{ 'https://keepa.com/#!product/5-' . $product->asin }}" target="_blank">{{ 'https://keepa.com/#!product/5-' . $product->asin }}</a>
											</td> -->
											<td colspan="1" rowspan="1" style="text-align: center;"><button
													class="btn btn-danger btn-icon" type="button"
													onclick="removeProduct('{{ $product->id }}')"><i
														class="zmdi zmdi-delete"></i></button></td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>

					@if (count($products)) {{ $products->onEachSide(1)->links('mypage.pagination') }} @endif
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.0/FileSaver.min.js" integrity="sha512-csNcFYJniKjJxRWRV1R7fvnXrycHP6qDR21mgz1ZP55xY5d+aHLfo9/FcGDQLfn2IfngbAHd8LdfsagcCqgTcQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script>
		$(document).ready(function () {
			setInterval(() => {
				location.href = '{{ route("list_product") }}';
			}, 300000);
		});

		function deleteProduct(id) {
			if (!window.confirm('データを本当に削除しますか？')) {
				return;
			}
			toastr.info('少々お待ちください。')
			$.ajax({
				url: '{{ route("delete_product") }}',
				type: 'post',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: {
					user_id: id
				},
				success: function () {
					toastr.success('データが正常に削除されました。');
					setTimeout(() => {
						location.href = '{{ route("list_product") }}';
					}, 3000);
				}
			})
		}

		function removeProduct(id) {
			if (!window.confirm('データを本当に削除しますか？')) {
				return;
			}
			$.ajax({
				url: '{{ route("remove_product") }}',
				type: 'post',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: {
					product_id: id
				},
				success: function () {
					toastr.success('データが正常に削除されました。');
					$('tr[data-id=' + id + ']').remove();
				}
			})
		}

		$('#save-file').on('click', function(e) {
			$.ajax({
				url: "{{route('csv_down')}}",
				success: async function(res) {
					// console.log(res);
					// var BOM = "\uFEFF";
					// var csvData = BOM + res;
					// var blob = new Blob([csvData], { type: "text/csv;charset=utf-8" });
					// console.log(blob);
					// saveAs(blob, "myCsv.csv");

					// create a new handle
					const newHandle = await window.showSaveFilePicker();
					
					// create a FileSystemWritableFileStream to write to
					const writableStream = await newHandle.createWritable();

					// write our file
					await writableStream.write("\uFEFF" + res);
		
					// close the file and write the contents to disk.
					await writableStream.close();
				}
			});
		});

	</script>
@endpush
