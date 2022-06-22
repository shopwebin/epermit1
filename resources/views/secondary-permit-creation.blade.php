@include("includes/header");
<div class="container-fluid bdy dashboard">
    <div class="py-5 section">
        <div class="card">
            <h5 class="card-header">
                <span>Secondary Permit Creation</span>
                <div class="btn-grp">
                    <btn onclick="window.history.back()" title="Back"><i class="priya-arrow-left"></i></btn>
                    <btn onclick="" title="Dashboard"><i class="priya-dashboard"></i></btn>
                    <btn onclick="helpModal('#add-dist-help')" title="Help"><i class="priya-info"></i></btn>
                    <btn onclick="" title="History"><i class="priya-history"></i></btn>
                </div>
            </h5>
            <div class="card-body">
                <!--div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Type of Permit <span class="text-danger">*</span></label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="customRadioInline1" name="customRadioInline1" class="custom-control-input">
                                <label class="custom-control-label" for="customRadioInline1">Primary</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="customRadioInline2" name="customRadioInline1" class="custom-control-input">
                                <label class="custom-control-label" for="customRadioInline2">Secondary</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Address <span class="text-danger">*</span></label>
                            <input type="" name="amc_name" class="form-control pri-form" value="@isset($dat->amc){{$dat->amc}}@endisset" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Purchase Quantity <span class="text-danger">*</span></label>
                            <input type="" name="weight" class="form-control pri-form" value="@isset($dat->weight){{$dat->weight}}@endisset" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Balance Quantity<span class="text-danger">*</span></label>
                            <input type="" name="a_weight" class="form-control pri-form" value="@if(isset($dat->aqty)){{$dat->aqty + $dat->a_weight}}@else{{$dat->a_weight}}@endif" readonly>
                        </div>
                    </div>
                </div>-->
                <script>
                    /*$(document).ready(function() {
                        {{-- @if($dat->c_status)
                            window.location.href = '{{url("cancel-permit")}}/S{{ $dat->id }}';
                        @endif --}}
                    });*/
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $(document).ready(function() {
                        $(".state").change(function() {
                            $.get("{{url('district')}}/" + $(this).val(), function(result) {
                                var str;
                                for (var i = 0; i < result.length; i++) {
                                    str += '<option value="' + result[i].id + '" @isset($dat->dis_id) @if($dat->dis_id == "' + result[i].id + '"){{ "selected" }}@endif @endisset>' + result[i].name + '</option>';
                                }
                                $('.district').html(str);
                            });
                        });
                    });
                    $(document).ready(function() {
                        $(".district").change(function() {
                            $.get("{{url('mandal')}}/" + $(this).val(), function(result) {
                                var str;
                                for (var i = 0; i < result.length; i++) {
                                    str += '<option value="' + result[i].id + '" @isset($dat->mdl_id) @if($dat->mdl_id == "' + result[i].id + '"){{ "selected" }}@endif @endisset>' + result[i].name + '</option>';
                                }
                                $('.mandal').html(str);
                            });
                        });
                    });                    
                    $(document).ready(function() {      
                        $(".fd").change(function() {
                            var w = $(".fd").val();
                            var h = $(".td").val();
                            // alert(w);
                            var strDate = (new Date()).toISOString().split('T')[0];
                            // alert(strDate);
                            if(w < strDate){
                                alert("old date permit can't be Created");
                                $(".fd").val('');
                            }
                            if((!empty(h))&&(w>=h)){
                                alert("Atleast 1 day  permit can be Created");
                            }
                        });
                    });
                    $(document).ready(function() {      
                        $(".td").change(function() {
                            var w = $(".fd").val();
                            var h = $(".td").val();
                            var strDate = (new Date()).toISOString().split('T')[0];
                            // alert(w);
                            // alert(strDate);
                            if((w < strDate)||(w >= h)){
                                alert("Atleast 1 day permit can be Created");
                                $(".td").val();
                            }
                        });
                    });
                    /*$(document).ready(function() {
                        $(".a_weight").change(function() {
                            var w = $('.a_weight').val();
                            @if(isset($dat->aqty))
                            if(w > {{ $dat->a_weight + $dat->aqty }}){
                            @else   if(w > {{ $dat->a_weight }}0){    @endif
                                alert("Kindly enter value below or equal available quantity");
                                $('.a_weight').val("{{ $dat->a_weight }}");
                            } else {
                                $('.trade_val').val(w * 1{{--$dat->amt--}});
                            }
                        });
                    });*/
                    $(document).ready(function() {
                        $('#cancelbtn').click(function(e){
                            e.preventDefault();
                            $('.c_qty1').val($('.c_qty').val());
                            $('.c_reason1').val($('.c_reason').val());
                            $('.c_id1').val('P'+$('.id').val());
                            $('.c_tid1').val($('input[name=t_id]').val());
                            $('.form2').submit();
                        });
                    });
                    $(document).ready(function() {
                        $('.c_qty').change(function(){
                            var a = $('.a_weight').val();
                            var c = $('.c_qty').val(); 
                            if(c - a >0){
                                alert('Kindly enter number less or equal to the Available Quantity');
                                $('.c_qty').val(0);
                            }
                            $('.c_r').css('display','block');
                        });
                    });
                </script>
                <h5 class="mt-3">Consignee Details</h5>
                @if(isset($dat->veh_id))
                <form action="{{url('secondary/permit/edit')}}" method="post">
                @else
                <form action="{{url('secondary/permit/add')}}" method="post">
                @endif
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input type="" name="name" class="form-control pri-form" value="@isset($dat->name){{$dat->name}}@endisset">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Address Line 1 <span class="text-danger">*</span></label>
                                <input type="" name="ad1" class="form-control pri-form"  value="@isset($dat){{$dat->ad1}}@endisset">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Address Line 2 <span class="text-danger">*</span></label>
                                <input type="" name="ad2" class="form-control pri-form"  value="@isset($dat){{$dat->ad2}}@endisset">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>State <span class="text-danger">*</span></label>
                                <select class="form-control pri-form state" name="stt_id">
                                    <option>Select</option>
                                    @foreach($state as $stt)
                                    <option value="{{$stt->state_id}}" @if(isset($dat->stt_id)) @if($stt->state_id == $dat->stt_id){{"selected"}}@endif @endif>{{$stt->state_title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>District <span class="text-danger">*</span></label>
                                <select class="form-control pri-form district" name="dis_id">
                                @if(isset($dat->dis_id))
                                    <option value="{{$dat->dis_id}}">{{$dat->dis_name}}</option>
                                @else
                                    <option>Select</option>
                                @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Mandal <span class="text-danger">*</span></label>
                                <select class="form-control pri-form mandal" name="mdl_id">
                                @if(isset($dat->mdl_id))
                                <option value="{{$dat->mdl_id}}">{{$dat->mdl_name}}</option>                                    
                                @else
                                <option>Select</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <!--div class="col-md-4">
                            <div class="form-group">
                                <label>Village <span class="text-danger">*</span></label>
                                <select class="form-control pri-form">
                                    <option>Select</option>
                                        @isset($dat)
                                            <option value="">{{$dat->amc}}</option>
                                        @endisset
                                </select>
                            </div>
                        </div-->
                    <script>
                        function addcom(id){
                            if(id < {{ count($dat->tc) }}){
                            $('.repeat-div').append("<div class = 'list com"+id+"'>"+$('.repeat-div').find('.com').html()+"</div>");
                            $('.pp').attr('onclick','addcom('+(id+1)+')');
                        }}
                        function com_ch_1(a){
                            a=a.parent().parent();
                            @foreach($dat->tc as $tc)
                            if({{$tc->com_id}} == a.find('.commodity').val()){
                                    a.find('.aweight').html("Quantity (Available Quantity {{ $tc->a_weight }}@isset($tc->qty_name) {{ $tc->qty_name }} @endisset ) <span class='text-danger'>*</span>");
                                    a.find('.q_id').val("{{ $tc->q_id }}");
                                    a.find('.weight').val("{{ $tc->a_weight }}");
                                    a.find('.tc_id').val("{{ $tc->tc_id }}");
                                }
                            @endforeach
                        }
                        function awht_ch(a) {
                            if(parseInt(a.find('.weight').val()) < parseInt(a.find('.a_weight').val())){
                                alert("Kindly Enter amount less than Avalable Qunatity");
                                a.find('.a_weight').val(0);
                            }
                        }
                    </script>
                    <div class="ordered-list col-12 repeat-div">
                    @php 
                     if(isset($dat->tc[0]->p_id)){
                         $i = count($dat->tc);
                        // echo $dat[0]->tc[0]->p_id;
                    }   else { $i = 1; }
                    for($j=0;$j<$i;$j++){
                        @endphp
                        <div class="list com">
                            <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Commodity <span class="text-danger">*</span></label>
                                <select class="form-control pri-form commodity" name="com_id[]" onchange="com_ch_1($(this).parent())">
                                    <option>Select</option>
                                    @if($dat != '')
                                    @foreach($dat->tc as $tc)
                                        <option value="{{ $tc->com_id }}" @if($tc->com_id == $dat->tc[$j]->com_id) selected @endif >{{ $tc->com_name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <!-- <input type="" name="" class="form-control pri-form"> -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="aweight"> Quantity ( Avaliable Quantity )<span class="text-danger">*</span></label>
                                <input type="" name="a_weight[]" class="a_weight form-control pri-form" value="@isset($dat->tc[$j]){{$dat->tc[$j]->a_weight}}@endisset" onchange="awht_ch($(this).parent())">
                                <input type="hidden" name="weight[]" class="weight form-control pri-form" value="@if(isset($dat->tc[$j]->weight1)){{$dat->tc[$j]->a_weight + $dat->tc[$j]->weight1}}@else{{$dat->tc[$j]->a_weight}}@endif">
                                @if(isset($dat->tc[$j]->weight1))
                                <input type="hidden" name="tc_id[]" class="tc_id" value="{{ $dat->tc[$j]->tc_id }}">@endif
                                <input type="hidden" name="q_id[]" class="q_id" value="@if(isset($dat->tc[$j]->q_id)){{$dat->tc[$j]->q_id }}@endif">
                                </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Trade Value (INR) <span class="text-danger">*</span></label>
                                <input type="" name="trade_val[]" class="trade_val form-control pri-form" value="@isset($dat->tc[$j]->trade_value){{$dat->tc[$j]->trade_value}}@endisset">
                            </div>
                        </div>
                        </div>
                    </div>
                     @php } @endphp
                        @if($i == 1)<div class="add-new pp" onclick="addcom(1)"><i class="priya-plus"></i></div>@endif
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Reference Trade (Last Trade Details/ Primary trade permit) <span class="text-danger">*</span></label>
                               @if(isset($dat->t_id))
                                    <input type="hidden" name="id" value="S{{$dat->id}}" class="form-control pri-form" readonly>
                                    <input type="" name="t_id" value="t{{$dat->t_id}}" class="form-control pri-form" readonly>
                                @else
                                    <input type="" name="t_id" value="t{{$dat->id}}" class="form-control pri-form" readonly>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Vehicle Number <span class="text-danger">*</span></label>
                                <input type="" name="veh_id" class="eh_id form-control pri-form"  value="@isset($dat->veh_id){{$dat->veh_id}}@endisset">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Mobile Number <span class="text-danger">*</span></label>
                                <input type="number" name="mobile" class="number form-control pri-form" value="@isset($dat->mobile){{$dat->mobile}}@endisset"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="10">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Quantity Details <span class="text-danger">*</span></label>
                                <input type="" name="q_details" class="form-control pri-form q_details" @isset($dat->q_details) value="{{$dat->q_details}}" @endisset >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Permit Validity (from date) <span class="text-danger">*</span></label>
                                        <input type="date" name="fd" class="fd form-control pri-form" @isset($df) value="{{$df[0]}}" @endisset>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Time</label>
                                        <input type="time" name="ft" class="ft form-control pri-form" @isset($df[1]) value="{{$df[1]}}" @endisset>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Permit Validity (to date) <span class="text-danger">*</span></label>
                                        <input type="date" name="td" class="td form-control pri-form" @isset($dt) value="{{$dt[0]}}" @endisset>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Time</label>
                                        <input type="time" name="tt" class="tt form-control pri-form" @isset($dt[1]) value="{{$dt[1]}}" @endisset>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for=""> Sales Invoice/Reciept </label>
                                <input type="" class="invoice form-control pri-form" name="invoice" value="@if(isset($dat->invoice)){{$dat->invoice}}@endif">
                            </div>
                        </div>
                        @if(isset($dt))
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for=""> Cancelled Quantity </label>
                                <input type="number" class="c_qty form-control pri-form">
                            </div>
                        </div>
                        <div class="col-md-4 c_r" style="display: none;">
                            <div class="form-group">
                                <label>Cancelation Reason <span class="text-danger">*</span></label>
                                <input type="text" class="c_reason form-control pri-form">
                                <!--select class="c_reason form-control pri-form">
                                    <option value="">Select</option>
                                    <option value="Natural Disaster">Natural Disaster</option>
                                    <option value="Product Damaged and Returned">Product Damaged and Returned</option>
                                </select-->
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="text-center">
                    @if(isset($dt))
                        @if(strtotime($dt[0]." ".$dt[1]) > time())
                        <input type="submit" value="Early Arrival" class="btn" name="submit">
                        <input type="submit" value="SOS" class="btn" name="submit">
                        <button id="cancelbtn" class="btn">Cancel Permit</button>    
                        <input type="submit" class="btn" value="Edit Permit">
                        @endif
                    @else
                        <input type="submit" class="btn" value="Create Permit">
                    @endif
                    @if(isset($dat->name)) <a href="{{url('print-permit')}}/S{{ $dat->id }}" name="" class="btn">Print Permit</a> @endif 
                    </div>
                </form>
                <form action="{{url('cancel-permit')}}" method="post" class="form2">
                @csrf    
                <input type="hidden" name="c_qty" class="c_qty1">
                    <input type="hidden" name="c_reason" class="c_reason1">
                    <input type="hidden" name="id" class="c_id1">
                    <input type="hidden" name="tid" class="c_tid1">
                </form>
            </div>
        </div>
    </div>
</div>
{{--@dd($dat)--}}
@include("includes/footer");
</body>
</html>