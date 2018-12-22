<?php $dbHost = 'localhost'; $dbUser = 'root'; $dbPass = ''; $dbName = 'timetable'; 
///Основной гинератор------------------------------------------------------
	if(!isset($_POST)) {
		echo "Да";
	}
	$day_id = $_POST["week"];
	$groups_id = $_POST["groups"];
	$teachers_id_num = $_POST["teacher_id"];
	settype($day_id, 'integer');
	settype($teachers_id_num, 'integer');
	settype($groups_id, 'integer');

	if (!isset($_POST["week"]))
	{
		$day_id = 1;
	}

	if (!isset($_POST["groups"]))
	{
		$groups_id = 1;
	}	

	if (!isset($_POST["teacher"]))
	{
		$teachers_id = 1;
	}

	if (!isset($_POST["teacher_id"]))
	{
		$teachers_id_num = 1;
	}

	if($day_id != '7')
		{
			$query_add = " AND timetable.day = '".$day_id."'";
		}else{
			$query_add = "";
		}
	$link = mysqli_connect($dbHost,$dbUser,$dbPass,$dbName)
	or die("ошибка".mysqli_connect_error($link));

	$teacher_name_id = "SELECT number FROM timetable WHERE (timetable.teacher = '".$teachers_id_num."'".$query_add.");";


	$teacher_id = mysqli_query($link, $teacher_name_id)
	or die ("ошибка2 ".mysqli_connect_error($link));
	$teacher_rows_id = mysqli_num_rows($teacher_id);

 ?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Расписание КЦПТ</title>
	<link rel="stylesheet" href="Style.css">
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>

	<!-- JavaScript (JQuery) -->

	<script type="text/javascript">
		alert("ss")
		
		function check_answer (answer,i){
			var html;
			if (answer[i].lesson.indexOf("|") != -1) {
				var lesson_1 = answer[i].lesson.substring(0,answer[i].lesson.indexOf("|"));
				var teacher_1 = answer[i].teacher.substring(0,answer[i].teacher.indexOf("|"));
				var room_1 = answer[i].room.substring(0,answer[i].room.indexOf("|"));

				var lesson_2 = answer[i].lesson.substring(answer[i].lesson.indexOf("|")+1,answer[i].lesson.length);
				var teacher_2 = answer[i].teacher.substring(answer[i].teacher.indexOf("|")+1,answer[i].teacher.length);
				var room_2 = answer[i].room.substring(answer[i].room.indexOf("|")+1,answer[i].room.length);

				html = "<tr class=\"table_"+i+"\"> <td class=\"cell_1\" rowspan =\"2\" >"+answer[i].number+"</td> <td class=\"cell_2\">"+lesson_1+"</td> <td class=\"cell_3\">"+teacher_1+"</td> <td class=\"cell_4\">"+room_1+"</td> </tr> <tr class=\"table_"+i+"\"> <td class=\"cell_2\">"+lesson_2+"</td> <td class=\"cell_3\">"+teacher_2+"</td> <td class=\"cell_4\">"+room_1+"</td> </tr>";
				return html;
			}else{
				html = "<tr class=\"table_"+ i +"\"><td class=\"cell_1\">"+ answer[i].number +"</td><td class=\"cell_2\">"+ answer[i].lesson +"</td><td class=\"cell_3\">"+ answer[i].teacher +"</td><td class=\"cell_4\">"+ answer[i].room +"</td></tr>";
				return html;
			}
			
		}

		function funcSeccess(data){
			var answer = JSON.parse(data);
			var request = "";
			for (var i = 0; i < answer.length; i++) {
				if(i==0){
					$("#week_day").html("");
					$("#week_day").append("<tr class=\"table_of_contents\"><th>Урок</th><th>Название предмета</th><th>Преподаватель</th><th>Кабинет</th></tr>");
				}
					
					$("#week_day").append( check_answer( answer,i ) );
					/*else if (j == 2) {
						$(request).html(answer[i].lesson);
					}else if (j == 3) {
						$(request).html(answer[i].teacher);
					}else if (j == 4) {
						$(request).html(answer[i].room);
					}*/
				
			}
			alert(request);
		};

		$(document).ready(function (){

			$("select[name='groups']").on("click", function(){


				if ($(this).hasClass("op-sel")){

					$.ajax({
						url:"request.php",
						type:"POST",
						data: { 
							status: $("input[name='teacher']").val(),
							group: $("select[name='groups']").val(),
							week: $("select[name='week']").val()
						} ,
						datatype: "html",
						success: funcSeccess
					});
					$("select[name='groups']").removeClass("open");
					$(this).removeClass("op-sel");
				} 
				else 
				{
					$(this).addClass("op-sel");
				}
			});

			$("select[name='week']").on("click", function(){
				if ($(this).hasClass("op-sel")){
					$.ajax({
						url:"request.php",
						type:"POST",
						data: { 
							status: $("input[name='teacher']").val(),
							group: $("select[name='groups']").val(),
							week: $("select[name='week']").val()
						} ,
						datatype: "html",
						success: funcSeccess
					});
					$("select[name='groups']").removeClass("open");
					$(this).removeClass("op-sel");
				} 
				else 
				{
					$(this).addClass("op-sel");
				}
			});

			$("div.checkbox-btn input[name='teacher']").bind("click",function(){
				if ($("input[name='teacher']").val() == "1") {
					$("input[name='teacher']").val("0");
				}else{
					$("input[name='teacher']").val("1");
				}
				$.ajax({
					url:"request.php",
					type:"POST",
					data: {
						status: $("input[name='teacher']").val(),
						group: $("select[name='groups']").val(),
						week: $("select[name='week']").val()
					} ,
					datatype: "html",
					success: funcSeccess
				});

				
			});
		});
	</script>

	<!--End JavaScript (Jquery)!-->

</head>
<body>	
	<h1>Расписание КЦПТ</h1>
	<form name="ginerat"  action="" method="post">
	<label>Статус:</label><br><br>
	<div class="checkbox-btn">
		<input type="checkbox" name="teacher" value = "0" >
		<div><span class="slide"></span></div>
	</div>

<?php 
	///----------------------------------------------------------------------
	if ($_POST["teacher"] == '1') {
		$query_teacher = "SELECT teachers.name FROM teachers;";

		$result_teacher = mysqli_query($link,$query_teacher) 
		or die("ошибка ".mysqli_connect_error($link));
		$rows_teacher  = mysqli_num_rows($result_teacher);
		
 		echo "<label style=\"display: inline-block; width: 85px\">Группа:</label><br>";
		echo "<select name='teacher_id' id='teacher_id' value = '".$teachers_id."'>";

		for ($g = 1;$g<$rows_teacher+1;++$g){
			$row_teacher = mysqli_fetch_row($result_teacher);

			for ($l = 0;$l < 1;++$l){
				echo "<option value = \"".$g."\">";
				echo "<a href =\"#\">".$row_teacher[$l]."</a>";
			}
			echo "</option>";
		}
		mysqli_free_result($result_teacher);
		echo "</select><br><br>";

	} else {
	
		$query_groups = "SELECT groups.name FROM groups;";
	
		$result_groups = mysqli_query($link,$query_groups) 
		or die("ошибка ".mysqli_connect_error($link));
	
		
	
		$rows_groups  = mysqli_num_rows($result_groups);
	 	echo "<label style=\"display: inline-block; width: 85px\">Группа:</label><br>";
		echo "<select name='groups' class= 'groups' autofocus='' id='groups' value = '".$day_id."'>";
	
		for ($g = 1;$g<$rows_groups+1;++$g){
			$row_groups = mysqli_fetch_row($result_groups);
	
			for ($l = 0;$l < 1;++$l){
				echo "<option ";
				if ($_POST["groups"] == $g){echo "selected='".$_POST["groups"]."'";}
				echo " value = \"".$g."\">";
				echo "<a href =\"#\">".$row_groups[$l]."</a>";
			}
			echo "</option>";
		}
		mysqli_free_result($result_groups);
		echo "</select><br><br>";
	}
	///----------------------------------------------------------------

	$query_week = "SELECT week.name FROM week;";

	$result_week = mysqli_query($link,$query_week) 
	or die("ошибка ".mysqli_connect_error($link));
	$rows_week  = mysqli_num_rows($result_week);

	echo "<label style=\"display: inline-block; width: 125px\">День недели:</label><br>";
	echo "<select name='week' id='week' value = '".$day_id."'>";
	for ($g = 1;$g<$rows_week+1;++$g){
		$row_week = mysqli_fetch_row($result_week);

		for ($l = 0;$l < 1;++$l){
		echo "<option ";
		if ($_POST["week"] == $g){echo "selected='".$_POST["week"]."'";}
		echo " value = \"".$g."\">";
		echo "<a href =\"#\">".$row_week[$l]."</a>";
		}
		echo "</option>";
	}
	echo "<option ";
	if ($_POST["week"] == '7'){echo "selected='".$_POST["week"]."'";}
	echo " value='7'>Неделя</option>";
	mysqli_free_result($result_week);
	echo "</select><br>";
		 ?>
	</form>
	<hr>
<?php 
///Зависимость от списка "Группа" и вывод группы на экран
// 	if($_POST["teacher"] === "0")
// 		{
// 			$query_teachers = "SELECT teachers.name FROM teachers WHERE (teachers.id = '".$teachers_id_num."');";

// 			$result_teachers = mysqli_query($link,$query_teachers) 
// 			or die("ошибка ".mysqli_connect_error($link));

// 			$row_teachers = mysqli_fetch_row($result_teachers);
// 			echo $row_teachers[0]."<br>";	
// 	}else{
// 			$query_groups = "SELECT groups.name FROM groups WHERE (groups.id = '".$groups_id."');";

// 			$result_groups = mysqli_query($link,$query_groups) 
// 			or die("ошибка ".mysqli_connect_error($link));

// 			$row_groups = mysqli_fetch_row($result_groups);
// 			echo $row_groups[0]."<br>";
// 	}	
// ///Зависимость от списка "Дни недели" и вывод дня недели на экран
// 			$query_week = "SELECT week.name FROM week WHERE (week.id = '".$day_id."');";

// 			$result_week = mysqli_query($link,$query_week) 
// 			or die("ошибка ".mysqli_connect_error($link));

// 			$row_week = mysqli_fetch_row($result_week);
// 			echo $row_week[0];
////Таблица(Генератор)
 // 	if($_POST["teacher"] === "1")
 // 	{
	// 	$teacher_name = "SELECT * FROM timetable WHERE (timetable.teacher = '".$teachers_id_num."'".$query_add.") ORDER BY timetable.number;";
	// 	$teacher = mysqli_query($link, $teacher_name)
	// 	or die("ошибка2 ".mysqli_connect_error($link));
	// 	if ($teacher)
	// 	{
	// 		if($day_id != 7)
	// 		{
	// 			$teacher_rows = mysqli_num_rows($teacher);
	// 			if($teacher_rows != 0)
	// 			{
	// 				echo "<table><th>Урок</th><th>Группа</th><th>Название предмета</th><th>Каб</th>";
	// 				for($r = 0;$r<$teacher_rows;++$r)
	// 				{
	// 					$teacher_row = mysqli_fetch_row($teacher);
	// 					echo "<tr>";
	// 					for ($j = 3; $j < 7 ; ++$j) 
	// 					{
	// 						$k = $teacher_row[$j];
	// 						settype($k, "integer");
							
	// 						if ($j == 3) 		///Номер пары
	// 						{
	// 							echo "<td>".$teacher_row[3]."</td>";
	// 						}
	// 						if ($j == 4) 		///Урок
	// 						{	
	// 							$k = $teacher_row[1];
	// 							settype($k, "integer");
	// 							$query_groups = "SELECT groups.name FROM groups WHERE (groups.id = '".$k."');";     	///Запрос в таблицу groups(Название группы)
	// 							$result_groups = mysqli_query($link,$query_groups) 
	// 							or die("ошибка ".mysqli_connect_error($link));
	// 							$row_groups = mysqli_fetch_row($result_groups);
								
	// 							echo "<td>".$row_groups[0]."</td>";
	// 						}
	// 						if ($j == 5) 		///Урок
	// 						{	
	// 							$k = $teacher_row[4];
	// 							settype($k, "integer");
	// 							$query_lessons = "SELECT * FROM lessons WHERE (lessons.id = '".$k."');";     	///Запрос в таблицу lessons(Название урока)
	// 							$result_lessons = mysqli_query($link,$query_lessons) 
	// 							or die("ошибка ".mysqli_connect_error($link));
	// 							$row_lessons = mysqli_fetch_row($result_lessons);
								
	// 							echo "<td>".$row_lessons[1]."</td>";
	// 						}
	// 						if ($j == 6)		///Кабинет
	// 						{
	// 							$query_rooms = "SELECT rooms.name FROM rooms WHERE (rooms.id = '".$k."');";  					///Запрос в таблицу rooms(Кабинет)
	// 							$result_rooms = mysqli_query($link,$query_rooms) 
	// 							or die("ошибка ".mysqli_connect_error($link));
	// 							$row_rooms = mysqli_fetch_row($result_rooms);
			
	// 							echo "<td>".$row_rooms[0]."</td>";
	// 						}
	// 					}
	// 					echo "</tr>";	
	// 				}
	// 				echo "</table>";
	// 			}
	// 			else
	// 			{
	// 				echo "<br>Пар нет";
	// 			}
	// 		}
	// 		else
	// 		{	
	// 			for($t = 1;$t<$day_id;$t++)
	// 			{
	// 				$query_week = "SELECT week.name FROM week WHERE (week.id = '".$t."');";

	// 				$result_week = mysqli_query($link,$query_week) 
	// 				or die("ошибка ".mysqli_connect_error($link));

	// 				$row_week = mysqli_fetch_row($result_week);
	// 				echo $row_week[0];
	// 				//день недели выше
	// 				SELECT timetable.number,lessons.name,teachers.name,rooms.name FROM timetable,rooms,teachers,lessons WHERE (timetable.teacher = '1' AND timetable.day = '1') ORDER BY timetable.number;
					// $teacher_name = "SELECT * FROM timetable WHERE (timetable.teacher = '".$teachers_id_num."' AND timetable.day = '".$t."') ORDER BY timetable.number;";
	// 				$teacher = mysqli_query($link, $teacher_name)
	// 				or die("ошибка2 ".mysqli_connect_error($link));
	// 				$teacher_rows = mysqli_num_rows($teacher);///музей имени Словцова
	// 				if($teacher_rows != 0)
	// 				{
	// 					echo "<table><th>Урок</th><th>Группа</th><th>Название предмета</th><th>Каб</th>";
	// 					for($r = 0;$r<$teacher_rows;++$r)
	// 					{
	// 						$teacher_row = mysqli_fetch_row($teacher);
	// 						echo "<tr>";
	// 						for ($j = 3; $j < 7 ; ++$j) 
	// 						{
	// 							$k = $teacher_row[$j];
	// 							settype($k, "integer");
						
	// 							if ($j == 3) 		///Номер пары
	// 							{
	// 								echo "<td>".$teacher_row[3]."</td>";
	// 							}
	// 							if ($j == 4) 		///Урок
	// 							{	
	// 								$k = $teacher_row[1];
	// 								settype($k, "integer");
	// 								$query_groups = "SELECT groups.name FROM groups WHERE (groups.id = '".$k."');";     	///Запрос в таблицу groups(Название группы)
	// 								$result_groups = mysqli_query($link,$query_groups) 
	// 								or die("ошибка ".mysqli_connect_error($link));
	// 								$row_groups = mysqli_fetch_row($result_groups);
							
	// 								echo "<td>".$row_groups[0]."</td>";
	// 							}
	// 							if ($j == 5) 		///Урок
	// 							{	
	// 								$k = $teacher_row[4];
	// 								settype($k, "integer");
	// 								$query_lessons = "SELECT * FROM lessons WHERE (lessons.id = '".$k."');";     	///Запрос в таблицу lessons(Название урока)
	// 								$result_lessons = mysqli_query($link,$query_lessons) 
	// 								or die("ошибка ".mysqli_connect_error($link));
	// 								$row_lessons = mysqli_fetch_row($result_lessons);
	// 								echo "<td>".$row_lessons[1]."</td>";
	// 							}
	// 							if ($j == 6)		///Кабинет
	// 							{
	// 								$query_rooms = "SELECT rooms.name FROM rooms WHERE (rooms.id = '".$k."');";  					///Запрос в таблицу rooms(Кабинет)
	// 								$result_rooms = mysqli_query($link,$query_rooms) 
	// 								or die("ошибка ".mysqli_connect_error($link));
	// 								$row_rooms = mysqli_fetch_row($result_rooms);
	// 								echo "<td>".$row_rooms[0]."</td>";
	// 							}
	// 						}
	// 						echo "</tr>";
	// 					}
	// 					echo "</table><hr>";
	// 				}
	// 				else
	// 				{
	// 					echo "<br>Пар нет<hr>";
	// 				}
	// 			}
	// 		}
	// 	}
	// }
	// else
	// {	
	// 	if($day_id != 7)
	// 	{
	// 			$query = "SELECT * FROM timetable WHERE (groupname = '".$groups_id."' AND day = ".$day_id." ) ;";   ///Запрос в таблицу timetable(структура расписания)
	// 			$result = mysqli_query($link,$query) 
	// 			or die("ошибка ".mysqli_connect_error($link));
	// 			if ($result)
	// 			{
	// 				$rows = mysqli_num_rows($result);
	// 				if($rows != 0)
	// 				{
	// 					echo "<table><th>Урок</th><th>Название предмета</th><th>Преподаватель</th><th>Каб</th>";
	// 					$result_check = false;
	// 					for ($i = 0;$i<$rows;++$i)
	// 					{
	// 						$row = mysqli_fetch_row($result);
	// 						echo "<tr>";
	// 						for ($j = 3; $j < 7 ; ++$j) 
	// 						{
	// 							$k = $row[$j];
	// 							settype($k, "integer");
	// 							if ($j == 3) 		///Номер пары
	// 							{
	// 								echo "<td>".$row[$j]."</td>";
	// 							}
	// 							if ($j == 4) 		///Урок
	// 							{
	// 								$query_lessons = "SELECT lessons.name FROM lessons WHERE (lessons.id = '".$k."');";     	///Запрос в таблицу lessons(Название урока)
	// 								$result_lessons = mysqli_query($link,$query_lessons) 
	// 								or die("ошибка ".mysqli_connect_error($link));
	// 								$row_lessons = mysqli_fetch_row($result_lessons);
	// 								echo "<td>".$row_lessons[0]."</td>";
	// 							}
	// 							if ($j == 5) 		///Преподаватель
	// 							{
	// 								$query_teachers = "SELECT teachers.name FROM teachers WHERE (teachers.id = '".$k."');";	///Запрос в таблицу teachers(ФИО Преподавателя)
	// 								$result_teachers = mysqli_query($link,$query_teachers) 
	// 								or die("ошибка ".mysqli_connect_error($link));
	// 								$row_teachers = mysqli_fetch_row($result_teachers);
	// 								echo "<td>".$row_teachers[0]."</td>";
	// 							}
	// 							if ($j == 6)		///Кабинет
	// 							{
	// 								$query_rooms = "SELECT rooms.name FROM rooms WHERE (rooms.id = '".$k."');";  					///Запрос в таблицу rooms(Кабинет)
	// 								$result_rooms = mysqli_query($link,$query_rooms) 
	// 								or die("ошибка ".mysqli_connect_error($link));
	// 								$row_rooms = mysqli_fetch_row($result_rooms);
	// 								echo "<td>".$row_rooms[0]."</td>";
	// 							}
	// 						}
	// 						echo "</tr>";
	// 					}
	// 					echo "</table>";
	// 					mysqli_free_result($result);
	// 				}
	// 				else
	// 				{
	// 					echo "<br>Пар нет";
	// 				}
	// 			}
	// 	}
	// 	else
	// 	{	
	// 		for($t = 1;$t<$day_id;$t++)
	// 		{	
	// 			$query_week = "SELECT week.name FROM week WHERE (week.id = '".$t."');";
	// 			$result_week = mysqli_query($link,$query_week) 
	// 			or die("ошибка ".mysqli_connect_error($link));
	// 			$row_week = mysqli_fetch_row($result_week);
	// 			echo $row_week[0];
	// 			//день недели выше
	// 			$query = "SELECT * FROM timetable WHERE (groupname = '".$groups_id."' AND day = ".$t." ) ;";   ///Запрос в таблицу timetable(структура расписания)
	// 			$result = mysqli_query($link,$query) 
	// 			or die("ошибка ".mysqli_connect_error($link));
	// 			if ($result)
	// 			{
	// 				$rows = mysqli_num_rows($result);
	// 				if($rows != 0)
	// 				{
	// 					echo "<table><th>Урок</th><th>Название предмета</th><th>Преподаватель</th><th>Каб</th>";
	// 					$result_check = false;
	// 					for ($i = 0;$i<$rows;++$i)
	// 					{
	// 						$row = mysqli_fetch_row($result);
	// 						echo "<tr>";
	// 						for ($j = 3; $j < 7 ; ++$j) 
	// 						{
	// 							$k = $row[$j];
	// 							settype($k, "integer");
	// 							if ($j == 3) 		///Номер пары
	// 							{
	// 								echo "<td>".$row[$j]."</td>";
	// 							}
	// 							if ($j == 4) 		///Урок
	// 							{
	// 								$query_lessons = "SELECT lessons.name FROM lessons WHERE (lessons.id = '".$k."');";     	///Запрос в таблицу lessons(Название урока)
	// 								$result_lessons = mysqli_query($link,$query_lessons) 
	// 								or die("ошибка ".mysqli_connect_error($link));
	// 								$row_lessons = mysqli_fetch_row($result_lessons);
	// 								echo "<td>".$row_lessons[0]."</td>";
	// 							}
	// 							if ($j == 5) 		///Преподаватель
	// 							{
	// 								$query_teachers = "SELECT teachers.name FROM teachers WHERE (teachers.id = '".$k."');";	///Запрос в таблицу teachers(ФИО Преподавателя)
	// 								$result_teachers = mysqli_query($link,$query_teachers) 
	// 								or die("ошибка ".mysqli_connect_error($link));
	// 								$row_teachers = mysqli_fetch_row($result_teachers);
	// 								echo "<td>".$row_teachers[0]."</td>";
	// 							}
	// 							if ($j == 6)		///Кабинет
	// 							{
	// 								$query_rooms = "SELECT rooms.name FROM rooms WHERE (rooms.id = '".$k."');";  					///Запрос в таблицу rooms(Кабинет)
	// 								$result_rooms = mysqli_query($link,$query_rooms) 
	// 								or die("ошибка ".mysqli_connect_error($link));
	// 								$row_rooms = mysqli_fetch_row($result_rooms);
	// 								echo "<td>".$row_rooms[0]."</td>";
	// 							}
	// 						}
	// 						echo "</tr>";
	// 					}
	// 					echo "</table><hr>";
	// 					mysqli_free_result($result);
	// 				}
	// 				else
	// 				{
	// 					echo "<br>Пар нет<hr>";
	// 				}
	// 			}
	// 		}	
	// 	}
	// }
	// 

	mysqli_close($link);
?>
	<div class="main">
		<div class="weekend">
			
		</div>
		<div class="week_day">
			<table id="week_day">
				<tr class="table_of_contents"><th>Урок</th><th>Название предмета</th><th>Преподаватель</th><th>Кабинет</th></tr>
				<tr class="table_1">
					<td class="cell_1"></td>
					<td class="cell_2"></td>
					<td class="cell_3"></td>
					<td class="cell_4"></td>
				</tr>
				<tr class="table_2">
					<td class="cell_1"></td>
					<td class="cell_2"></td>
					<td class="cell_3"></td>
					<td class="cell_4"></td>
				</tr>
				<tr class="table_3">
					<td class="cell_1"></td>
					<td class="cell_2"></td>
					<td class="cell_3"></td>
					<td class="cell_4"></td>
				</tr>
				<tr class="table_4">
					<td class="cell_1"></td>
					<td class="cell_2"></td>
					<td class="cell_3"></td>
					<td class="cell_4"></td>
				</tr>
				<tr class="table_5">
					<td class="cell_1"></td>
					<td class="cell_2"></td>
					<td class="cell_3"></td>
					<td class="cell_4"></td>
				</tr>
				<tr class="table_6">
					<td class="cell_1"></td>
					<td class="cell_2"></td>
					<td class="cell_3"></td>
					<td class="cell_4"></td>
				</tr>
				<tr class="table_7">
					<td class="cell_1"></td>
					<td class="cell_2"></td>
					<td class="cell_3"></td>
					<td class="cell_4"></td>
				</tr>
				<tr class="table_8">
					<td class="cell_1"></td>
					<td class="cell_2"></td>
					<td class="cell_3"></td>
					<td class="cell_4"></td>
				</tr>
				<tr class="table_9">
					<td class="cell_1"></td>
					<td class="cell_2"></td>
					<td class="cell_3"></td>
					<td class="cell_4"></td>
				</tr>
				<tr class="table_10">
					<td class="cell_1"></td>
					<td class="cell_2"></td>
					<td class="cell_3"></td>
					<td class="cell_4"></td>
				</tr>
				<tr class="table_11">
					<td class="cell_1"></td>
					<td class="cell_2"></td>
					<td class="cell_3"></td>
					<td class="cell_4"></td>
				</tr>
				<tr class="table_12">
					<td class="cell_1"></td>
					<td class="cell_2"></td>
					<td class="cell_3"></td>
					<td class="cell_4"></td>
				</tr>
			</table>
		</div>
	</div>
</body>
</html>