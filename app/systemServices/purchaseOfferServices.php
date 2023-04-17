<?php
namespace App\systemServices;

use Illuminate\Support\Facades\DB;
use App\Exceptions\Exception;
use Auth;
use App\Models\DetailPurchaseOffer;
use Illuminate\Http\Request;

class purchaseOfferServices
{
    public function compareOfferDetailsToRequestDetails($details, $offerId){
        $conflict = false;
        $type = '';
        $result = DetailPurchaseOffer::where('purchase_offers_id', $offerId)->get();
        foreach ($details as $_detail) {
            //should be one to one?
            foreach ($result as $_result) {
                if($_result['type']==$_detail['type']){
                    if($_detail['amount'] > $_result['amount']){
                        $conflict = true;
                        $type = $_detail['type'];
                        break;
                    }
                }
               
            }
            if($conflict==true)
            break;    
        }
        if($conflict==true)
            return  ["status"=>false, "message"=>" الكمية المدخلة من المادة". $type." أكبر من العرض نفسه"];
        return  ["status"=>true, "message"=>"الكميات المدخلة صحيحة"];
    }
}