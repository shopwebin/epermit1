<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\PseudoTypes\True_;

class tradelist_model extends Model{
    use HasFactory;
    public static function add($request){
        $query['paid'] = $request->input('p_status');
        /* = DB::table('trade')->insertGetId([
            'seller_name' => $request->input('seller_name'),
            'state_id' => $request->input('state'),
            'district_id' => $request->input('district'),
            'mandal_id' => $request->input('mandal'),
            'commodity_id' => $request->input('commodity'),
            'quantity_id' => $request->input('quantity'),
            'weight' => $request->input('weight'),
            'a_weight' => $request->input('a_weight'),
            'trade_value' => $request->input('trade_value'),
            'm_fee' => $request->input('m_fee'),
            'amc_id' => $request->input('amc'),
            'p_status' => $query['paid'],
            'trade_type' => $request->input('trade_type'),
            'trader_id' => 4,
        ]);*/
        $query['id'] = DB::table('trade')->insertGetId([
            'seller_name' => $request->input('seller_name'),
            'state_id' => $request->input('state'),
            'district_id' => $request->input('district'),
            'mandal_id' => $request->input('mandal'),
            'amc_id' => $request->input('amc'),
            'p_status' => $query['paid'],
            'trader_id' => 4,
        ]);
        $a='';
        for($i=0;$i<count($request->input('commodity'));$i++){
            $id = DB::table('trade_com')->insertGetId([
                't_id' => $query['id'],
                'com_id' => $request->input('commodity')[$i],
                'q_id' => $request->input('quantity')[$i],
                'weight' => $request->input('weight')[$i],
                'a_weight' => $request->input('weight')[$i],
                'trade_value' => $request->input('trade_value')[$i],
                'm_fee' => $request->input('m_fee')[$i],
                'trade_type' => $request->input('trade_type'),
            ]);
            $a.=",".$id;
        }
        DB::update('update trade set tc_id =? where id=?',[substr($a,1),$query['id']]);
        return $query;
    }

    public function transfer_trader($request)
    {
        $trade = DB::table('trade')->insertGetId([
            'seller_name' => $request->input('name'),
            'ad1' => $request->input('ad1'),
            'ad2' => $request->input('ad2'),
            'state_id' => $request->input('state_id'),
            'district_id' => $request->input('dis_id') ,
            'mandal_id' => $request->input('mdl_id') ,
            // 'commodity_id' => $request->input('commodity_id') ,
            // 'quantity_id' => $request->input('quantity_id') ,
            // 'weight' => $request->input('qtt'),
            // 'a_weight' =>   $request->input('qtt'),
            // 'trade_type'=> $request->input('trade_type'),
            // 'trade_value' => $request->input('trade_value'),
            // 'm_fee' => ($request->input('trade_value')/50),
            'amc_id' => $request->input('amc_id'),
            'p_status' => $request->input('p_status'),
            'trader_id' => $request->input('trader_id'),

        ]);
        return $trade;
    }

    public function convert($request){
        $id = $request->input('id');
        // $dat = DB::select('select trade_com.*,trade.seller_name, trade.state_id, trade.district_id, trade.mandal_id, trade.p_status, trade.trader_id amc_id from trade join trade_com on trade_com.tc_id = trade.tc_id where id = '.$id);
        $dat = DB::select('select trade.seller_name, trade.state_id, trade.district_id, trade.mandal_id, trade.p_status, trade.trader_id,trade.amc_id, trade.tc_id from trade where id = '.$id)[0];
        $d1 = explode(',',$dat->tc_id);
        $mfee = DB::select('select * from mfee where from_date <= ? AND to_date >= ?', [date('Y-m-d H:m:s',time()),date('Y-m-d H:m:s',time())])[0];
        var_dump($d1);
        $intdat = DB::table('trade')->insertGetId([
            'seller_name' => $dat->seller_name ,
            'state_id' => $dat->state_id ,
            'district_id' => $dat->district_id ,
            'mandal_id' => $dat->mandal_id ,
            'amc_id' => $dat->amc_id,
            'p_status' => $dat->p_status,
            'trader_id' => 4,
            'tc_id' => 1
        ]);
        $ntc_id=[];
        foreach($d1 as $i=>$d2){
            $dat1 = DB::select('select * from trade_com where tc_id = '.$d2);
            if(!empty($dat1)){
                // $i=$i-1;
                $dat1 = $dat1[0];
                $w = $request->input('a_weight')[$i];        
                $tv = ($w*$dat1->trade_value)/$dat1->weight;
                $tdc[$i] = DB::table('trade_com')->insertGetId([
                    'com_id' => $dat1->com_id ,
                    'q_id' => $dat1->q_id ,
                    'weight' => $w,
                    'trade_type'=> $request->input('trade_type')[$i],
                    'a_weight' => $w,
                    'trade_value' => $tv,
                    't_id'=>$intdat,
                    'm_fee' => ($tv/100)*$mfee->percent]);
                DB::update('update trade_com set a_weight = a_weight-'.$w.', weight = weight-'.$w.',trade_value=trade_value - '.$tv.',m_fee = m_fee - '.($tv/100)*$mfee->percent.' where tc_id = ?', [$d2]);
                $dat1 = DB::select('select * from trade_com where tc_id = '.$d2);
                if($dat1[0]->weight>0){
                    $ntc_id[]=$d2;
                }else{
                    DB::delete('DELETE FROM `trade_com` WHERE tc_id = '.$d2);
                }
            }
        }
        $tdc1 = implode(',',$tdc);
        DB::update('update trade set trade.tc_id = ? where id = ? ',[$tdc1,$intdat]);
        DB::update('update trade set trade.tc_id = ? where id = ? ',[implode(',',$ntc_id),$id]);
        DB::delete('Delete from `trade` where `tc_id` = ""');
        return  DB::select('select * from trade where id = '.$intdat)[0];
    }

    public function retail_sale($request){
        $id = DB::table('retail')->insertGetId([
            'name' => $request->input('name'),
            'ad1' => $request->input('ad1'),
            'ad2' => $request->input('ad2'),
            'st_id' => $request->input('state_id'),
            'dis_id' => $request->input('dis_id'),
            'mdl_id' => $request->input('mdl_id'),
            'amc_id' => $request->input('amc_id'),
            'invoice' => $request->input('invoice'),
            'trade_id' => $request->input('trade_id'),
            'mobile' => $request->input('mobile'),
            'veh_detail' => $request->input('veh_detail'),
            'trader_id' => 4,
        ]);
        for ($i=0; $i < count($request->input('qtt1')); $i++) { 
            DB::table('multi_permit')->insertGetId([
                'p_id'=>'R'.$id,
                'q_id'=>$request->input('q_id')[$i],
                'weight'=>$request->input('a_weight')[$i],
                'a_weight'=>$request->input('qtt1')[$i],
                'trade_value'=>$request->input('value1')[$i],
                'com_id'=>$request->input('c_id')[$i],
            ]);
            DB::update('update `trade_com` set `a_weight` = `a_weight` - ? where `t_id` = ? and `com_id` = ?',[$request->input('qtt1')[$i],$request->input('trade_id'),$request->input('c_id')[$i]]);
        }
        /*  'rad1' => $request->input('rad1'),
            'rad2' => $request->input('rad2'),
            'a_qty' => $request->input('a_weight'),
            'trade_value' => $request->input('trade_value'),
            'com_name' => $request->input('com_name'),*/
        // DB::update('update trade set a_weight = a_weight - ? where id = ?', [$request->input('a_weight'),$request->input('trade_id')]);
        return $id;
    }

    public function ae_update($request){
        // DB::enableQueryLog();
        return DB::update('update trade set ad1 = ?,ad2 = ?  where id = ?', [$request->input('ad1'),$request->input('ad2'),$request->input('id')]);
        // dd(DB::getQueryLog());
    }

    public static function show(){
        $dat = DB::select('SELECT `trade`.*,`amc`.`name` as `amc` FROM `trade` JOIN `amc` on `amc`.`id` = `trade`.`amc_id` Order By trade.id DESC');
        $i=0;
        foreach($dat as $d){
            $id = $d->id;
            $dat[$i]->sec = DB::table('spermit')->where('t_id',$id)->get('id')->all();
            $dat[$i]->trade_com = DB::select('select `trade_com`.*,`commodity`.`com_name` as `cty`,`quantity`.`qty_name` as `qty` from `trade_com` inner join `commodity` on `commodity`.`com_id` = `trade_com`.`com_id` inner join `quantity` on `quantity`.`id` = `trade_com`.`q_id` where `t_id` = ?',[$d->id]);
            foreach($dat[$i]->trade_com as $tc){
                if($tc->trade_type == 'Sales'){
                    $dat[$i]->trade_type = $tc->trade_type;
                }
                if($tc->a_weight > 0){
                    $dat[$i]->a_weight = 1;
                }
            }
            $i++;
        }
        return $dat;
    }

    public static function find_trader($id){
        $list = DB::table('trader_apply')->where('mobile','like','%'.$id.'%')->orWhere('alternate_mobile','like','%'.$id.'%')->orWhere('lic_no','like','%'.$id.'%')->get('*')->all();
        return $list;
    }

    public function history($request){
        // $dat = DB::table('trade')->join('amc','amc.id','=','trade.amc_id')->join('commodity','commodity.com_id','=','trade.commodity_id')->join('quantity','quantity.id','=','quantity_id')->where('trade.created_at','>=',$request->input('fd'))->where('trade.created_at','<=',$request->input('td'))->get('trade.*,amc.name as amc1,commodity.*,quantity.qty_name')->all();
        
        $dat = DB::select('select trade.*,amc.name as amc1,commodity.*,quantity.qty_name from trade inner join amc on amc.id = trade.amc_id inner join commodity on commodity.com_id = trade.commodity_id inner join quantity on quantity.id = quantity_id where trade.created_at >= ? and trade.created_at <= ? and trader_id = 4 order by trade.created_at asc', [date('Y-m-d',strtotime($request->input('fd'))),date('Y-m-d',strtotime($request->input('td')))]);
        return $dat;
    }

    public function cons_pay(){
        $dat = DB::select('select trade.*,commodity.com_name,quantity.qty_name,amc.name as amc_name from trade join amc on amc.id = trade.amc_id join commodity on commodity.com_id = trade.commodity_id join quantity on quantity.id = trade.quantity_id WHERE 1');
        // $dat = DB::select('select trade.*,commodity.com_name,quantity.qty_name,amc.name as amc_name from trade join amc on amc.id = trade.amc_id join commodity on commodity.com_id = trade.commodity_id join quantity on quantity.id = trade.quantity_id WHERE trade.p_status%2=0');
        return $dat;
    }
}
