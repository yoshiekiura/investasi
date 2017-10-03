<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 28 Aug 2017 06:57:19 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Product
 * 
 * @property string $id
 * @property int $category_id
 * @property string $name
 * @property int $user_id
 * @property float $raising
 * @property float $external_commitment
 * @property float $raised
 * @property int $equity_offered
 * @property int $days_left
 * @property float $minimum_per_investor
 * @property string description
 * @property int $status_id
 * @property string $created_by
 * @property \Carbon\Carbon $created_on
 * @property string $modified_by
 * @property \Carbon\Carbon $modified_on
 * 
 * @property \App\Models\Status $status
 * @property \Illuminate\Database\Eloquent\Collection $carts
 * @property \Illuminate\Database\Eloquent\Collection $transaction_details
 *
 * @package App\Models
 */
class Product extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	//protected $dateFormat = "U";

	protected $casts = [
		'user_id' => 'int',
		'raising' => 'float',
		'external_commitment' => 'float',
		'raised' => 'float',
		'equity_offered' => 'int',
		'days_left' => 'int',
        'minimum_per_investor' => 'float',
		'status_id' => 'int'
	];
	protected $dates = [
		'created_on',
		'modified_on'
	];

	protected $fillable = [
	    'id',
		'name',
        'category_id',
        'user_id',
        'raising',
        'external_commitment',
        'raised',
        'equity_offered',
        'days_left',
        'minimum_per_investor',
        'description',
		'status_id',
		'created_by',
		'created_on',
		'modified_by',
		'modified_on'
	];

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

    public function user(){
        return $this->belongsTo(\App\Models\User::class);
    }
	public function carts()
	{
		return $this->hasMany(\App\Models\Cart::class);
	}

	public function transaction_details()
	{
		return $this->hasMany(\App\Models\TransactionDetail::class);
	}

    public function category(){
        return $this->belongsTo(\App\Models\Category::class);
    }


    public function product_image(){
	    return $this->hasMany(\App\Models\ProductImage::class);
    }

	public function getRaisingAttribute(){
        return number_format($this->attributes['raising'], 0, ",", ".");
    }

    public function getExternalCommitmentAttribute(){
	    if(!empty($this->attributes['external_commitment'])){
            return number_format($this->attributes['external_commitment'], 0, ",", ".");
        }
    }
    public function getRaisedAttribute(){
	    if(!empty($this->attributes['raised'])){
            return number_format($this->attributes['raised'], 0, ",", ".");
        }
    }

    public function getMinimumPerInvestorAttribute(){
        if(!empty($this->attributes['minimum_per_investor'])){
            return number_format($this->attributes['minimum_per_investor'], 0, ",", ".");
        }
    }
}