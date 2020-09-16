
<?php
header("Content-type: application/json; charset=utf-8");
?>

<div class="col-sm-12 col-md-12 ">
	<h3>Минимальный путь</h3>
	<form>
		<div class="form-group ">
			<label for="first_node">Первая вершина</label>
			<select class="form-control node_ajax" id="first_node" name="first_node">
			<?php
				$json = file_get_contents('http://yii.tat/node');
				$data = json_decode($json, JSON_PRETTY_PRINT);
				foreach($data as $item){
					echo '<option value="'.$item['id'].'">'.$item['name'].'</option>';
				}
			?>
			</select>
		</div>
		<div class="form-group ">
			<label for="second_node">Вторая вершина</label>
			<select class="form-control node_ajax" id="second_node" name="second_node">
			<?php
				foreach($data as $item){
					echo '<option value="'.$item['id'].'">'.$item['name'].'</option>';
				}
			?>
			</select>
		</div>
		<div class="result">

		</div>
		<button type="button" class="btn btn-primary search">Получить минимальный путь</button>
	</form>
	<br>
</div>

<div class="col-sm-6 col-md-6">
	<h3>Создание вершины</h3>
	<form>
		<div class="form-group ">
			<label for="name_node">Название вершины</label>
			<input type="text" class="form-control" id="name_node" name="name_node">
		</div>
		<button type="button" class="btn btn-primary create_node">Создать вершину</button>
	</form>
</div>
<div class="col-sm-6 col-md-6">
</div>
<div class="col-sm-6 col-md-6">
	<h3>Связать вершины</h3>
	<form>
		<div class="form-group ">
			<label for="first_node">Первая вершина</label>
			<select class="form-control bind_node node_ajax" id="first_node" name="first_node">
			<?php
				foreach($data as $item){
					echo '<option value="'.$item['id'].'">'.$item['name'].'</option>';
				}
			?>
			</select>
		</div>
		<div class="form-group ">
			<label for="second_node">Вторая вершина</label>
			<select class="form-control bind_node node_ajax" id="second_node" name="second_node">
			<?php
				foreach($data as $item){
					echo '<option value="'.$item['id'].'">'.$item['name'].'</option>';
				}
			?>
			</select>
		</div>
		<div class="form-group ">
			<label for="weight_node bind_node">Вес ребра</label>
			<input type="number" class="form-control" id="weight_node" name="weight_node">
		</div>
		<div class="result_bind">

		</div>
		<div id="sum" name="sum">

		</div>
		<button type="button" class="btn btn-primary bind">Связать вершины</button>
		<button type="button" class="btn btn-primary disabled unbind">Удалить связь вершин</button>
	</form>
</div>

<div class="col-sm-12 col-md-12 ">
	<div id="edge_arr" style="display:none;"><?php echo(json_encode($return));?></div>
	<div id="canvas" style="height: 400px; width: 600px;"></div>
</div>

<?php
$js = <<<JS
/*Рисуем*/
var edge_arr = $("#edge_arr").text();
var data = JSON.parse(edge_arr);

var g = new Graph();
data.forEach(function(row) {
	g.addEdge(row.id_first_node+"", row.id_second_node+"", {directed: true,stroke: "#fff",fill: "#5a5",label: row.weight });
});

var layouter = new Graph.Layout.Spring(g);
layouter.layout();

var renderer = new Graph.Renderer.Raphael('canvas', g, 512, 368);
renderer.draw();
/**/

/*наличие новых вершин*/
function fetchData() {
$.get('/node', function(data) {
	$.each(data,function(key,row) {
		var option = '<option value="'+row.id+'">'+row.name+'</option>';
		if ($('.node_ajax > option[value='+row.id+']').length) {
			//console.log(option);
		}
		else{
			$('.node_ajax').append(option);
		}
	});
})
}
$(document).ready(function () {
	setInterval(fetchData, 3000);
})
/**/

$('.search').on('click', function () {
	var first = this.form.first_node.value;
	var second = this.form.second_node.value;
	var url='/search/'+ first + '/' + second;
	$.ajax({
		type: "GET",
		url: url,
		dataType: 'json',
		cache: false,
		success: function (data) {
			//console.log(data);
			if (data==null){
				$(".result").text('Нет пути');
			}
			else{
				$(".result").text('Минимальный путь = '+ data);
			}
		}
    });

});
$('.create_node').on('click', function () {
	var name_n = this.form.name_node.value;
	var url='/node';
	$.ajax({
		type: "POST",
		url: url,
		data: {id_heap: '1', name: name_n},
		dataType: 'json',
		cache: false,
		success: function (data) {
			alert('Вершина добавлена!');
		}
    });

});
$('.bind_node').change(function () {
	var form = this.form;
	var first = this.form.first_node.value;
	var second = this.form.second_node.value;
	var url='/searchedge/'+ first + '/' + second;
	$.ajax({
		type: "GET",
		url: url,
		dataType: 'json',
		cache: false,
		success: function (data) {
			if (data.weight==null){
				$(".result_bind").text('Вершины не связаны');
				$(".result_bind").removeAttr("data-id");
				$(".unbind").addClass("disabled")
			}
			else{
				$(".result_bind").text('Вершины связаны. Вес = '+ data.weight);
				$(".result_bind").attr("data-id", data.id);
				$(".unbind").removeClass("disabled")

			}
		}
    });

});
$('.bind').on('click', function () {
	var id_edge = $(".result_bind").attr("data-id");
	var form = this.form;
	var first = this.form.first_node.value;
	var second = this.form.second_node.value;
	var weight = this.form.weight_node.value;

	if (id_edge!=null) {
		var url='/edge/'+ id_edge;
		//var data=;
		console.log(url);
		$.ajax({
			type: "PATCH",
			url: url,
			data: {weight: weight},
			dataType: 'json',
			cache: false,
			success: function (data) {
				console.log(data);
				alert('Вес изменен');
			}
		});
	}
	else{
		var url='/edge';
		$.ajax({
			type: "POST",
			url: url,
			data: {id_first_node: first, id_second_node: second, weight: weight},
			dataType: 'json',
			cache: false,
			success: function (data) {
				console.log(data);
				alert('Вершины соеденены!');
				$(".result_bind").text('Вершины связаны. Вес = '+ data.weight);
				$(".result_bind").attr("data-id", data.id);
			}
		});
	}
});
$('.unbind').on('click', function () {
	var id_edge = $(".result_bind").attr("data-id");
	if (id_edge!=null) {
		var url='/edge/'+ id_edge;
		console.log(url);
		$.ajax({
			type: "DELETE",
			url: url,
			dataType: 'json',
			cache: false,
			success: function (data) {
				alert('Удалено');
				$(".result_bind").text('');
				$(".result_bind").removeAttr("data-id");
				$(".unbind").addClass("disabled")
			}
		});
	}
});
JS;
 
$this->registerJs($js);
?>