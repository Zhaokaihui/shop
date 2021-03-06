var mmg,combo;
function initGrid(p){
    var h = WST.pageHeight();
    var cols = [
            {title:'ID', name:'articleId' ,width:20,sortable:true},
            {title:'标题', name:'articleTitle' ,width:200,sortable:true},
            {title:'分类', name:'catName' ,width:100,sortable:true},
            {title:'是否x显示', name:'isShow' ,width:50,sortable:true, renderer: function(val,item,rowIndex){
                return '<form autocomplete="off" class="layui-form" lay-filter="gridForm"><input type="checkbox" id="isShow" name="isShow" '+((item['isShow']==1)?"checked":"")+' lay-skin="switch" value="1" lay-filter="isShow" lay-text="显示|隐藏" data="'+item['articleId']+'"></form>';
            }},
            {title:'最后编辑者', name:'staffName' ,width:50,sortable:true},
            {title:'创建时间', name:'createTime' ,width:120,sortable:true},
            {title:'排序号', name:'catSort' ,width:15, renderer: function(val,item,rowIndex){
                return '<span style="color:blue;cursor:pointer;" ondblclick="changeSort(this,'+item["articleId"]+');">'+val+'</span>';
             }},
            {title:'操作', name:'' ,width:100, align:'center', renderer: function(val,item,rowIndex){
                var h = "";
                if(WST.GRANT.GRZZN_02)h += "<a  class='btn btn-blue' onclick='javascript:toEdit("+item['articleId']+")'><i class='fa fa-pencil'></i>修改</a> ";
                if(WST.GRANT.GRZZN_03)h += "<a  class='btn btn-red' onclick='javascript:toDel(" + item['articleId'] + ")'><i class='fa fa-trash-o'></i>删除</a> ";
                return h;
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-125,indexCol: true, cols: cols,method:'POST',checkCol:true,multiSelect:true,
        url: WST.U('admin/supplierarticles/pageQueryByGuide'), fullWidthRows: true, autoLoad: false,remoteSort: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });   
    mmg.on('loadSuccess',function(){
    	layui.form.render('','gridForm');
        layui.form.on('switch(isShow)', function(data){
            var id = $(this).attr("data");
            if(this.checked){
                toggleIsShow(1,id);
            }else{
                toggleIsShow(0,id);
            }
        });
    })
    loadGrid(p);
}


function loadGrid(p){
	p=(p<=1)?1:p;
	mmg.load({key:$('#key').val(),catId:$('#catId').val(),page:p});
}


function toggleIsShow(t,v){
	if(!WST.GRANT.GRZZN_02)return;
    var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
    	$.post(WST.U('admin/supplierarticles/editiIsShow'),{id:v,isShow:t},function(data,textStatus){
			  layer.close(loading);
			  var json = WST.toAdminJson(data);
			  if(json.status=='1'){
			    	WST.msg(json.msg,{icon:1});
		            loadGrid(tb_CURR_PAGE);
			  }else{
			    	WST.msg(json.msg,{icon:2});
			  }
		});
}

function toEdit(id){
	location.href=WST.U('admin/supplierarticles/toEditGuide','id='+id+'&p='+tb_CURR_PAGE);
}

function toEdits(id,p){
    var params = WST.getParams('.ipt');
    params.id = id;
    if(params.TypeStatus == 4){
    	params.coverImg = '';
    }
    var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	$.post(WST.U('admin/supplierarticles/'+((id>0)?"editGuide":"addGuide")),params,function(data,textStatus){
		  layer.close(loading);
		  var json = WST.toAdminJson(data);
		  if(json.status=='1'){
		    	WST.msg(json.msg,{icon:1});
		        setTimeout(function(){ 
			    	location.href=WST.U('admin/supplierarticles/guide','p='+p);
		        },1000);
		  }else{
		        WST.msg(json.msg,{icon:2});
		  }
	});
}
function saveAgreement(){
    var params = WST.getParams('.ipt');
    params.id = 109;
    var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
    $.post(WST.U('admin/supplierarticles/editAgreement'),params,function(data,textStatus){
          layer.close(loading);
          var json = WST.toAdminJson(data);
          if(json.status=='1'){
                WST.msg(json.msg,{icon:1});
          }else{
                WST.msg(json.msg,{icon:2});
          }
    });
}
function toDel(id){
	var box = WST.confirm({content:"您确定要删除该文章吗?",yes:function(){
	           var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	           	$.post(WST.U('admin/supplierarticles/delGuide'),{id:id},function(data,textStatus){
	           			  layer.close(loading);
	           			  var json = WST.toAdminJson(data);
	           			  if(json.status=='1'){
	           			    	WST.msg(json.msg,{icon:1});
	           			    	layer.close(box);
	           		            loadGrid(tb_CURR_PAGE);
	           			  }else{
	           			    	WST.msg(json.msg,{icon:2});
	           			  }
	           		});
	            }});
}

function toBatchDel(){
	var rows = mmg.selectedRows();
	if(rows.length==0){
		WST.msg('请选择要删除的文章',{icon:2});
		return;
	}
	var ids = [];
	for(var i=0;i<rows.length;i++){
       ids.push(rows[i]['articleId']); 
	}
	var box = WST.confirm({content:"您确定要删除这些文章吗?",yes:function(){
	           var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	           	$.post(WST.U('admin/supplierarticles/delByBatchGuide'),{ids:ids.join(',')},function(data,textStatus){
	           			  layer.close(loading);
	           			  var json = WST.toAdminJson(data);
	           			  if(json.status=='1'){
	           			    	WST.msg(json.msg,{icon:1});
	           			    	layer.close(box);
	           		            loadGrid(tb_CURR_PAGE);
	           			  }else{
	           			    	WST.msg(json.msg,{icon:2});
	           			  }
	           		});
	            }});
}
function selectlLayout(val){
    if(val == 4){
    	$('#upload,#image').hide();
    }else{
    	$('#upload,#image').show();
    }
    var remind;
    if(val == 1 || val == 2){
        remind = '91 × 91(px)';
    }else if(val == 3){
        remind = '411 × 164(px)';
    }
    
    $('#remind').html('建议图片大小:'+remind+'，格式为 gif, jpg, jpeg, png');
}
var oldSort;
function changeSort(t,id){
   $(t).attr('ondblclick'," ");
var html = "<input type='text' id='sort-"+id+"' style='width:30px;padding:2px;' onblur='doneChange(this,"+id+")' value='"+$(t).html()+"' />";
 $(t).html(html);
 $('#sort-'+id).focus();
 $('#sort-'+id).select();
 oldSort = $(t).html();
}
function doneChange(t,id){
  var sort = ($(t).val()=='')?0:$(t).val();
  if(sort==oldSort){
    $(t).parent().attr('ondblclick','changeSort(this,'+id+')');
    $(t).parent().html(parseInt(sort));
    return;
  }
  $.post(WST.U('admin/supplierarticles/changeSort'),{id:id,catSort:sort},function(data){
    var json = WST.toAdminJson(data);
    if(json.status==1){
        $(t).parent().attr('ondblclick','changeSort(this,'+id+')');
        $(t).parent().html(parseInt(sort));
    }
  });
}