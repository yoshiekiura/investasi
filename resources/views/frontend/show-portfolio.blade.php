@extends('layouts.frontend')

@section('body-content')
    <!-- Banner -->
    <div class="page-banner">
        <div class="container">
            <div class="parallax-mask"></div>
            <div class="section-name">
                <h2>Portfolio</h2>
                <div class="short-text">
                    <h5><a href="{{route('index')}}">Beranda</a>
                        <i class="fa fa-angle-double-right"></i>Portfolio</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="about-page-wrapper">
        <div class="description container">
            <div class="row">
                <div id="tabs" class="panel with-nav-tabs panel-default">
                    <div  id="tabs" class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li {{$isActiveTabPending}}><a href="{{route('portfolio', ['tab' => 'pending'])}}">Pending Transaksi</a></li>
{{--                            <li {{$isActiveTabEquity}}><a href="{{route('portfolio', ['tab' => 'equity'])}}">Saham / Bagi Produk</a></li>--}}
                            <li {{$isActiveTabDebt}}><a href="{{route('portfolio', ['tab' => 'debt'])}}">Portfolio</a></li>
                            <li><a href="#portfolio" data-toggle="tab">Portfolio Breakdown</a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade {{$isActivePending}}" id="pending">

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Pending Transaksi</h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content table-responsive">
                                        <table id="datatable-responsive-pending" class="table table-striped table-bordered dt-responsive nowrap" width="100%" cellspacing="0">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Tanggal Beli</th>
                                                <th class="text-right">Jumlah Pendanaan</th>
                                                <th>Jenis</th>
                                                <th>Status Pembayaran</th>
                                                <th>Update Proyek</th>
                                                <th>Keterangan</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php( $idx = 1 )
                                                @foreach($transactionPending as $trx)
                                                    <tr>
                                                        <td>{{ $idx }}</td>
                                                        <td>
                                                            <a href="{{ route('project-detail', ['id' => $trx->product_id]) }}">{{ $trx->Product->name}}</a>
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($trx->created_on)->format('j F Y')}}</td>
                                                        <td class="text-right">Rp {{ $trx->total_price }}</td>
                                                        <td>{{ $trx->Product->Category->name }}</td>
                                                        <td>{{ $trx->Status->description }}</td>
                                                        <td>{{ $trx->Product->Status->description }}</td>
                                                        @if($trx->payment_method_id == 1)
                                                        <td>Transfer ke nomor rekening {{ $user->va_acc }} <br>
                                                            dengan nama akun : Indofund.id ({{ $user->first_name }} {{$user->last_name}}) </td>
                                                        @else
                                                        <td> Pembayaran dengan {{$trx->payment_method->description}} </td>
                                                        @endif
                                                    </tr>
                                                    @php( $idx++ )
                                                        @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <div style="padding-top:5%;">
                                    <h4>Cara Pembayaran</h4>
                                    <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#mandiri">Bank Mandiri</button>
                                    <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#bank_lain">Bank Lain</button>
                                    <div class="info-block" style="margin: 0; padding: 0;">
                                        <div id="mandiri" class="collapse">
                                            <h3>Pembayaran dengan Bank Mandiri</h3>
                                            <h5>*Pembayaran tidak dapat dilakukan dengan mobile banking gunakan ATM atau Internet banking</h5>
                                            <ol>
                                                <li>1. Masukkan kartu ATM dan PIN MANDIRI Anda</li>
                                                <li>2. Masuk ke menu BAYAR/BELI > menu LAINNYA > menu LAINNYA > menu MULTIPAYMENT</li>
                                                <li>3. Masukkan KODE PERUSAHAAN yaitu 88795</li>
                                                <li>4. Masukkan nomor virtual account Anda: {{$user->va_acc}}</li>
                                                <li>5. Masukkan jumlah nominal yang akan di bayarkan / di transfer</li>
                                                <li>6. Ikuti instruksi untuk menyelesaikan transaksi</li>
                                            </ol>
                                        </div>
                                        <div id="bank_lain" class="collapse">
                                            <h3>Pembayaran dengan Bank Lain</h3>
                                            <ol>
                                                <li>1. Masukkan kartu ATM dan PIN ATM Anda</li>
                                                <li>2. Pilih menu transfer</li>
                                                <li>3. Masukkan kode Bank Mandiri 008</li>
                                                <li>4. Masukkan nomor virtual account Anda: {{$user->va_acc}}</li>
                                                <li>5. Masukkan jumlah nominal yang akan di bayarkan / di transfer</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--<div class="tab-pane fade {{$isActiveEquity}}" id="equity">--}}

                                {{--<div class="x_panel">--}}
                                    {{--<div class="x_title">--}}
                                        {{--<h2>Saham / Bagi Hasil</h2>--}}
                                        {{--<div class="clearfix"></div>--}}
                                    {{--</div>--}}
                                    {{--<div class="x_content table-responsive">--}}
                                        {{--<table id="datatable-responsive-equity" class="table table-striped table-bordered dt-responsive nowrap" width="100%" cellspacing="0">--}}
                                            {{--<thead>--}}
                                            {{--<tr>--}}
                                                {{--<th>No</th>--}}
                                                {{--<th>Nama</th>--}}
                                                {{--<th>Tanggal Beli</th>--}}
                                                {{--<th>Jumlah Investasi</th>--}}
                                                {{--<th>Jenis</th>--}}
                                                {{--<th>Update Terkini</th>--}}
                                            {{--</tr>--}}
                                            {{--</thead>--}}
                                            {{--<tbody>--}}
                                            {{--@php( $idx = 1 )--}}
                                                {{--@foreach($transactionSahamHasil as $trx)--}}
                                                    {{--<tr>--}}
                                                        {{--<td>{{ $idx }}</td>--}}
                                                        {{--<td>--}}
                                                            {{--<a href="{{ route('project-detail', ['id' => $trx->product_id]) }}">{{ $trx->Product->name}}</a>--}}
                                                        {{--</td>--}}
                                                        {{--<td>{{ $trx->created_on }}</td>--}}
                                                        {{--<td>{{ $trx->total_price }}</td>--}}
                                                        {{--<td>{{ $trx->Product->Category->name }}</td>--}}
                                                        {{--<td>{{ $trx->created_on }}</td>--}}
                                                    {{--</tr>--}}
                                                    {{--@php( $idx++ )--}}
                                                        {{--@endforeach--}}
                                            {{--</tbody>--}}
                                        {{--</table>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            <div class="tab-pane fade {{$isActiveDebt}}" id="debt">

                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Produk Konvensional</h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content table-responsive">
                                        <table id="datatable-responsive-debt" class="table table-striped table-bordered dt-responsive nowrap" width="100%" cellspacing="0">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Tanggal Beli</th>
                                                <th class="text-right">Jumlah Pendanaan</th>
                                                <th>Grade/rate</th>
                                                <th>Status Kolektibilitas</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php( $idx = 1 )
                                                @foreach($transactionHutang as $trx)
                                                    <tr>
                                                        <td>{{ $idx }}</td>
                                                        <td>
                                                            <a href="{{ route('project-detail', ['id' => $trx->product_id]) }}">{{ $trx->Product->name}}</a>
                                                        </td>
                                                        <td>{{ $trx->created_on }}</td>
                                                        <td class="text-right">Rp {{ $trx->total_price }}</td>
                                                        <td>{{ $trx->Product->business_class }} / {{ $trx->Product->interest_rate }}%</td>
                                                        @php($color = 'background-color: green')
                                                        <td style="{{$color}}">

                                                        </td>
                                                    </tr>
                                                    @php( $idx++ )
                                                        @endforeach
                                            </tbody>
                                        </table>
                                        <div class="col-md-6" style="text-align: right;">
                                            <p>Keterangan</p>
                                        </div>
                                        <div class="col-md-6">
                                            <img src="{{ URL::asset('frontend_images/keterangan.jpg') }}" style="width: 100%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="portfolio">
                                <h2>Portfolio Breakdown</h2>
                                <h5>Dana : Rp {{ $userDompet }}</h5>
                                <h5>Pendanaan : Rp {{ $userInvestasi }}</h5>
                                <h5>Pendapatan : Rp {{ $userPendapatan }}</h5>

                                <input type="hidden" id="dana" value="{{ $userDompet }}">
                                <input type="hidden" id="investasi" value="{{ $userInvestasi }}">
                                <input type="hidden" id="pendapatan" value="{{$userPendapatan }}">
                                {{--<div id="chart_wrap"><div id="chart_div"></div></div>--}}
                                <div id="chart_wrap"><div id="chart"></div></div>
                                <p id="canvas_size"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        $(window).on("throttledresize", function (event) {
            drawChart();
        });

        function drawChart() {
            var dompetFormatedVal = $('#dana').val();
            var dompetVal = dompetFormatedVal.replace(".", "");

            var investFormatedVal = $('#investasi').val();
            var investVal = investFormatedVal.replace(".", "")

            var pendapatanFormatedVal = $('#pendapatan').val();
            var pendapatanVal = pendapatanFormatedVal.replace(".", "");

            var data = google.visualization.arrayToDataTable([
                ['Task', 'Breakdown'],
                ['Dana', parseInt(dompetVal)],
                ['Investasi', parseInt(investVal)],
                ['Pendapatan', parseInt(pendapatanVal)]
            ]);
            var chart = new google.visualization.PieChart(document.getElementById('chart'));
            var widthWindow = $(window).width();
            if(widthWindow < 480){
                var options = {
                    chartArea : {'left':'0', 'bottom':'10%', 'width': '75%', 'height': '75%'},
                    legend:{alignment :'top'}
                };

                chart.draw(data, options);
            }
            else{
                var options = {
                    chartArea : {'left':'0', 'bottom':'10%', 'width': '100%', 'height': '100%'},
                    legend: {position: 'labeled', alignment:'center'}
                };

                chart.draw(data, options);
            }
        }
    </script>

@endsection