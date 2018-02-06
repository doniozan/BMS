<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;
use App\Personil;
use App\Typetask;
use App\Itemdijawab;
use DB;

class ChecklistperformanceController extends Controller
{
    function index(){
    	// $data = DB::table('tb_task as a')
     //    ->select(DB::raw('count(e.jawaban) as tot'),'e.uid','g.dept_name')
     //    ->join('tb_task_detail as b', 'a.code', '=', 'b.code_task')
     //    ->join('tb_item_check as c', 'b.code', '=', 'c.id_point')
     //    ->join('tb_item_dijawab as e', 'e.id_item', '=', 'c.id_item')
     //    ->join('tb_user as f', 'f.uid', '=', 'e.uid')
     //    ->join('tb_dept as g', 'g.id_dept', '=', 'f.category')
     //    ->groupBy('e.uid')
     //    ->get();
     //    return compact('data');
    	return view('checklistPerformance.index')
    	->with('deparment',Department::all());
    }

    function search_personil_by_category(Request $request){
    	return json_encode(Personil::where('category',$request->id_dept)->get());
    }

    function search_type_task_by_dept(Request $request){
    	return json_encode(Typetask::where('dept',$request->dept)->get());
    }


    function search_month_by_dpt(Request $request){
        $data = DB::table('tb_task as a')
        ->select(DB::raw("month(e.tanggal) as tanggal,month(e.tanggal) as namatanggal"))
        ->join('tb_task_detail as b', 'a.code', '=', 'b.code_task')
        ->join('tb_item_check as c', 'b.code', '=', 'c.id_point')
        ->join('tb_item_dijawab as e', 'e.id_item', '=', 'c.id_item')
        ->join('tb_user as f', 'f.uid', '=', 'e.uid')
        ->join('tb_dept as g', 'g.id_dept', '=', 'f.category')
        // ->where('f.uid','like','%%')
        ->where('f.uid',$request->uid)
        ->where('g.id_dept',$request->id_dept)
        ->where('a.tipe_task',$request->tipe_task)
        ->groupBy(DB::raw("MONTH(e.tanggal)"))
        ->get();

        return json_encode($data);
    }   

    function search_year_by_dptb(Request $request){
        $data = DB::table('tb_task as a')
        ->select(DB::raw("YEAR(e.tanggal) as tahun"),'e.tanggal')
        ->join('tb_task_detail as b', 'a.code', '=', 'b.code_task')
        ->join('tb_item_check as c', 'b.code', '=', 'c.id_point')
        ->join('tb_item_dijawab as e', 'e.id_item', '=', 'c.id_item')
        ->join('tb_user as f', 'f.uid', '=', 'e.uid')
        ->join('tb_dept as g', 'g.id_dept', '=', 'f.category')
        ->where('f.uid',$request->uid)
        ->where('g.id_dept',$request->id_dept)
        ->where('a.tipe_task',$request->tipe_task)
        ->whereMonth('e.tanggal',$request->bulan)
        ->groupBy(DB::raw("YEAR(e.tanggal)"))
        ->get();

        return json_encode($data);
    }     

    function search_by_month(Request $request){
        $data = DB::table('tb_task as a')
        ->select(DB::raw("count(DAY(e.tanggal)) as jumlah"),'c.question','f.uid as uid','e.tanggal')
        ->join('tb_task_detail as b', 'a.code', '=', 'b.code_task')
        ->join('tb_item_check as c', 'b.code', '=', 'c.id_point')
        ->join('tb_item_dijawab as e', 'e.id_item', '=', 'c.id_item')
        ->join('tb_user as f', 'f.uid', '=', 'e.uid')
        ->join('tb_dept as g', 'g.id_dept', '=', 'f.category')
        ->where('f.uid',$request->uid)
        ->where('g.id_dept',$request->id_dept)
        ->where('a.tipe_task',$request->tipe_task)
        ->whereMonth('e.tanggal',$request->bulan)
        ->whereYear('e.tanggal',$request->tahun)
        ->groupBy(DB::raw("DAY(e.tanggal)"))
        ->get();
        return json_encode($data);
    }

    // function search_item_dijawab_by_bulan_groupBy(Request $request){
    //     return json_encode(DB::table('tb_item_dijawab')
		  //       ->select(DB::raw("YEAR(tanggal) as tang"))
		  //       ->groupBy("tang")
		  //       ->whereMonth('tanggal',$request->bulan)
		  //       ->get());
    // }
}