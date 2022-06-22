<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class premit_model extends Model
{
    use HasFactory;
    public static function add($request){
        // DB::enableQueryLog(); 
        $id = DB::table('permit')->insertGetId([
            'ad1'=> $request->input('ad1'),
            'ad2' => $request->input('ad2'),
            'state_id' => $request->input('state_id'),
            'name' => $request->input('name'),
            'dis_id' => $request->input('dis_id'),
            'mdl_id' => $request->input('mdl_id'),
            'source' => $request->input('source'),
            'destination' => $request->input('destination'),
            'veh_detail' => $request->input('veh_detail'),
            'valid_from' => $request->input('fd').' '.$request->input('ft'),
            'valid_to' => $request->input('td').' '.$request->input('tt'),
            'mobile'    => $request->input('mobile'),
            'invoice'    => $request->input('invoice'),
            'trader_id' => '4',
            't_id' => $request->input('t_id'),
            'q_details' => $request->input('q_details'),
        ]);
        for($i=0;$i<count($request->input('bal_qty'));$i++){
            $a = DB::table('multi_permit')->insertGetId([
                'com_id'=>$request->input('com_id')[$i],
                'weight'=>$request->input('bal_qty')[$i],
                'a_weight'=>$request->input('a_weight')[$i],
                'trade_value'=>$request->input('value')[$i],
                'q_id'=> $request->input('q_id')[$i],
                'p_id'=>'P'.$id
            ]);
            $b = DB::update('update `trade_com` set `a_weight` = `a_weight` - ? where `t_id` = ? and `com_id` = ?',[$request->input('a_weight')[$i],$request->input('id'),$request->input('com_id')[$i]]);
        }
        DB::select('update `trade` set `permit_id` = '.$id.', `p_status` = `p_status` + 2  where `id` = '.$request->input('t_id'));
        return $id;
        /*$query = DB::getQueryLog();
            var_dump($query)
            $a1 = DB::select('Select `id` from `quantity` where `qty_name` = "'.$request->input('q').'"')[0]->id;
            var_dump($a1);
            'com_id' => $request->input('com_id'),
            'value'    => $request->input('value'),
            'a_weight' => $request->input('a_weight'),*/
    }
    
    public static function add2($request){
        $id = DB::table('spermit')->insertGetId([
            'name' => $request->input('name'),
            'ad1'=> $request->input('ad1'),
            'ad2' => $request->input('ad2'),
            'stt_id' => $request->input('stt_id'),
            'dis_id' => $request->input('dis_id'),
            'mdl_id' => $request->input('mdl_id'),
            't_id' => substr($request->input('t_id'),1),
            // 'c_id' => $request->input('com_id'),
            // 'a_weight' => $request->input('a_weight'),
            'q_details' => $request->input('q_details'),
            'veh_id' => $request->input('veh_id'),
            'mobile' => $request->input('mobile'),
            'from_date' => $request->input('fd').' '.$request->input('ft'),
            'to_date' => $request->input('td').' '.$request->input('tt'),
            'invoice' => $request->input('invoice')
        ]);
        for($i=0;$i<count($request->input('q_id'));$i++){
            if(!empty($request->input('com_id')[$i])){
            $a = DB::table('multi_permit')->insertGetId([
                'com_id'=>$request->input('com_id')[$i],
                'weight'=>$request->input('weight')[$i],
                'a_weight'=>$request->input('a_weight')[$i],
                'trade_value'=>$request->input('trade_val')[$i],
                'q_id'=> $request->input('q_id')[$i],
                'p_id'=>'S'.$id
            ]);
            $b = DB::update('update `trade_com` set `a_weight` = `a_weight` - ? where `t_id` = ? and `com_id` = ?',[$request->input('a_weight')[$i],substr($request->input('t_id'),1),$request->input('com_id')[$i]]);
        }}   
        // DB::select('update `trade` set  `a_weight` = `a_weight` - '.$request->input('a_weight').' where `id` = '.substr($request->input('t_id'),1));
        return $id;
    }

    public function ae_update($request){
        $q = DB::table('aepermit')->insertGetId([
            'name' => $request->input('name'),
            'invoice' => $request->input('invoice'),
            'ad1' => $request->input('add1'),
            'ad2' => $request->input('add2'),
            't_id' => $request->input('id'),
            'state_id' => $request->input('state'),
            'dis_id' => $request->input('district'),
            'mdl_id' => $request->input('mandal'),
            'amc_id' => $request->input('amc_id'),
            'veh_no' => $request->input('veh_no'),
            'phone' => $request->input('phone')
        ]);
        for($i=0;$i<count($request->input('qte'));$i++){
            $a1 = DB::select('Select `id` from `quantity` where `qty_name` = "'.$request->input('q').'"')[0]->id;
            // var_dump($a1);
            $a = DB::table('multi_permit')->insertGetId([
                'com_id'=>$request->input('c_id')[$i],
                'weight'=>$request->input('a_qty')[$i],
                'a_weight'=>$request->input('qte')[$i],
                'trade_value'=>$request->input('sale_value'),
                'q_id'=> $a1,
                'p_id'=>'A'.$q
            ]);
            $b = DB::update('update `trade_com` set `a_weight` = `a_weight` - ? where `t_id` = ? and `com_id` = ?',[$request->input('qte')[$i],$request->input('id'),$request->input('c_id')[$i]]);
        }
        /* 'com_name' => $request->input('c_id'),
            'value' => $request->input('sale_value'),
            'qte' => $request->input('qte'),
            var_dump($request->input('c_id'));
            DB::enableQueryLog();
            dd(DB::getQueryLog());
            $b = DB::table('trade_com')->where('t_id','=',$request->input('id'),'and')->where('com_id','=',$request->input('c_id')[$i])->update([
                'a_weight'=>'`a_weight` - '.$request->input('qte')[$i]
            ]);
            DB::update('update trade set a_weight = ? where id = ?', [,]);*/
        return $q;
    }
    public static function primary($id){
        // $q = DB::table('permit')->join('commodity','permit.com_id','=','commodity.com_id')->join('trade','trade.permit_id','=','permit.id')->where('permit.id','=',$id)->get('permit.*,commodity.*,trade.a_weight as qty');
        // DB::enableQueryLog();
        // dd(DB::getQueryLog());
        $q = DB::select('select permit.*,`trade`.`a_weight` as `qty`,`trade`.`id` as `trade_id` from `permit` inner join `trade` on `trade`.`permit_id` = `permit`.`id` where `permit`.`id` = ?', [$id]);
        $q[0]->tc = DB::select('select trade_com.a_weight as weight1,trade_com.tc_id,`multi_permit`.*,quantity.qty_name,commodity.com_name from `multi_permit` JOIN permit on permit.id = SUBSTRING(multi_permit.p_id,2) RIGHT JOIN trade_com on  (trade_com.t_id = permit.t_id AND trade_com.com_id = multi_permit.com_id) JOIN quantity on quantity.id = `multi_permit`.q_id JOIN commodity on commodity.com_id = `multi_permit`.com_id where p_id = "P'.$id.'"');
        return $q;
        /*->join('districts','districts.id','=','permit.dis_id')
        ->join('mandals','mandals.id','=','permit.mdl_id')*/
    }
    public static function primary1($id){
        $q = DB::select('select permit.*,trader_apply.* from permit  inner join trader_apply on trader_apply.id = permit.trader_id where permit.id = ?', [$id])[0];
        $q->mp = DB::select('select `multi_permit`.*,quantity.qty_name,commodity.com_name from `multi_permit` JOIN quantity on quantity.id = `multi_permit`.q_id JOIN commodity on commodity.com_id = `multi_permit`.com_id where p_id = "P'.$id.'"');
        // var_dump($id);
        return $q;
    }
    public function secondary($id)
    {
        /*$dat = DB::table('spermit')->join('commodity','com_id','=','c_id')
            ->join('trade','t_id','=','trade.id')->join('amc','amc.id','=','amc_id')
            ->where('id',$id)->get('spermit.*,commodity.*,amc.name,trade.weight,trade.a_weight');*/
            // var_dump($dat);
        $dat = DB::select('select spermit.*,amc.name as amc,districts.name as dis_name, mandals.name as mdl_name from spermit inner join trade on spermit.t_id = trade.id inner join amc on amc.id = trade.amc_id join districts on districts.id = spermit.dis_id join mandals on mandals.id = spermit.mdl_id where spermit.id =  ?', [$id])[0];
        $dat->tc = DB::select('select trade_com.a_weight as weight1,trade_com.tc_id,`multi_permit`.*,quantity.qty_name,commodity.com_name from `multi_permit` JOIN spermit on spermit.id = SUBSTRING(multi_permit.p_id,2) RIGHT JOIN trade_com on  (trade_com.t_id = spermit.t_id AND trade_com.com_id = multi_permit.com_id) JOIN quantity on quantity.id = `multi_permit`.q_id JOIN commodity on commodity.com_id = `multi_permit`.com_id where p_id = "S'.$id.'"');
        // $dat->w = DB::select('select trade_com.a_weight from trade_com join spermit on spermit.t_id = trade_com.t_id where spermit.id = ?',[$id]);
        // var_dump($dat->tc);
        return $dat;
    }

    public function secondary1($id)
    {
        $dat = DB::select('select spermit.*,amc.name as amc,districts.name as dis_name, mandals.name as mdl_name, trader_apply.* from spermit  inner join trade on spermit.t_id = trade.id inner join amc on amc.id = trade.amc_id join districts on districts.id = spermit.dis_id join mandals on mandals.id = spermit.mdl_id join trader_apply on trader_apply.id = trade.trader_id  where spermit.id =  ?', [$id])[0];
        $dat->mp = DB::select('select `multi_permit`.*,quantity.qty_name,commodity.com_name from `multi_permit` JOIN quantity on quantity.id = `multi_permit`.q_id JOIN commodity on commodity.com_id = `multi_permit`.com_id where p_id = "S'.$id.'"');
        return $dat;
    }

    public function edit2($request){
        $a = $request->input('a_weight');
        DB::enableQueryLog(); 
        $id = DB::update('update spermit set name = ?,ad1 = ?,ad2 = ?,stt_id = ?,dis_id = ?,mdl_id = ?,q_details = ?,veh_id = ?,mobile = ?,from_date = ?,to_date=? where id = ?',[$request->input('name'),$request->input('ad1'),$request->input('ad2'),$request->input('stt_id'),$request->input('dis_id'),$request->input('mdl_id'),$request->input('q_details'),$request->input('veh_id'),$request->input('mobile'),$request->input('fd').' '.$request->input('ft'),$request->input('td').' '.$request->input('tt'),substr($request->input('id'),1)]);
        for($i=0;$i<count($a);$i++){
            
        }
        DB::update('update trade set a_weight = ? where id = ?', [$request->input('weight')-$a,substr($request->input('t_id'),1)]);
        dd(DB::getQueryLog());
        return 1;
    }

    public static function new($id){
        $quer = DB::select('Select trade.*,amc.name as amc from `trade` join `amc` on `amc`.`id` = `trade`.`amc_id` where `trade`.`id` = '.$id);
        $quer[0]->tc = DB::select('select `trade_com`.*,quantity.qty_name,commodity.* from `trade_com` JOIN quantity on quantity.id = `trade_com`.q_id JOIN commodity on commodity.com_id = `trade_com`.com_id where t_id = "'.$id.'"');
        return $quer;
    }

    public function print_processing($id){
        $q = DB::select('SELECT p_permit.*,trader_apply.*,trade.seller_name as name  FROM `p_permit` JOIN trade on trade.id = p_permit.t_id join trader_apply on trader_apply.id = trade.trader_id where p_permit.id = ?', [$id])[0];
        // var_dump($q);
        $q->mp = DB::select('select `multi_permit`.*,quantity.qty_name,commodity.com_name from `multi_permit` JOIN quantity on quantity.id = `multi_permit`.q_id JOIN commodity on commodity.com_id = `multi_permit`.com_id where p_id = "PP'.$id.'"');
        // $q = DB::select('SELECT p_permit.*,quantity.qty_name,trader.name,trader.lic_no FROM `p_permit` JOIN commodity on commodity.com_id = p_permit.c_id JOIN quantity on quantity.id = p_permit.q_id JOIN trade on trade.id = p_permit.t_id join trader on trader.id = trade.trader_id where p_permit.id = ?', [$id]);
        // var_dump($q);
        return $q;
    }

    public function aqua1($id){
        $q = DB::select('select trader_apply.lic_no,aepermit.*,trader_apply.tname,trader_apply.address,trader_apply.firmname,trader_apply.firmaddress from aepermit join trade on trade.id = aepermit.t_id join trader_apply on trade.trader_id = trader_apply.id where aepermit.id = ?', [$id])[0];
        $q->mp = DB::select('select `multi_permit`.*,quantity.qty_name,commodity.com_name from `multi_permit` JOIN quantity on quantity.id = `multi_permit`.q_id JOIN commodity on commodity.com_id = `multi_permit`.com_id where p_id = "A'.$id.'"');
        
        return $q;
    }

    public function retail_print($id){
        $q = DB::select('select trader_apply.lic_no,retail.*,trader_apply.tname,trader_apply.address,trader_apply.firmname,trader_apply.firmaddress,quantity.qty_name from retail join trade on trade.id = retail.trade_id join trader_apply on trade.trader_id = trader_apply.id join commodity on commodity.com_id = trade.commodity_id join  quantity on quantity.id = commodity.q_id where retail.id = ?', [$id]);
        return $q;
    }

    public function pcancel($id){
        DB::update('Update permit set c_status = 0, c_qty = ?, c_reason = ? where id = ?',[$id['c_qty'],$id['c_reason'],substr($id['id'],1)]);
        return 1;
    }

    public function scancel($id){
        DB::update('Update spermit set c_status = 0 where id = ?',[$id]);
        return 1;
    }

}