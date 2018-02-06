@extends('layouts.app')

@section('stylesheet')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
    <link rel="stylesheet" href="css/separate/vendor/tags_editor.min.css">
    <link rel="stylesheet" href="css/separate/vendor/bootstrap-select/bootstrap-select.min.css">
    <link rel="stylesheet" href="css/separate/vendor/select2.min.css">
    <link rel="stylesheet" href="css/lib/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="css/lib/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- <header class="section-header">
                <div class="tbl">
                    <div class="tbl-row">
                        <div class="tbl-cell">
                            <h3>Selects</h3>
                            <ol class="breadcrumb breadcrumb-simple">
                                <li><a href="#">StartUI</a></li>
                                <li><a href="#">Forms</a></li>
                                <li class="active">Selects</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </header> -->

            <section class="card">
                <div class="card-block">
                    <!-- <h5 class="with-border m-t-0"></h5> -->
                    <div class="row">
                        <div class="col-md-3">
                            <select class="select2" name="id_dept" id="selectDepartment" onchange="changeDepartment();">
                                <option value="">--Department--</option>
                                @foreach($deparment as $data)
                                    <option value="{{ $data->id_dept }}">{{ strtoupper($data->dept_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="select2" id="selectPersonil" onchange="changeSchedule();">
                                <option value="">--Personil--</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="select2" id="selectTypeTask" onchange="changeSchedule();">
                                <option value="">--Task Type--</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="select2" id="selectSchedule" onchange="changeSchedule();">
                                <option value="">--Schedule--</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>
            <section class="card" id="sectionMonth">
                <div class="card-block">
                    <div class="row">
                        <div class="col-md-3">
                            <select class="select2" id="monthSelectBulan" onchange="monthChangeBulan();">
                                <option value="">--Month--</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="select2" id="monthSelectTahun" onchange="monthChangeTahun();">
                                <option value="">--Tahun--</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>
            <section class="card" id="sectionDate">
                <div class="card-block">
                    <div class="row">
                        <div class="col-md-3">
                            date
                        </div>
                    </div>
                </div>
            </section>
            <section class="card" id="sectionYear">
                <div class="card-block">
                    <div class="row">
                        <div class="col-md-3">
                            oke
                        </div>
                    </div>
                </div>
            </section>
            <div id="div1">
            
            </div>
            <div id="chart_div"></div>
        </div><!--.container-fluid-->
    </div>
@endsection

@section('javascript')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/tether/tether.min.js"></script>
    <script src="js/lib/bootstrap/bootstrap.min.js"></script>
    <script src="js/plugins.js"></script>

    <script src="js/lib/jquery-tag-editor/jquery.caret.min.js"></script>
    <script src="js/lib/jquery-tag-editor/jquery.tag-editor.min.js"></script>
    <script src="js/lib/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="js/lib/select2/select2.full.min.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
    
    <script>
        $(document).ready(function(){
            $('#sectionMonth').hide();
            $('#sectionDate').hide();
            $('#sectionYear').hide();
            $('#datatable').DataTable();
        });
        
        function changeTypeTask(dept) {
            var id_dept = document.getElementById("selectDepartment");
            $.ajax({
                url: '/search_type_task_by_dept',
                type: "post",
                data: {dept:dept,_token: '{{csrf_token()}}'},
                success: function(response)
                {
                    respon = JSON.parse(response);
                    $('option').remove('#optionTypeTask');
                    for (var i = 0; i < respon.length; i++) {
                        $('#selectTypeTask').append('<option value="'+respon[i].code+'" id="optionTypeTask">'+respon[i].nama_tipe.toUpperCase()+'</option>');
                    }
                }
            });
        }

        function changeDepartment() {
            var selectDepartment = document.getElementById("selectDepartment");
            var selectedValue = selectDepartment.options[selectDepartment.selectedIndex].value;
            changeTypeTask(selectedValue);
            $('#sectionMonth').hide();
            $('#sectionDate').hide();
            $('#sectionYear').hide();
            $('option').remove('#optionSchedule');
            $('#selectSchedule').append('<option value="date" id="optionSchedule">DATE</option>');
            $('#selectSchedule').append('<option value="month" id="optionSchedule">MONTH</option>');
            $('#selectSchedule').append('<option value="year" id="optionSchedule">YEAR</option>');
            $('option').remove('#optionTahun');
            $('#selectBulan').val('');

            $.ajax({
                url: '/search_personil_by_category',
                type: "post",
                data: {id_dept:selectedValue,_token: '{{csrf_token()}}'},
                success: function(response)
                {
                    respon = JSON.parse(response);
                    $('option').remove('#optionPersonil');
                    for (var i = 0; i < respon.length; i++) {
                        $('#selectPersonil').append('<option value="'+respon[i].uid+'" id="optionPersonil">'+respon[i].name.toUpperCase()+'</option>');
                    }
                }
            });
        }

        function changeSchedule() {
            var dept        = document.getElementById("selectDepartment").value;
            var personil    = document.getElementById("selectPersonil").value;
            var typetask    = document.getElementById("selectTypeTask").value;
            var schedule    = document.getElementById("selectSchedule").value;
            $('option').remove('#optionTahun');
            if(dept != "" && personil != "" && typetask != "" && schedule != ""){
                if(schedule == "month"){
                    monthChangeMonth();
                    $('#sectionMonth').show(100);
                    $('#sectionYear').hide();
                    $('#sectionDate').hide();
                } else if(schedule == "date"){
                    $('#sectionMonth').hide();
                    $('#sectionYear').hide();
                    $('#sectionDate').show(100);
                } else if(schedule == "year"){
                    $('#sectionMonth').hide();
                    $('#sectionYear').show(100);
                    $('#sectionDate').hide();
                }
            } else {
                $('#sectionMonth').hide();
                $('#sectionDate').hide();
                $('#sectionYear').hide();
            }
        }

        function monthChangeMonth(){
            var dept        = document.getElementById("selectDepartment").value;
            var personil    = document.getElementById("selectPersonil").value;
            var typetask    = document.getElementById("selectTypeTask").value;

            $.ajax({
                url: '/search_month_by_dpt',
                type: "post",
                data: {
                    id_dept:dept,
                    uid:personil,
                    tipe_task:typetask,
                    _token: '{{csrf_token()}}'},
                success: function(response)
                {
                    alert(response);
                    respon = JSON.parse(response);
                    $('option').remove('#optionBulan');
                    $('option').remove('#optionTahun');
                    for (var i = 0; i < respon.length; i++) {
                        if (respon[i].tanggal == '01') {
                            respon[i].namatanggal = 'January'
                        } else if (respon[i].tanggal == '02') {
                            respon[i].namatanggal = 'February'
                        } else if (respon[i].tanggal == '03') {
                            respon[i].namatanggal = 'Maret'
                        } else if (respon[i].tanggal == '04') {
                            respon[i].namatanggal = 'April'
                        } else if (respon[i].tanggal == '05') {
                            respon[i].namatanggal = 'Mey'
                        } else if (respon[i].tanggal == '06') {
                            respon[i].namatanggal = 'Juny'
                        } else if (respon[i].tanggal == '07') {
                            respon[i].namatanggal = 'July'
                        } else if (respon[i].tanggal == '08') {
                            respon[i].namatanggal = 'August'
                        } else if (respon[i].tanggal == '09') {
                            respon[i].namatanggal = 'September'
                        } else if (respon[i].tanggal == '10') {
                            respon[i].namatanggal = 'Oktober';
                        } else if (respon[i].tanggal == '11') {
                            respon[i].namatanggal = 'November'
                        } else if (respon[i].tanggal == '12') {
                            respon[i].namatanggal = 'Desember'
                        }
                        $('#monthSelectBulan').append('<option value="'+respon[i].tanggal+'" id="optionBulan">'+respon[i].namatanggal+'</option>');
                    }
                }
            });
        }

        function monthChangeBulan() {
            var dept        = document.getElementById("selectDepartment").value;
            var personil    = document.getElementById("selectPersonil").value;
            var typetask    = document.getElementById("selectTypeTask").value;
            var bulan       = document.getElementById("monthSelectBulan").value;

            $.ajax({
                url: '/search_year_by_dptb',
                type: "post",
                data: {
                    id_dept:dept,
                    uid:personil,
                    tipe_task:typetask,
                    bulan:bulan,
                    _token: '{{csrf_token()}}'
                },
                success: function(response)
                {
                    respon = JSON.parse(response);
                    $('option').remove('#optionTahun');
                    for (var i = 0; i < respon.length; i++) {
                        $('#monthSelectTahun').append('<option value="'+respon[i].tahun+'" id="optionTahun">'+respon[i].tahun+'</option>');
                    }
                }
            });
        }

        function monthChangeTahun() {
            var select = document.getElementById("selectDepartment");
            var id_dept = select.options[select.selectedIndex].value;
            var select = document.getElementById("selectPersonil");
            var uid = select.options[select.selectedIndex].value;
            var select = document.getElementById("selectTypeTask");
            var tipe_task = select.options[select.selectedIndex].value;
            var select = document.getElementById("monthSelectBulan");
            var bulan = select.options[select.selectedIndex].value;
            var select = document.getElementById("monthSelectTahun");
            var tahun = select.options[select.selectedIndex].value;

            $.ajax({
                url: '/search_by_month',
                type: "post",
                data: {
                    id_dept:id_dept,
                    uid:uid,
                    bulan:bulan,
                    tipe_task:tipe_task,
                    tahun:tahun,
                    _token: '{{csrf_token()}}'
                },
                success: function(response)
                {
                    respon = JSON.parse(response);
                    no =1;
                    html = "<table><td>Total</td><td>Question</td><td>Uid</td><td>Date</td></tr>";
                    for (var i = 0; i < respon.length; i++) {
                        html+="<tr><td>"+respon[i].jumlah+"</td><td>"+respon[i].question+"</td><td>"+respon[i].uid+"</td><td>"+respon[i].tanggal+"</td></tr>";
                    }
                    html+="</table>";
                    alert(html);
                    $("#div1").html(html);
                    google.charts.load('current', {'packages':['line', 'corechart']});
                      google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {

                      var button = document.getElementById('change-chart');
                      var chartDiv = document.getElementById('chart_div');

                      var data = new google.visualization.DataTable();
                      data.addColumn('date', 'Month');
                      data.addColumn('number', "Average Temperature");
                      data.addColumn('number', "Average Hours of Daylight");

                      data.addRows([
                        [new Date(2014, 0),  -.5,  5.7],
                        [new Date(2014, 1),   .4,  8.7],
                        [new Date(2014, 2),   .5,   12],
                        [new Date(2014, 3),  2.9, 15.3],
                        [new Date(2014, 4),  6.3, 18.6],
                        [new Date(2014, 5),    9, 20.9],
                        [new Date(2014, 6), 10.6, 19.8],
                        [new Date(2014, 7), 10.3, 16.6],
                        [new Date(2014, 8),  7.4, 13.3],
                        [new Date(2014, 9),  4.4,  9.9],
                        [new Date(2014, 10), 1.1,  6.6],
                        [new Date(2014, 11), -.2,  4.5]
                      ]);

                      var materialOptions = {
                        chart: {
                          title: 'Average Temperatures and Daylight in Iceland Throughout the Year'
                        },
                        width: 900,
                        height: 500,
                        series: {
                          // Gives each series an axis name that matches the Y-axis below.
                          0: {axis: 'Temps'},
                          1: {axis: 'Daylight'}
                        },
                        axes: {
                          // Adds labels to each axis; they don't have to match the axis names.
                          y: {
                            Temps: {label: 'Temps (Celsius)'},
                            Daylight: {label: 'Daylight'}
                          }
                        }
                      };

                      var classicOptions = {
                        title: 'Average Temperatures and Daylight in Iceland Throughout the Year',
                        width: 900,
                        height: 500,
                        // Gives each series an axis that matches the vAxes number below.
                        series: {
                          0: {targetAxisIndex: 0},
                          1: {targetAxisIndex: 1}
                        },
                        vAxes: {
                          // Adds titles to each axis.
                          0: {title: 'Temps (Celsius)'},
                          1: {title: 'Daylight'}
                        },
                        hAxis: {
                          ticks: [new Date(2014, 0), new Date(2014, 1), new Date(2014, 2), new Date(2014, 3),
                                  new Date(2014, 4),  new Date(2014, 5), new Date(2014, 6), new Date(2014, 7),
                                  new Date(2014, 8), new Date(2014, 9), new Date(2014, 10), new Date(2014, 11)
                                 ]
                        },
                        vAxis: {
                          viewWindow: {
                            max: 30
                          }
                        }
                      };

                      function drawMaterialChart() {
                        var materialChart = new google.charts.Line(chartDiv);
                        materialChart.draw(data, materialOptions);
                        button.innerText = 'Change to Classic';
                        button.onclick = drawClassicChart;
                      }

                      function drawClassicChart() {
                        var classicChart = new google.visualization.LineChart(chartDiv);
                        classicChart.draw(data, classicOptions);
                        button.innerText = 'Change to Material';
                        button.onclick = drawMaterialChart;
                      }

                      drawMaterialChart();

                    }
                    // $('option').remove('#optionPersonil');
                    // for (var i = 0; i < respon.length; i++) {
                    //     $('#selectPersonil').append('<option value="'+respon[i].uid+'" id="optionPersonil">'+respon[i].name.toUpperCase()+'</option>');
                    // }
                }
            });
        }

        // function month(){
        //     $.ajax({
        //         url: '/search_type_task_by_dept',
        //         type: "post",
        //         data: {dept:dept,_token: '{{csrf_token()}}'},
        //         success: function(response)
        //         {
        //             respon = JSON.parse(response);
        //             $('option').remove('#optionTypeTask');
        //             for (var i = 0; i < respon.length; i++) {
        //                 $('#selectTypeTask').append('<option value="'+respon[i].uid+'" id="optionTypeTask">'+respon[i].nama_tipe.toUpperCase()+'</option>');
        //             }
        //         }
        //     });
        // }

        // $("#check-all").click(function () {
        //     $(".data-check").prop('checked', $(this).prop('checked'));
        // });

        // $("#btnAdd").click(function () {
        //     $('.addModal').modal();
        //     $('option').remove('#optionSubCategory');
        //     $('option').remove('#optionProduct');
        //     $('#selectCategory').val("");
        // });

        // function bulk_delete()
        // {
        //     var list_id = [];
        //     $(".data-check:checked").each(function() {
        //         list_id.push(this.value);
        //     });
        //     if(list_id.length > 0)
        //     {
        //         if(confirm('Akan Menghapus '+list_id.length+' Data ?'))
        //         {
        //             $.ajax({
        //                 url: '/delete_brand',
        //                 type: "post",
        //                 data: {id:list_id,_token: '{{csrf_token()}}'},
        //                 success: function(response)
        //                 {
        //                     location.href= "/brand";
        //                     alert('Delete Success');
        //                 }
        //             });
        //         }
        //     }
        //     else
        //     {
        //         alert('Tidak Ada Data Yang di Pilih');
        //     }
        // }

        // function search_by_id()
        // {
        //     var list_id = [];
        //     $(".data-check:checked").each(function() {
        //         list_id.push(this.value);
        //     });
        //     if(list_id.length > 0)
        //     {
        //         $.ajax({
        //             url: '/search_brand_by_id',
        //             type: "post",
        //             data: {id:list_id[0],_token: '{{csrf_token()}}'},
        //             success: function(response)
        //             {
        //                 respon = JSON.parse(response);   
        //                 $('#selectSubCategoryEdit').css( 'pointer-events', 'none' );
        //                 $('#selectProductEdit').css( 'pointer-events', 'none' );
        //                 $('#id_edit').val(respon[0].id_product);
        //                 $('#selectCategoryEdit').val(respon[0].id_category);
        //                 $('#selectSubCategoryEdit').append('<option value="'+respon[0].id_sub_category+'" id="optionSubCategory" selected>'+respon[0].title_sub_category+'</option>');
        //                 $('#selectProductEdit').append('<option value="'+respon[0].id_product+'" id="optionProduct" selected>'+respon[0].title_product+'</option>');
        //                 $('#title_edit').val(respon[0].title_brand);
        //                 $('#description_edit').val(respon[0].description);
        //                 $('#EditModal').modal();
        //             }
        //         });
        //     }
        //     else
        //     {
        //         alert('Tidak Ada Data Yang di Pilih');
        //     }
        // }

        // function changeSelectCategory() {
        //     var selectCategory = document.getElementById("selectCategory");
        //     var selectedValue = selectCategory.options[selectCategory.selectedIndex].value;
        //     $.ajax({
        //         url: '/search_sub_category_by_id_category',
        //         type: "post",
        //         data: {id:selectedValue,_token: '{{csrf_token()}}'},
        //         success: function(response)
        //         {
        //             respon = JSON.parse(response);
        //             $('option').remove('#optionSubCategory');
        //             $('option').remove('#optionProduct');
        //             for (var i = 0; i < respon.length; i++) {
        //                 $('#selectSubCategory').append('<option value="'+respon[i].id+'" id="optionSubCategory">'+respon[i].title+'</option>');
        //             }
        //         }
        //     });
        // }

        // function changeSelectSubCategory() {
        //     var selectSubCategory = document.getElementById("selectSubCategory");
        //     var selectedValue = selectSubCategory.options[selectSubCategory.selectedIndex].value;
        //     $.ajax({
        //         url: '/search_product_by_id_sub_category',
        //         type: "post",
        //         data: {id:selectedValue,_token: '{{csrf_token()}}'},
        //         success: function(response)
        //         {
        //             respon = JSON.parse(response);
        //             $('option').remove('#optionProduct');
        //             for (var i = 0; i < respon.length; i++) {
        //                 $('#selectProduct').append('<option value="'+respon[i].id+'" id="optionProduct">'+respon[i].title+'</option>');
        //             }
        //         }
        //     });
        // }

        // function changeSelectCategoryEdit() {
        //     var selectCategoryEdit = document.getElementById("selectCategoryEdit");
        //     var selectedValue = selectCategoryEdit.options[selectCategoryEdit.selectedIndex].value;
        //     $.ajax({
        //         url: '/search_sub_category_by_id_category',
        //         type: "post",
        //         data: {id:selectedValue,_token: '{{csrf_token()}}'},
        //         success: function(response)
        //         {
        //             respon = JSON.parse(response);
        //             $('option').remove('#optionSubCategory');
        //             $('option').remove('#optionProduct');
        //             $('#selectSubCategoryEdit').css( 'pointer-events', '' );
        //             $('#selectProductEdit').css( 'pointer-events', '' );
        //             for (var i = 0; i < respon.length; i++) {
        //                 $('#selectSubCategoryEdit').append('<option value="'+respon[i].id+'" id="optionSubCategory">'+respon[i].title+'</option>');
        //             }
        //         }
        //     });
        // }

        // function changeSelectSubCategoryEdit() {
        //     var selectSubCategoryEdit = document.getElementById("selectSubCategoryEdit");
        //     var selectedValue = selectSubCategoryEdit.options[selectSubCategoryEdit.selectedIndex].value;
        //     $.ajax({
        //         url: '/search_product_by_id_sub_category',
        //         type: "post",
        //         data: {id:selectedValue,_token: '{{csrf_token()}}'},
        //         success: function(response)
        //         {
        //             respon = JSON.parse(response);
        //             $('option').remove('#optionProduct');
        //             for (var i = 0; i < respon.length; i++) {
        //                 $('#selectProductEdit').append('<option value="'+respon[i].id+'" id="optionProduct">'+respon[i].title+'</option>');
        //             }
        //         }
        //     });
        // }

        // function changeFuncEdit() {
        //     var selectBoxEdit = document.getElementById("selectBoxEdit");
        //     var selectedValue = selectBoxEdit.options[selectBoxEdit.selectedIndex].value;
        //     $.ajax({
        //         url: '/search_sub_category_by_id_category',
        //         type: "post",
        //         data: {id:selectedValue,_token: '{{csrf_token()}}'},
        //         success: function(response)
        //         {
        //             respon = JSON.parse(response);
        //             $('#selectBoxSubEdit').css( 'pointer-events', '' );
        //             $('option').remove('#optionsub');
        //             for (var i = 0; i < respon.length; i++) {
        //                 $('#selectBoxSubEdit').append('<option value="'+respon[i].id+'" id="optionsub">'+respon[i].title+'</option>');
        //             }
        //         }
        //     });
        // }
                // function add()
                // {
                //     var merk_mobil  = $("#merk_mobil").val();
                //     var notes       = $("#notes").val();
                //     var label       = $("#label").val();
                //     if(notes == "" || merk_mobil == "" || label == ""){
                //         alert('Inputan Jangan Ada Yang Kosong');
                //     } else {
                //         $.ajax({
                //             url: '/tambah_merk_mobil',
                //             type: "post",
                //             data: {
                //                 Merk_mobil:merk_mobil,
                //                 Notes:notes,
                //                 Label:label,
                //                 _token: '{{csrf_token()}}'
                //             },
                //             success: function(response)
                //             {
                //                 location.href= "/merk_mobil";
                //                 alert(merk_mobil+' Berhasil di Tambah');
                //             }
                //         });
                //     }
                // }


                // function simpanEdit()
                // {
                //     var merk_mobil  = $("#merk_mobil_edit").val();
                //     var notes       = $("#notes_edit").val();
                //     var label       = $("#label_edit").val();
                //     var id          = $("#id_edit").val();
                //     if(notes == "" || merk_mobil == "" || label == ""){
                //         alert('Inputan Jangan Ada Yang Kosong');
                //     } else {
                //         $.ajax({
                //             url: '/simpan_edit_merk_mobil',
                //             type: "post",
                //             data: {
                //                 Id:id,
                //                 Merk_mobil:merk_mobil,
                //                 Notes:notes,
                //                 Label:label,
                //                 _token: '{{csrf_token()}}'
                //             },
                //             success: function(response)
                //             {
                //                 location.href= "/merk_mobil";
                //                 alert(merk_mobil+' Berhasil di Edit');
                //             }
                //         });
                //     }
                // }
            </script>
@endsection