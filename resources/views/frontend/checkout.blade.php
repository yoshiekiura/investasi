@extends('layouts.frontend')

@section('body-content')
    <!-- Banner -->
    <div class="page-banner">
        <div class="container">
            <div class="parallax-mask"></div>
            <div class="section-name">
                <h2>Pembayaran</h2>
                <div class="short-text">
                    <h5><a href="{{route('index')}}">Beranda</a>
                        <i class="fa fa-angle-double-right"></i><a href="{{ route('project-list', ['tab' => 'debt']) }}">Daftar Investasi</a>
                        <i class="fa fa-angle-double-right"></i>Pembayaran
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Causes Wrapper -->
    <div class="causes-page-wrapper single-cause">
        <div class="container" style="margin-bottom: 20px;">
            <div class="row cause">
                <div class="col-md-10 col-md-offset-1">
                    <div class="meta">
                        <h2>Danai Sekarang</h2>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        @if(\Illuminate\Support\Facades\Session::has('message'))
                            <div class="alert alert-success alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>{{ \Illuminate\Support\Facades\Session::get('message') }}</strong>
                            </div>
                        @endif
                        <form class="donation-form">
                            <h3>Masukkan Jumlah Pendanaan <br>{{$product->name}}</h3>
                            <h4>Sisa Pengumpulan Dana = Rp {{$remainingStr}}</h4>

                            <div class="field col-sm-12 text-center error-div" style="display: none;">
                                <span class="help-block" style="color: red;">Nominal harus kelipatan dari Rp {{number_format(env('PAYMENT_MULTIPLE'), 0, ",", ".")}} dan minimal Rp {{number_format(env('PAYMENT_MINIMUM'), 0, ",", ".")}}</span>
                            </div>
                            <div class="field col-sm-12 text-center error-div-wallet" style="display: none;">
                                <span class="help-block" style="color: red;">Saldo Anda tidak mencukupi</span>
                            </div>
                            <div class="field col-sm-12 text-center error-div-wallet-use" style="display: none;">
                                <span class="help-block" style="color: red;">Saldo harus lebih kecil atau sama dengan Pendanaan</span>
                            </div>
                            <div class="field col-sm-12 text-center error-remaining" style="display: none;">
                                <span class="help-block" style="color: red;">Nominal Harus lebih kecil dari sisa pendanaan</span>
                            </div>
                            <div class="field col-sm-12 price-format checkout-border">
                                <h5>Nominal</h5>
                                <input id="amount" type="text" name="amount" />
                                <h5>Minimum Pendanaan : Rp {{number_format(env('PAYMENT_MINIMUM'), 0, ",", ".")}}</h5>
                                <h5>Kelipatan : Rp {{number_format(env('PAYMENT_MULTIPLE'), 0, ",", ".")}}</h5>
                                <h5 style="visibility: hidden">Simulasi pendapatan per-bulan : <span id="income">Rp 0</span></h5>
                            </div>
                            {{--<div class="field col-sm-12 checkout-border">--}}
                                {{--<h5>Simulasi pendapatan per-bulan : <span id="income">Rp 0</span></h5>--}}
                            {{--</div>--}}

                            <div class="field col-sm-12 checkout-border">
                                <h5>Pilihan Sumber Dana</h5>
                                @if($userData->wallet_amount != 0)
                                    <h5>Saldo Anda Rp {{$userData->wallet_amount}}</h5>
                                    <div class="radio-inputs">
                                        <input type="radio" id="payment-3" name="payment" value="bank_transfer">
                                        <label for="payment-3">
                                            <span></span>Transfer bank
                                        </label>
                                        <input type="radio" id="payment-1" name="payment" value="wallet" checked>
                                        <label for="payment-1">
                                            <span></span>Saldo Saya
                                        </label>
                                        {{--<input type="radio" id="payment-2" name="payment" value="credit_card">--}}
                                        {{--<label for="payment-2"><span></span>Kartu Kredit</label>--}}
                                    </div>
                                    <div class="amount_wallet_transfer">
                                        <input id="amount_wallet_transfer" type="hidden" name="amount_wallet_transfer" placeholder="Penggunaan Saldo"/>
                                    </div>
                                @else
                                    <div class="radio-inputs">
                                        {{--<input type="radio" id="payment-2" name="payment" value="credit_card" checked>--}}
                                        {{--<label for="payment-2"><span></span>Kartu Kredit</label>--}}
                                        <input type="radio" id="payment-3" name="payment" value="bank_transfer" checked>
                                        <label for="payment-3"><span></span>Transfer bank</label>
                                    </div>
                                @endif
                            </div>
                            @if($notCompletedData == 1)

                                <input id="notCompletedData" value="{{$notCompletedData}}" type="hidden">
                                <input id="wallet" value="{{$userData->wallet_amount}}" type="hidden">
                                <input id="remaining" value="{{$remaining}}" type="hidden">
                                <input id="paymentMinim" value="{{env('PAYMENT_MINIMUM')}}" type="hidden">
                                <input id="paymentMultiple" value="{{env('PAYMENT_MULTIPLE')}}" type="hidden">
                                <input id="raised" value="{{$product->raised}}" type="hidden">
                                <input id="getProductInstallment" value="{{$getProductInstallment}}" type="hidden">

                                <div class="field col-sm-12">
                                    <div class="col-sm-12">
                                        <h5 style="color:red;">
                                            Catatan<br>Harap membaca Product Disclosure Statement dari tiap produk, terutama yang berhubungan dengan aturan dan resiko berinvestasi.
                                        </h5>
                                        <h4 style="margin-top: -25px;">
                                            <br>
                                            {{-- <a href="{{route('download', ['filename' => $product->prospectus_path])}}">Download Product Disclosure Statement</a>--}}
                                            <a href="{{$product->prospectus_path}}" target="_blank" style="cursor: pointer;"><span>Product Disclosure Statement</span></a>
                                        </h4>
                                    </div>
                                </div>
                                <div class="field col-sm-12 text-left" >
                                    @if(auth()->check())
                                        {{--<button type="button" class="btn btn-big btn-solid" onclick="modalCheckout()"><i class="fa fa-archive"></i><span>Bayar</span></button>--}}
                                        {{--<button type="button" data-toggle="modal" data-target="#readProspectusModal" data-backdrop="static" data-keyboard="false" class="btn btn-big btn-solid "><i class="fa fa-archive"></i><span>Bayar</span></button>--}}
                                        <button type="button" onclick="modalCheckout()" class="btn btn-big btn-solid" style="margin-top:0;"><span>Proses Sekarang</span></button>
                                    @else
                                        <button type="button" data-toggle="modal" data-target="#loginModal" class="btn btn-big btn-solid" style="margin-top:0;"><span>Proses Sekarang</span></button>
                                    @endif
                                </div>
                            @else
                                <div class="field col-sm-12 text-left">
                                    @if(auth()->check())
                                        {{--<button type="button" class="btn btn-big btn-solid" onclick="modalCheckout()"><i class="fa fa-archive"></i><span>Bayar</span></button>--}}
                                        {{--<button type="button" data-toggle="modal" data-target="#readProspectusModal" data-backdrop="static" data-keyboard="false" class="btn btn-big btn-solid "><i class="fa fa-archive"></i><span>Bayar</span></button>--}}

                                        <a href="{{ route('setting-data', ['id' => $product->id]) }}" class="btn btn-big btn-solid" style="margin-top:0;"><span>Proses Sekarang</span></a>
                                    @else
                                        <button type="button" data-toggle="modal" data-target="#loginModal" class="btn btn-big btn-solid" style="margin-top:0;"><span>Proses Sekarang</span></button>
                                    @endif
                                </div>
                            @endif
                        </form>
                    </div>
                    <div id="faq-checkout" class="col-md-6 col-sm-12">
                        <span>
                            <b>Indofund.id bukan lembaga investasi</b>
                            <br><br>
                            Kami adalah sebuah portal yang mempertemukan pihak yang membutuhkan
                            bantuan pendanaan dengan pihak yang mau memberikan bantuan akan pendanaan.
                            <br>
                            Indofund.id dengan standarisasi yang baku telah melakukan studi kelayaran
                            pada semua pihak yang mendaftarkan kebutuhan pendanaannya di portal kami.
                            Namun bukan berarti risiko akan proyek maupun pekerjaan yang dilakukan oleh
                            pihak yang menerima modal menjadi bebas risiko kepada pihak yang memberikan bantuan.
                            <br><br>
                            <a>Pelajari disini bagaimana kami mengelola risiko </a>
                            <br><br>
                            Hal-hal yang sering ditanyakan
                        </span>
                        <ul>
                            <li>Bagaimana saya mendanai proyek di indofund.id</li>
                            <li>Biaya transaksi apa yang muncul ketika saya mendanai</li>
                            <li>Kapan saya mendapatkan keuntungan</li>
                            <li>Apakah orang lain mengetahui saya mendanai</li>
                            <li>Bila dana tidak terkumpul apa yang terjadi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal prospectus read -->
    <div class="modal fade" id="readProspectusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true" style="padding-top:10%;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Perhatian</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="font-16" style="color:red;">
                                Catatan<br>Harap membaca Product Disclosure Statement dari tiap produk, terutama yang berhubungan dengan aturan dan resiko berinvestasi.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-error" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-solid" data-dismiss="modal" onclick="modalCheckout()"><i class="fa fa-archive"></i><span> Lanjutkan</span></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Checkout -->
    <div class="modal fade" id="modal-checkout-confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true" style="padding-top:10%;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {!! Form::open(array('action' => array('Frontend\PaymentController@pay', $product->id), 'method' => 'POST', 'role' => 'form')) !!}
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Konfirmasi Checkout</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                            <p>Metode pembayaran via <span id="checkout-payment-method">Kartu Kredit</span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-offset-2 col-md-offset-2 col-lg-4 col-md-4 col-sm-12">
                            <label>Jumlah Investasi:</label>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <span id="checkout-invest-amount" ></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-offset-2 col-md-offset-2 col-lg-4 col-md-4 col-sm-12">
                            <label>Biaya Admin:</label>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <span id="checkout-admin-fee"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-offset-2 col-md-offset-2 col-lg-4 col-md-4 col-sm-12">
                            <label>Total:</label>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <span id="checkout-total-invest"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label>
                                <input type="checkbox" name="check1" id="check1" onclick="check()">
                                Saya telah membaca dan memahami isi dari prospektus produk ini
                                {{--(<a href="{{route('download', ['filename' => $product->prospectus_path])}}"><span>Download Product Disclosure Statement</span></a>),--}}
                                (<a href="{{$product->prospectus_path}}" target="_blank" style="cursor: pointer;"><span>Product Disclosure Statement</span></a>),
                                dan saya telah menyetujui
                                <a target="_blank" href="{{route('perjanjian-layanan')}}">perjanjian layanan pinjam meminjam berbasis teknologi</a> dari indofund.id
                                {{--<a target="_blank" href="{{route('term-condition')}}">syarat dan ketentuan</a> dari indofund.id--}}

                            </label>
                        </div>
                    </div>
                    {{ Form::hidden('checkout-invest-amount-input', '', array('id' => 'checkout-invest-amount-input')) }}
                    {{ Form::hidden('checkout-admin-fee-input', '', array('id' => 'checkout-admin-fee-input')) }}
                    {{ Form::hidden('checkout-payment-method-input', '', array('id' => 'checkout-payment-method-input')) }}
                    {{ Form::hidden('checkout-wallet-used', '', array('id' => 'checkout-wallet-used')) }}

                    {{ Form::hidden('checkout-notCompletedData', '', array('id' => 'checkout-notCompletedData')) }}
                    {{ Form::hidden('checkout-name-KTP', '', array('id' => 'checkout-name-KTP')) }}
                    {{ Form::hidden('checkout-KTP', '', array('id' => 'checkout-KTP')) }}
                    {{ Form::hidden('checkout-citizen', '', array('id' => 'checkout-citizen')) }}
                    {{ Form::hidden('checkout-address', '', array('id' => 'checkout-address')) }}
                    {{ Form::hidden('checkout-city', '', array('id' => 'checkout-city')) }}
                    {{ Form::hidden('checkout-province', '', array('id' => 'checkout-province')) }}
                    {{ Form::hidden('checkout-zip', '', array('id' => 'checkout-zip')) }}
                </div>
                <div class="modal-footer" style="text-align: center;">
                    <button type="button" class="btn btn-error" data-dismiss="modal">Tutup</button>
                    <button id="submit" type="submit" class="btn btn-solid" disabled>Bayar Sekarang</button>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- Go to www.addthis.com/dashboard to customize your tools -->
    {{--<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-59f6e999249e3f1c"></script>--}}

    <script type="text/javascript">
        function check(){

            if(document.getElementById("check1").checked){
                document.getElementById("submit").disabled = false;
            }
            else if(document.getElementById("check1").checked == false){
                document.getElementById("submit").disabled = true;
            }
        }
    </script>
@endsection

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ URL::asset('css/datatable/jquery.dataTables.min.css') }}">
    <style>
        .checkout-border{
            border-bottom: 2px solid #ff7a00;
            padding-bottom: 6%;
        }
    </style>
@endsection

@section('scripts')
    @parent
    <script src="{{ URL::asset('js/frontend/bootstrap-datetimepicker.min.js') }}"></script>
    <script>
        $( document ).ready(function() {
            var paidAmount = $('#getProductInstallment').val();
            var editedPaidAmount = RemoveDot(paidAmount);
            $('#getProductInstallment').val(editedPaidAmount);

            var raised = $('#raised').val();
            var editedRaised = RemoveDot(raised);
            $('#raised').val(editedRaised);

        });
        numberFormat = new AutoNumeric('.amount_wallet_transfer > input', {
            decimalCharacter: ',',
            digitGroupSeparator: '.',
            decimalPlaces: 0,
            modifyValueOnWheel: false
        });
        $('#amount').on('input',function(){
            var invest = $("#amount").val();
            while(true)
                if(invest.includes('.')){
                    invest = invest.replace('.', '');
                }
                else{
                    break;
                }
            var paidAmount = $('#getProductInstallment').val();
            var raised = $('#raised').val();

            var userGetTemp = ((invest*100) / raised).toFixed(2);

            var userGetFinal =  Math.round((userGetTemp * paidAmount) / 100);
            // alert(invest + " | "  + paidAmount + " | " + raised + " | " + userGetTemp + " | " + userGetFinal)

            $('#income').html(addCommas(userGetFinal));
        });

        function modalCheckout(){
            // Set invest amount
            // var invest = $("input[name=amount]:checked").val();
            var invest = $("#amount").val();
            var walletUsed = $("#amount_wallet_transfer").val();
            var remaining = $("#remaining").val();

            while(true)
                if(invest.includes('.')){
                    invest = invest.replace('.', '');
                }
                else{
                    break;
                }
            while(true)
                if(walletUsed.includes('.')){
                    walletUsed = walletUsed.replace('.', '');
                }
                else{
                    break;
                }

            var notComplete = $("#notCompletedData").val();
            $("#checkout-notCompletedData").val(notComplete);
            if(notComplete === '0'){
                var KTP = $("#KTP").val();
                $("#checkout-KTP").val(KTP);
                var citizen = $("#citizen").val();
                $("#checkout-citizen").val(citizen);
                var address = $("#address-home").val();
                $("#checkout-address").val(address);
                var city = $("#city").val();
                $("#checkout-city").val(city);
                var province = $("#province").val();
                $("#checkout-province").val(province);
                var zip = $("#zip").val();
                $("#checkout-zip").val(zip);
            }

            //check remaining
            if(parseInt(invest)  < parseInt(walletUsed)){
                $(".error-div").hide();
                $(".error-remaining").hide();
                $(".error-div-wallet").hide();
                $(".error-div-wallet-use").hide();
                $(".error-div-wallet-use").show();

            }
            else if(parseInt(invest)  > parseInt(remaining)){
                $(".error-div").hide();
                $(".error-remaining").hide();
                $(".error-div-wallet").hide();
                $(".error-div-wallet-use").hide();
                $(".error-remaining").show();
            }
            else{
                var payment_minim = parseInt($("#paymentMinim").val());
                var payment_multiple = parseInt($("#paymentMultiple").val());
                //check amount
                if(invest%payment_multiple === 0 && invest >= payment_minim){
                    $(".error-div").hide();
                    $(".error-remaining").hide();
                    $(".error-div-wallet").hide();
                    $(".error-div-wallet-use").hide();

                    var investStr = addCommas(invest);
                    $("#checkout-invest-amount").html(investStr);
                    $("#checkout-invest-amount-input").val(invest);

                    var adminFee = 0;

                    // Set admin fee
                    var payment = $("input[name=payment]:checked").val();
                    $("#checkout-payment-method-input").val(payment);
                    if(payment === "credit_card"){
                        var investFeeInt = (parseInt(invest) / 100) * 3;
                        $("#checkout-admin-fee-input").val(investFeeInt);
                        adminFee += investFeeInt;
                        investStr = addCommas(investFeeInt);
                        $("#checkout-admin-fee").html(investStr);

                        $("#checkout-payment-method").html("Kartu Kredit");

                        // Set total invest amount
                        var total = parseInt(invest) + adminFee;
                        $("#checkout-total-invest").html(addCommas(total));

                        $("#modal-checkout-confirm").modal();
                    }
                    else if(payment === "bank_transfer"){
                        // adminFee += 4000;
                        $("#checkout-admin-fee-input").val(0);
                        $("#checkout-admin-fee").html("GRATIS");
                        $("#checkout-payment-method").html("Transfer Bank");

                        // Set total invest amount
                        var total = parseInt(invest) + adminFee;
                        $("#checkout-total-invest").html(addCommas(total));

                        $("#modal-checkout-confirm").modal();
                    }
                    else if(payment === "wallet"){

                        var walletVal = $("#wallet").val();

                        while(true)
                            if(walletVal.includes('.')){
                                walletVal = walletVal.replace('.', '');
                            }
                            else{
                                break;
                            }
                        if(parseInt(walletVal) < parseInt(invest)){
                            $(".error-div-wallet").show();
                        }
                        else{
                            $("#checkout-wallet-used").val(walletUsed);

                            $("#checkout-admin-fee-input").val(0);
                            $("#checkout-admin-fee").html("GRATIS");
                            $("#checkout-payment-method").html("Saldo Saya");

                            // Set total invest amount
                            var total = parseInt(invest) + adminFee;
                            $("#checkout-total-invest").html(addCommas(total));

                            $("#modal-checkout-confirm").modal();
                        }
                    }

                }
                else{
                    $(".error-div").hide();
                    $(".error-remaining").hide();
                    $(".error-div-wallet").hide();
                    $(".error-div-wallet-use").hide();

                    $(".error-div").show();
                }
            }
        }

    </script>
@endsection