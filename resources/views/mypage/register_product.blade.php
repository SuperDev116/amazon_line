@extends("layouts.mypage")

@php
	$user = Auth::user();
@endphp

@section('content')
<section class="content">
	<div class="block-header">
		<div class="row">
			<div class="col-lg-7 col-md-6 col-sm-12">
				<h2>商品登録</h2>
				<ul class="breadcrumb">
					<li class="breadcrumb-item"><a href="/"><i class="zmdi zmdi-home"></i> Amazon</a></li>
					<li class="breadcrumb-item active">商品登録</li>
				</ul>
				<button class="btn btn-primary btn-icon mobile_menu" type="button"><i
						class="zmdi zmdi-sort-amount-desc"></i></button>
			</div>
			<div class="col-lg-5 col-md-6 col-sm-12">
				<button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i
						class="zmdi zmdi-arrow-right"></i></button>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row clearfix">
			<div class="col-lg-3 col-md-12"></div>
			<div class="col-lg-6 col-md-12">
				<div class="card widget_2 big_icon">
					<div class="body">
						<div class="table-responsive">
							<table class="table table-hover product_item_list c_table theme-color mb-0">
								<thead>
									<tr>
										<th style="width: 30%;">【価格改定】</th>
										<th style="width: 70%;"></th>
									</tr>
								</thead>
								<tbody> 
									<tr>
										<td>秒数:</td>
										<td><input type="number" class="form-control" placeholder="1200" id="interval" name="interval" value="{{ $user->interval }}" onchange="changeValue(event);" /></td>
									</tr>
									<tr>
										<td>1-5000円(%):</td>
										<td><input type="number" class="form-control" placeholder="50" id="five" name="five" min="0" max="100" value="{{ $user->five }}" onchange="changeValue(event);" /></td>
									</tr>
									<tr>
										<td>5001-10000円(%):</td>
										<td><input type="number" class="form-control" placeholder="50" id="ten" name="ten" min="0" max="100" value="{{ $user->ten }}" onchange="changeValue(event);" /></td>
									</tr>
									<tr>
										<td>10001-20000円(%):</td>
										<td><input type="number" class="form-control" placeholder="50" id="twenty" name="twenty" min="0" max="100" value="{{ $user->twenty }}" onchange="changeValue(event);" /></td>
									</tr>
									<tr>
										<td>20001-30000円(%):</td>
										<td><input type="number" class="form-control" placeholder="50" id="thirty" name="thirty" min="0" max="100" value="{{ $user->thirty }}" onchange="changeValue(event);" /></td>
									</tr>
									<tr>
										<td>30001円以上(%):</td>
										<td><input type="number" class="form-control" placeholder="50" id="over" name="over" min="0" max="100" value="{{ $user->over }}" onchange="changeValue(event);" /></td>
									</tr>
									<tr>
										<td>CSVファイル:</td>
										<td><input type="file" class="form-control" style="cursor: pointer;" placeholder="CSVファイルを選択してください。" id="csv" name="csv" /></td>
									</tr>
								</tbody>
							</table>
						</div>

						<div class="col-lg-12 mt-4" id="register-status" style="display: block;">
							<div class="row">
								<div class="col text-center">
									<span id="progress-num">0</span> 件/ <span id="total-num">0</span> 件
								</div>
								<div class="col text-center">
									<span id="round">0</span>回目
								</div>
							</div>
							<div class="row mt-4">
								<div class="progress col-12" id="count">
									<div class="progress-bar progress-bar-animated bg-danger progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; height: 20px;" id="progress">
										<span id="percent-num">0%</span>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-12 mt-4" id="track-status" style="display: none;">
							<div class="row">
								<div class="col text-center">
									<span id="progress-num1">0</span> 件/ <span id="total-num1">0</span> 件
								</div>
								<div class="col text-center">
									<span id="round1">0</span>回目
								</div>
							</div>
							<div class="row mt-4">
								<div class="progress col-12" id="count1">
									<div class="progress-bar progress-bar-animated bg-info progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; height: 20px;" id="progress1">
										<span id="percent-num1">0%</span>
									</div>
								</div>
							</div>
						</div>

						<div class="col-12 text-center mt-4">
							<button type="button" id="register" class="btn btn-raised btn-primary waves-effect" onclick="register()">登 録</button>
							<button type="button" id="stop" class="btn btn-raised btn-warning waves-effect" onclick="stop()">停 止</button>
							<button type="button" id="restart" class="btn btn-raised btn-warning waves-effect" onclick="restart()">起 動</button>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-12"></div>
		</div>
	</div>
</section>
@endsection

@push('scripts')
<script>
		var scanInterval = setInterval(scan, 5000);
		$(document).ready(function () {

			var user = <?php echo $user; ?>;
			if (user.is_reg == 1) {
				$('#register-status').css('display', 'block');
				$('#track-status').css('display', 'none');

				$('#total-num').html(user.len);
				$('#round').html(0);
				$('#progress-num').html(user.reg_num);

				$('#csv').attr('disabled', true);
				$('#register').attr('disabled', true);
			} else if (user.is_reg == 0) {
				$('#register-status').css('display', 'none');
				$('#track-status').css('display', 'block');

				$('#total-num1').html(user.len);
				$('#round1').html(user.round);
				$('#progress-num1').html(user.trk_num);

				$('#csv').attr('disabled', false);
				$('#register').attr('disabled', false);
			}
		});

		function scan() {
			$.ajax({
				url: "{{ route('scan') }}",
				type: "get",
				success: function(response) {
					if (response.is_reg == 1) {
						$('#register-status').css('display', 'block');
						$('#track-status').css('display', 'none');

						$('#total-num').html(response.len);
						$('#progress-num').html(response.reg_num);
						var percent = Math.floor(response.reg_num / response.len * 100);
						$('#percent-num').html(percent + '%');
						$('#progress').attr('aria-valuenow', percent);
						$('#progress').css('width', percent + '%');
						$('#round').html(0);
					} else if (response.is_reg == 0) {
						$('#register-status').css('display', 'none');
						$('#track-status').css('display', 'block');

						$('#total-num1').html(response.len);
						$('#progress-num1').html(response.trk_num);
						var percent = Math.floor(response.trk_num / response.len * 100);
						$('#percent-num1').html(percent + '%');
						$('#progress1').attr('aria-valuenow', percent);
						$('#progress1').css('width', percent + '%');
						$('#round1').html(response.round);
					}

					if (percent == 100) {
						if (response.round == 0) {
							toastr.success('正常に登録されました。');
							location.href = "{{ route('list_product') }}";
						}
					}
				}
			})
		}

		const register = async () => {
			var user = <?php echo $user; ?>;
			// if (user.is_permitted == 0) {
			// 	toastr.error('管理者からの許可をお待ちください。');
			// 	return;
			// }

			clearInterval(scanInterval);
			await $.ajax({
				url: "{{ route('stop') }}",
				type: "get",
				success: function () {
					$('#round1').html(0);
					$('#progress-num1').html(0);
					$('#percent-num1').html('0%');
					$('#progress1').attr('aria-valuenow', );
					$('#progress1').css('width', '0%');
					$('#total-num1').html(0);
				}
			});

			if (csvFile === undefined) {
				toastr.error('CSVファイルを選択してください。');
				return;
			}

			let postData = {
				interval: $('input[name="interval"]').val(),
				five: $('input[name="five"]').val(),
				ten: $('input[name="ten"]').val(),
				twenty: $('input[name="twenty"]').val(),
				thirty: $('input[name="thirty"]').val(),
				over: $('input[name="over"]').val(),
				file_name: csvFile.name,
				len: newCsvResult.length,
			};

			// first save user exhibition setting
			await $.ajax({
				url: 'save_user_setting',
				type: 'post',
				headers: {
					"X-CSRF-TOKEN" : $('meta[name="csrf-token"]').attr("content")
				},
				data: {
					exData: JSON.stringify(postData)
				},
				success: function () {
					scanInterval = setInterval(scan, 5000);
					toastr.info('商品登録を開始します。');

					$('#register-status').css('display', 'block');
					$('#track-status').css('display', 'none');
			
					$('#csv').attr('disabled', true);
					$('#register').attr('disabled', true);
				}
			});

			// then start registering products with ASIN code
			postData = {
				user_id: '{{ Auth::user()->id }}',
				codes: newCsvResult
			};

			$.ajax({
				url: "https://xs206760.xsrv.jp/fmproxy/api/v1/amazon/getInfo",
				type: "post",
				data: {
					asin: JSON.stringify(postData)
				},
			});
		};

		const stop = () => {
			clearInterval(scanInterval);
			$.ajax({
				url: "{{ route('stop') }}",
				type: "get",
				success: function () {
					toastr.info('サーバーが停止されました。');
					$('#round1').html(0);
					$('#progress-num1').html(0);
					$('#percent-num1').html('0%');
					$('#progress1').attr('aria-valuenow', );
					$('#progress1').css('width', '0%');
					$('#total-num1').html(0);
				}
			});
		};

		const restart = () => {
			scanInterval = setInterval(scan, 5000);
			$.ajax({
				url: "{{ route('restart') }}",
				type: "get",
				success: function () {
					toastr.info('サーバーが起動されました。');
				}
			});
		};

		var newCsvResult, csvFile;
		// select csv file and convert its content into an array of ASIN code
		$('body').on('change', '#csv', function(e) {
			clearInterval(scanInterval);

			csvFile = e.target.files[0];
			localStorage.setItem('csv', csvFile);
			
			newCsvResult = [];

			$('#progress-num').html('0');
			$('#percent-num').html('0%');
			$('#progress').attr('aria-valuenow', 0);
			$('#progress').css('width', '0%');

			var ext = $('#csv').val().split(".").pop().toLowerCase();
			if ($.inArray(ext, ["csv", "xlsx"]) === -1) {
				toastr.error('CSV、XLSXファイルを選択してください。');
				return false;
			}
			
			if (csvFile !== undefined) {
				reader = new FileReader();
				reader.onload = function (e) {
					$('#count').css('visibility', 'visible');
					csvResult = e.target.result.split(/\n/);

					for (let i = 1, len = csvResult.length - 1; i < len; i++) {
						let productInfo = {};
						let code = csvResult[i].split('\r')[0].split(',');
						productInfo.asin = code[0];
						productInfo.price = code[1];
						productInfo.pro = code[2];
						productInfo.interval = code[4];
						newCsvResult.push(productInfo);
					}
					// if (newCsvResult[0].asin == 'ASIN' || newCsvResult[0].asin == 'Asin' || newCsvResult[0].asin == 'asin') { newCsvResult.shift(); }

					$('#total-num').html(newCsvResult.length);
				}
				reader.readAsText(csvFile);
			}
		});

		const changeValue = (e) => {
			$.ajax({
				url: "{{ route('change_value') }}",
				type: "get",
				data: {
					col: e.target.id,
					value: e.target.value
				},
				success: function() {
					toastr.success("正常に設定しました。");
				}
			})
		};
	</script>
@endpush