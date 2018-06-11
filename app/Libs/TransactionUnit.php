<?php
/**
 * Created by PhpStorm.
 * User: GMG-Developer
 * Date: 18/10/2017
 * Time: 14:48
 */

namespace App\Libs;


use App\Mail\InvoicePembelian;
use App\Mail\PerjanjianLayanan;
use App\Mail\PerjanjianPinjaman;
use App\Models\Cart;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductInstallment;
use App\Models\Transaction;
use App\Models\TransactionWallet;
use App\Models\User;
use Carbon\Carbon;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TransactionUnit
{
    public static function createTransaction($userId, $cartId, $orderId){
        try{

            $cart = Cart::find($cartId);
            $user = User::find($userId);

            $dateTimeNow = Carbon::now('Asia/Jakarta');
            $invoice = Utilities::GenerateInvoice();
            $paymentMethodInt = 1;
            if($cart->payment_method == 'credit_card'){
                $paymentMethodInt = 2;
            }
            else if($cart->payment_method == 'wallet'){
                $walletTemp = (double)$user->getOriginal('wallet_amount');
                $user->wallet_amount = $walletTemp - (double)$cart->getOriginal('invest_amount');
                $user->save();

                $paymentMethodInt = 3;
            }

            $trxCreate = Transaction::create([
                'id'                => Uuid::generate(),
                'user_id'           => $userId,
                'va_number'           => $user->va_acc,
                'invoice'           => $invoice,
                'product_id'           => $cart->product_id,
                'payment_method_id' => $paymentMethodInt,
                'order_id'          => $orderId,
                'total_payment'     => $cart->getOriginal('total_invest_amount'),
                'total_price'       => $cart->getOriginal('invest_amount'),
                'phone'             => $user->phone,
                'admin_fee'         => $cart->getOriginal('admin_fee'),
                'two_day_due_date_flag' => 0,
                'status_id'         => 3,
                'created_on'        => $dateTimeNow->toDateTimeString(),
                'created_by'        => $userId
            ]);

            $productDB = Product::find($cart->product_id);
            $raisedDB = (double) str_replace('.','', $productDB->raised);
            $newRaise = $cart->getOriginal('invest_amount');
            $productDB->raised = $raisedDB + $newRaise;
            $productDB->save();

            // Delete cart
            $cart->delete();

            $payment = PaymentMethod::find($paymentMethodInt);
            $data = array(
                'transaction' => $trxCreate,
                'user'=>$user,
                'paymentMethod' => $payment,
                'product' => $productDB
            );
            SendEmail::SendingEmail('DetailPembayaran', $data);
            return true;
        }
        catch(\Exception $ex){
            Utilities::ExceptionLog('TransactionUnit.php > createTransaction ========> '.$ex);
            return false;
        }
    }

    public static function transactionRejected($trxid){
        try{
            DB::transaction(function() use ($trxid){
                $transaction = Transaction::find($trxid);
                if($transaction->status_id == 10){
                    return false;
                }
                $dateTimeNow = Carbon::now('Asia/Jakarta');

                $transaction->status_id = 10;
                $transaction->modified_on = $dateTimeNow->toDateTimeString();
                $transaction->save();

                //update product data
                $productDB = Product::find($transaction->product_id);
                $raisedDB = (double) str_replace('.','', $productDB->raised);
                $newRaise = (double) str_replace('.','', $transaction->total_price);
                $productDB->raised = $raisedDB - $newRaise;

                $productDB->save();


                return true;
            });
        }
        catch(\Exception $ex){
            Utilities::ExceptionLog('TransactionUnit.php > transactionRejected ========> '.$ex);
        }
        return false;
    }
    public static function transactionAfterVerified($orderid){
        try{
            DB::transaction(function() use ($orderid){
                $transaction = Transaction::where('order_id', $orderid)->first();
                if($transaction->status_id == 5){
                    return false;
                }
                $dateTimeNow = Carbon::now('Asia/Jakarta');

                $transaction->status_id = 5;
                $transaction->two_day_due_date = $dateTimeNow->addDays(2);
                $transaction->modified_on = $dateTimeNow->toDateTimeString();
                $transaction->save();

                //update product data
                $productDB = Product::find($transaction->product_id);
                $raisedDB = (double) str_replace('.','', $productDB->raised);
                $newRaise = (double) str_replace('.','', $transaction->total_price);
    //            $productDB->raised = $raisedDB + $newRaise;

                //checking if fund 100% or not and send email
                $userData = User::find($transaction->user_id);
                $payment = PaymentMethod::find($transaction->payment_method_id);
                $product = Product::find($transaction->product_id);
                $productInstallments = ProductInstallment::where('product_id',$transaction->product_id)->get();

                $data = array(
                    'transaction' => $transaction,
                    'user'=>$userData,
                    'paymentMethod' => $payment,
                    'product' => $product,
                    'productInstallment' => $productInstallments
                );

                $raisingDB = (double) str_replace('.','', $productDB->raising);
                $tempTotal = $raisedDB + $newRaise;
                if($tempTotal >= $raisingDB){
                    $productDB->status_id = 22;
                    Utilities::ExceptionLog("product raising collected  ".$product->name." (".$tempTotal." from ".$raisingDB.")");
    //                SendEmail::SendingEmail('collectedFund', $data);
    //            $perjanjianLayananEmail = new PerjanjianLayanan($payment, $transaction, $product, $userData);
    //            Mail::to($userData->email)->send($perjanjianLayananEmail);
                }
                $productDB->save();

                //Send Email for accepted fund
                SendEmail::SendingEmail('successTransaction', $data);

    //            $invoiceEmail = new InvoicePembelian($payment, $transaction, $product, $userData);
    //            Mail::to($userData->email)->send($invoiceEmail);
    //
    //            $perjanjianPinjamanEmail = new PerjanjianPinjaman($payment, $transaction, $product, $userData);
    //            Mail::to($userData->email)->send($perjanjianPinjamanEmail);

                return true;
            });
        }

        catch(\Exception $ex){
            Utilities::ExceptionLog('TransactionUnit.php > transactionAfterVerified ========> '.$ex);
        }
        return false;
    }

    public static function createTransactionTopUp($userId, $cartId, $orderId){
        try{
            $cart = Cart::find($cartId);

            $user = User::find($userId);

            $dateTimeNow = Carbon::now('Asia/Jakarta');

            $paymentMethodInt = 1;
            if($cart->payment_method == 'credit_card'){
                $paymentMethodInt = 2;
            }

            $trxCreate = TransactionWallet::create([
                'user_id'           => $userId,
                'payment_method_id' => $paymentMethodInt,
                'order_id'          => $orderId,
                'total_payment'     => $cart->getOriginal('total_invest_amount'),
                'amount'            => $cart->getOriginal('invest_amount'),
                'phone'             => $user->phone,
                'admin_fee'         => $cart->getOriginal('admin_fee'),
                'status_id'         => 16,
                'created_at'        => $dateTimeNow->toDateTimeString(),
                'created_by'        => $userId
            ]);

            // Delete cart
            $cart->delete();



            return true;
        }
        catch(\Exception $ex){
            Utilities::ExceptionLog($ex);
        }
        return false;
    }
}