<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
use Cake\Routing\Router;
$tasksTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Tasks');
echo $this->Html->css(['tasks' , 'home', 'modal', 'buttons', 'bootstrap']);
echo $this->Html->script(['jquery-1.4.1','bootstrap.min']);
//set date range for +/- 3months current date to avoid lag. Tasks are formatted mm/dd/yy
$d = date("m/d/y");
$dPlus = strtotime("+4 months"); //(php is smart can interpret "3 months"
$dMinus = strtotime("-4 months"); //(php is smart can interpret "3 months"
$maxD = strtotime(date("m/d/y",$dPlus));//3months forwards
$minD = strtotime(date("m/d/y",$dMinus));//3months backwards

$this->loadHelper('Authentication.Identity');
if ($this->Identity->isLoggedIn()) {
    $currentUserName = $this->Identity->get('name');
    $currentUserId = $this->Identity->get('id');
    $currentUserAccess = $this->Identity->get('department_id');
}
?>
<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">


<div style="display:none">
    <?php foreach ($users as $user): // THIS function appends the modals to the tasks. removed from the task creation because of bugs. (might be fixed now)
        foreach ($user->tasks as $task):
          if ((strtotime($task->due_date) > $minD && strtotime($task->due_date) < $maxD)):  //Only check for +/- 2months. To avoid excessive lag whilst dealing with most circumstances ?>
              <div class = "modals" id="<?=$task->id?>" style ="display:none"><?=$this->element('viewTask', ['taskID' => $task->id])?></div>
          <?php endif;
         endforeach;
    endforeach;?>
</div>

<?php
    $navData = Array();
    $NotComplted=0;
    $Completed=0;
?>

<?php foreach ($users as $user):?>
<?php
    if((isset($_GET['Employees']) && ($_GET['Employees']==$user->id || $_GET['Employees']=="blank")) || isset($_GET['Employees'])==false):
        ?>
        <?php foreach ($user->tasks as $task) {
            ?>
            <?php // Initialises every task as an invisible card
                if($task->status_id==2){
                    $Completed += 1;
                    array_push($navData, array('name'=>'Completed', 'value'=>1, 'dueDate'=> $task->due_date));
                }else{
                    $NotComplted += 1;
                    array_push($navData, array('name'=>'Not Completed', 'value'=>1, 'dueDate'=> $task->due_date));
                }
        }
        ?>
<?php endif; endforeach; ?>


<?php
    $allData = Array();
    $OverDue = 0;
    $allTotal = 0;
?>
<?php foreach ($users as $user):?>
<?php
    if((isset($_GET['Employees']) && ($_GET['Employees']==$user->id || $_GET['Employees']=="blank")) || isset($_GET['Employees'])==false):
        ?>
        <?php foreach ($user->tasks as $task) {
            ?>
            <?php // Initialises every task as an invisible card
            if($task->status_id==3){
                $OverDue += 1;
                array_push($allData, array('name'=>'OverDue', 'value'=>1, 'dueDate'=> $task->due_date));
                }
                $allTotal += 1;
                array_push($allData, array('name'=>'Total', 'value'=>1, 'dueDate'=> $task->due_date));
            }
        ?>
<?php endif; endforeach; ?>

<?php
    $BarAllData = Array(); // get all task ratio
    $BarInProgress = 0;
    $BarCompleted = 0;
    $BarOverDue = 0;
    $BarAttentionNeeded = 0;
?>
    <?php foreach ($users as $user):?>
        <?php
            if((isset($_GET['Employees']) && ($_GET['Employees']==$user->id || $_GET['Employees']=="blank")) || isset($_GET['Employees'])==false):
                ?>
                <?php foreach ($user->tasks as $task) {
                    ?>
                    <?php // Initialises every task as an invisible card
                    if($task->status_id==3){
                        $BarOverDue += 1;
                        array_push($BarAllData, array('name'=>'OverDue', 'value'=>1, 'dueDate'=> $task->due_date, 'itemStyle' => Array('color'=>'#ff7070')));
                    }else if($task->status_id==2){
                        $BarCompleted += 1;
                        array_push($BarAllData, array('name'=>'Completed', 'value'=>1, 'dueDate'=> $task->due_date, 'itemStyle' => Array('color'=>'#5470c6')));
                    }else if($task->status_id==4){
                        $BarAttentionNeeded += 1;
                        array_push($BarAllData, array('name'=>'AttentionNeeded', 'value'=>1, 'dueDate'=> $task->due_date, 'itemStyle' => Array('color'=>'#91cc75')));
                    }else if($task->status_id==1){
                        $BarInProgress += 1;
                        array_push($BarAllData, array('name'=>'InProgress', 'value'=>1, 'dueDate'=> $task->due_date, 'itemStyle' => Array('color'=>'#fac858')));
                    }
                }
                ?>
        <?php endif; endforeach; ?>


    <?php
    $advanceData = Array();//get overdue and total data from db
    $advance_e = 0;
    $overdue_e = 0;
    ?>
    <?php foreach ($users as $user):?>
        <?php
            if((isset($_GET['Employees']) && ($_GET['Employees']==$user->id || $_GET['Employees']=="blank")) || isset($_GET['Employees'])==false):
                ?>
                <?php foreach ($user->tasks as $task) {
                    ?>
                    <?php // Initialises every task as an invisible card
                        if($task->status_id==3){
                            $OverDue += 1;
                            array_push($advanceData, array('name'=>'Later Delivery', 'value'=>1, 'dueDate'=> $task->due_date, 'itemStyle' => Array('color'=>'#ff7070')));
                        }else if($task->status_id==2){
                            if(strtotime($task->complete_date) < strtotime($task->due_date)){
                                $advance_e += 1;
                                array_push($advanceData, array('name'=>'Early delivery', 'value'=>1, 'dueDate'=> $task->due_date, 'itemStyle' => Array('color'=>'#91cc75')));
                            }else if(strtotime($task->complete_date) > strtotime($task->due_date)){
                                $overdue_e += 1;
                                array_push($advanceData, array('name'=>'Later Delivery', 'value'=>1, 'dueDate'=> $task->due_date, 'itemStyle' => Array('color'=>'#ff7070')));
                            }
                        }

                }
            ?>
<?php endif; endforeach; ?>

<style>
    .echarts-box{
        margin-top:40px;
        padding: 10px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 7px 14px 0 rgb(60 66 87 / 10%), 0 3px 6px 0 rgb(0 0 0 / 7%);
    }
    .echarts-box h1{
    }
    .echarts-box .echarts-inner{
        display:flex;
    }
    .echarts-list{
        width:100%;
        height:400px;
        margin-top:40px;
        border-bottom:1px solid #d9d9d9;
    }
</style>
<div class="users index content" onload="document.Employees.submit()">

    <div style="display: flex; flex-direction: row">
        <button onclick = "nextWeek()" style="margin: auto" class="employee_view"> < </button>
        <h1 id="Month_Text"> August 2021 </h1>
        <!--<h1 id="misc"></h1> -->
        <button onclick = "prevWeek()" style="margin: auto" class="employee_view"> > </button>
    </div>

    <script src = "https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.1/moment.min.js"></script>
    <script src ="webroot/js/jquery-1.4.1.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
    <script>
        var Monday = "";
        var Tuesday = "";
        var Wednesday = "";
        var Thursday = "";
        var Friday = "";

        var currentMonday = new Date();


        function getMonday(d) {
            d = new Date(d);
            var day = d.getDay(),
                diff = d.getDate() - day + (day == 0 ? -6 : 1); // adjust when day is sunday
            return new Date(d.setDate(diff));
        }
        function addDays(date, days) {
            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            var result = new Date(date);
            result.setDate(result.getDate() + days);
            return result.getDate().toString() + ' ' + months[result.getMonth()] + ' ' + result.getFullYear().toString();
        }
        function getDateString(date, days) {
            var result = new Date(date);
            result.setDate(result.getDate() + days);
            var ddmm = result.toLocaleString('en-au').slice(0,5);
            return ddmm +  '/' + result.getFullYear().toString().slice(2);
        }
        function doSomething() {
            var thisMonday = currentMonday;
            //compare dates and turn display to visible if good

            var elements =  document.getElementsByClassName('task-card'); //Gets all hidden task card elements. Turns into an array so we can sort them
            elements = Array.prototype.slice.call(elements, 0);
            elements.sort(function(a, b){return a.id - b.id});
            let names = document.getElementsByClassName('names');//get array of names that exist.
            console.log(elements.length);
            //const length = elements.length;
            let MD = getDateString(thisMonday, 0);
            let TuD = getDateString(thisMonday, 1);
            let WD = getDateString(thisMonday, 2);
            let ThD = getDateString(thisMonday, 3);
            let FD = getDateString(thisMonday, 4);
            for(let i = 0; i < elements.length; i++){
                //set every element to invisible to start
                elements[i].style.display = "none";
            }
            for(let j = 0; j < names.length; j++){
                for (let i = 0; i < elements.length; i++) {
                    elements.sort(function(a, b){return a.id - b.id}); //need to sort every iteration by id otherwise order gets messed up every .append()
                    //for testing elements[i].innerHTML = "foo";
                    if(elements[i].childNodes[5].innerText === names[j].id){
                        let id = elements[i].id;
                        //If task name is equal then check for dates and then display task
                        switch (elements[i].childNodes[3].innerText){
                            case MD:
                                document.getElementById("M_TD " + names[j].id).append(elements[i]);
                                document.getElementById(id).style.display = "block";
                                break;
                            case TuD:
                                document.getElementById("Tu_TD " + names[j].id).append(elements[i]);
                                document.getElementById(id).style.display = "block";
                                break;
                            case WD:
                                document.getElementById("W_TD " + names[j].id).append(elements[i]);
                                document.getElementById(id).style.display = "block";
                                break;
                            case ThD:
                                document.getElementById("Th_TD " + names[j].id).append(elements[i]);
                                document.getElementById(id).style.display = "block";
                                break;
                            case FD:
                                document.getElementById("F_TD " + names[j].id).append(elements[i]);
                                document.getElementById(id).style.display = "block";
                                break;
                            default:
                                elements[i].style.display = "none";
                        }
                    }
                }
            }
        }

        function changeDates(currentMonday) {

            var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

            document.getElementById('Month_Text').innerText = months[currentMonday.getMonth()] + " " + currentMonday.getFullYear().toString();

            Monday = "Mon" + " " + addDays(currentMonday, 0);
            Tuesday = "Tue" + " " + addDays(currentMonday, 1);
            Wednesday = "Wed" + " " + addDays(currentMonday, 2);
            Thursday = "Thu" + " " + addDays(currentMonday, 3);
            Friday = "Fri" + " " + addDays(currentMonday, 4);

            document.getElementById('Mon').innerHTML = Monday;
            document.getElementById('Tue').innerHTML = Tuesday;
            document.getElementById('Wed').innerHTML = Wednesday;
            document.getElementById('Thu').innerHTML = Thursday;
            document.getElementById('Fri').innerHTML = Friday;

            doSomething();
            var navData = <?php echo json_encode($navData) ?>; //change php env to js env
            var allData = <?php echo json_encode($allData) ?>; //get data
            var currentYear = new Date(currentMonday).getFullYear();
            var currentMonth = new Date(currentMonday).getMonth()+1;
            var BarAllData = <?php echo json_encode($BarAllData) ?>; //get data
            var advanceData = <?php echo json_encode($advanceData) ?>; //get data
            navData = navData.filter(item=>{

                if(item.dueDate.split("-")){
                    if(item.dueDate.split("-")[0] == currentYear && item.dueDate.split("-")[1] == currentMonth){
                        return true
                    }
                }
            })
            allData = allData.filter(item=>{
                if(item.dueDate.split("-")){
                    if(item.dueDate.split("-")[0] == currentYear && item.dueDate.split("-")[1] == currentMonth){
                        return true
                    }
                }
            })
            BarAllData = BarAllData.filter(item=>{
                if(item.dueDate.split("-")){
                    if(item.dueDate.split("-")[0] == currentYear && item.dueDate.split("-")[1] == currentMonth){
                        return true
                    }
                }
            })
            advanceData = advanceData.filter(item=>{
                if(item.dueDate.split("-")){
                    if(item.dueDate.split("-")[0] == currentYear && item.dueDate.split("-")[1] == currentMonth){
                        return true
                    }
                }
            })

            SetNewformatAllData(allData)
            SetNewformatData(navData)
            SetFormatBarData(BarAllData)
            SetAdvanceBarData(advanceData)
        }
        function nextWeek(){
            currentMonday.setDate(currentMonday.getDate() - 7);
            changeDates(currentMonday);
        }
        function prevWeek(){
            currentMonday.setDate(currentMonday.getDate() + 7);
            changeDates(currentMonday);
        }

        function SetNewformatData(navData){
            function process(arr) {
                const cache = [];
                for (const t of arr) {
                    if (cache.find(c => c.name === t.name && c.dueDate === t.dueDate)) {   //delete repeat thing, if duedate and task name are same , value ++
                    cache.find(c => c.name === t.name && c.dueDate === t.dueDate).value += 1
                    }else{
                    cache.push(t);
                    }
                }
                return cache;
            }
            var formatData = process(navData)
            formatData.forEach(item=>{    //add default value
                if(item.name == 'Completed'){
                    item['Completed'] = item.value
                }else{
                    item['NotCompleted'] = item.value
                }
            })
            var NewformatData = newProcess(formatData)  //according to date delete repeat thing again
            NewformatData.sort(function(a, b){
                return new Date(a.dueDate).getTime() - new Date(b.dueDate).getTime()
            })
            function newProcess(arr) {
                const cache = [];
                for (const t of arr) {
                    if (cache.find(c => c.dueDate === t.dueDate)) {
                        if(cache.find(c => c.dueDate === t.dueDate).name == 'Completed'){
                        cache.find(c => c.dueDate === t.dueDate)['NotCompleted']  = t.NotCompleted
                        }else{
                        cache.find(c => c.dueDate === t.dueDate)['Completed']  = t.Completed
                        }
                    }else{
                        cache.push(t);
                    }
                }
                return cache;
            }
            var dom = document.getElementById("total");
            var myChart = echarts.init(dom);
            var option;

            option = {
                title: {
                    text: 'total tasks',
                    y: 'top',
                    x:'left'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['Completed', 'Not Completed']
                },
                calculable: true,
                xAxis: [
                    {
                        type: 'category',
                        data: NewformatData.map(i=>i.dueDate),

                    }
                ],
                yAxis: [
                    {
                        type: 'value',
                        minInterval: 1,
                    }
                ],
                series: [
                    {
                        name: 'Completed',
                        type: 'bar',
                        data: NewformatData.map(i=>i.Completed || 0),
                        barMinHeight: 4,
                        barMaxWidth: 50,
                        itemStyle: {
                            color:'green'
                        },
                    },
                    {
                        name: 'Not Completed',
                        type: 'bar',
                        barMaxWidth: 50,
                        data: NewformatData.map(i=>i.NotCompleted || 0),
                        barMinHeight: 4,
                    }
                ]
            };

            if (option && typeof option === 'object') {
                myChart.setOption(option);
            }
        }

        function SetNewformatAllData(allData){
            function allProcess(arr) {
                const cache = [];
                for (const t of arr) {
                    if (cache.find(c => c.name === t.name && c.dueDate === t.dueDate)) {   //delete repeat things
                        cache.find(c => c.name === t.name && c.dueDate === t.dueDate).value += 1
                    }else{
                        cache.push(t);
                    }
                }
                return cache;
            }
            var formatAllData = allProcess(allData)
            formatAllData.forEach(item=>{    //add default value
                if(item.name == "OverDue"){
                    item['OverDue'] = item.value
                }else{
                    item['Total'] = item.value
                }
            })
            var NewformatAllData = newAllProcess(formatAllData)  //according to date delete repeat thing again
            NewformatAllData.sort(function(a, b){
                return new Date(a.dueDate).getTime() - new Date(b.dueDate).getTime()
            })
            function newAllProcess(arr) {
                const cache = [];
                for (const t of arr) {
                    if (cache.find(c => c.dueDate === t.dueDate)) {
                        if(cache.find(c => c.dueDate === t.dueDate).name == 'OverDue'){
                            cache.find(c => c.dueDate === t.dueDate)['Total']  = t.Total
                        }else{
                            cache.find(c => c.dueDate === t.dueDate)['OverDue']  = t.OverDue
                        }
                    }else{
                        cache.push(t);
                    }
                }
                return cache;
            }
            var overdue = document.getElementById("overdue");
            var overdueMyChart = echarts.init(overdue);
            var overDueOption;
            overDueOption = {
                title: {
                    text: 'overdue tasks',
                    y: 'top',
                    x:'left'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['Total', 'OverDue']
                },
                calculable: true,
                xAxis: [
                    {
                        type: 'category',
                        data: NewformatAllData.map(i=>i.dueDate)
                    }
                ],
                yAxis: [
                    {
                        type: 'value',
                        minInterval: 1,
                    }
                ],
                series: [
                    {
                        name: 'OverDue',
                        type: 'bar',
                        data: NewformatAllData.map(i=>i.OverDue || 0),
                        itemStyle:{
                            color:'#b80c3c'
                        },
                        barMinHeight: 4,
                        barMaxWidth: 50,
                    },
                    {
                        name: 'Total',
                        type: 'bar',
                        data: NewformatAllData.map(i=>i.Total || 0),
                        itemStyle: {
                            color:'green'
                        },
                        barMinHeight: 4,
                        barMaxWidth: 50,
                    }
                ]
            }
            if (overDueOption && typeof overDueOption === 'object') {
                overdueMyChart.setOption(overDueOption);
            }
        }

        function SetFormatBarData(BarAllData){
            function barProcess(arr) {
                const cache = [];
                for (const t of arr) {
                    if (cache.find(c => c.name === t.name)) {   //delete repeat things
                        cache.find(c => c.name === t.name).value += 1
                    }else{
                        cache.push(t);
                    }
                }
                return cache;
            }
            var formatBarData = barProcess(BarAllData)

            var totalBar = document.getElementById("totalBar");
            var BarMyChart = echarts.init(totalBar);
            var BarOption;
            BarOption  = {
                title: {
                    text: 'All Task Ratio',
                    y: 'bottom',
                    x:'center'
                },
                tooltip: {
                    trigger: 'item'
                },
                legend: {
                    left: 'center'
                },
                series: [
                    {
                    type: 'pie',
                    radius: ['40%', '70%'],
                    data: formatBarData,
                    itemStyle: {
                        borderRadius: 10,
                        borderColor: '#fff',
                        borderWidth: 2,
                        normal:{
                        label:{
                            position : 'inner',
                            formatter : function (params){
                                if(params.percent){
                                    return (params.percent - 0) + '%';
                                }else{
                                    return '';
                                }
                            },
                            textStyle: {
                                color: "#333",
                                fontSize:14,
                                fontWeight:'bold'
                            }
                        },
                        labelLine:{
                            show:true
                        }
                        }
                    },
                    }
                ]
            };
            if (BarOption && typeof BarOption === 'object') {
                BarMyChart.setOption(BarOption);
            }
        }


        function SetAdvanceBarData(advanceData){
            function barProcess(arr) {
                const cache = [];
                for (const t of arr) {
                    if (cache.find(c => c.name === t.name)) {   //delete repeat things
                        cache.find(c => c.name === t.name).value += 1
                    }else{
                        cache.push(t);
                    }
                }
                return cache;
            }
            var advanceBarData = barProcess(advanceData)

            var advanceBar = document.getElementById("advanceBar");
            var BarMyChart = echarts.init(advanceBar);
            var BarOption;
            BarOption  = {
                title: {
                    text: 'Tasks Progress',
                    y: 'bottom',
                    x:'center'
                },
                tooltip: {
                    trigger: 'item'
                },
                legend: {
                    left: 'center'
                },
                color:['green','#b80c3c'],
                series: [
                    {
                    type: 'pie',
                    radius: ['40%', '70%'],
                    data: advanceBarData,
                    itemStyle: {
                        borderRadius: 10,
                        borderColor: '#fff',
                        borderWidth: 2,
                        normal:{
                        label:{
                            position : 'inner',
                            formatter : function (params){
                                if(params.percent){
                                    return (params.percent - 0) + '%';
                                }else{
                                    return '';
                                }
                            },
                            textStyle: {
                                color: "#333",
                                fontSize:14,
                                fontWeight:'bold'
                            }
                        },
                        labelLine:{
                            show:true
                        }
                        }
                    },
                    }
                ]
            };
            if (BarOption && typeof BarOption === 'object') {
                BarMyChart.setOption(BarOption);
            }
        }
    </script>

        <table>
            <thead><!--Form for selecting drop down user, sets user id in URL -->
            <div class="custom-select">
                <form id="Employees">
                    <label for="Employees">Select an Employee:</label>
                    <div style="display: inline-flex"> <!--groups so they can be on same line-->
                        <select name="Employees" id="Employees">
                            <option value ="blank"></option>
                            <?php foreach ($users as $user):?>
                            <option value ="<?=$user->id?>"><?=$user->name?></option>
                            <?php endforeach ?>
                        </select>
                        <input type="submit" value="Submit">
                        <?= $this->Html->link(__('New User'), ['action' => 'add'], ['class' => 'button new_user']) ?>
                    </div>
                </form>
            </div>
                <tr>
                    <th class="text-center"><?= $this->Paginator->sort('name') ?></th>
                    <th class="text-center" id = "Mon"></th>
                    <th class="text-center" id = "Tue"></th>
                    <th class="text-center" id = "Wed"></th>
                    <th class="text-center" id = "Thu"></th>
                    <th class="text-center" id = "Fri"></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user):
                //(if url param is set AND (its either blank or an employeeID)) OR if it isn't set
                //Gets a specific employee ONLY, OR gets all employees if it set to 'blank' or not set
                if((isset($_GET['Employees']) && ($_GET['Employees']==$user->id || $_GET['Employees']=="blank")) || isset($_GET['Employees'])==false):
                    foreach ($user->tasks as $task) :?>
                        <?php
                        if ((strtotime($task->due_date) > $minD && strtotime($task->due_date) < $maxD)): //Only check for +/- 2months. To avoid excessive lag whilst dealing with most circumstances ?>
                            <!--Initialises every task as an invisible card?-->
                            <li class="task-card" style = "display : none" id =<?=$task->id?>>
                                <h4 style = "margin-bottom: 0rem"><?=$task->title?></h4>
                                <p class="due_time"><?=date_format($task->due_date, "d/m/y")?></p>
                                <p class ="person"><?=$user->id?></p>
                                <p class="desc" ><?=substr($task->description,0,20)?>...</p>
                            </li>
                        <?php endif ?>
                    <?php endforeach;?>
                    <tr>
                        <td class = "names"  id = <?=$user->id?>><?= $this->Html->link(__(h($user->name) . ' ' . $user->last_name[0]), ['action' => 'view', $user->id]) ?></td>
                        <td id = "M_TD <?=$user->id?>"></td>
                        <td id = "Tu_TD <?=$user->id?>"></td>
                        <td id = "W_TD <?=$user->id?>"></td>
                        <td id = "Th_TD <?=$user->id?>"></td>
                        <td id = "F_TD <?=$user->id?>"></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
<?php
echo $currentUserId;
echo $currentUserName;
if(isset($_GET['Employees'])) :
    if ($_GET['Employees'] == $currentUserId || $currentUserAccess <=3): ?>
<?php endif; endif;?>
<div class="echarts-box">
    <div>
        <h1>
            <?php foreach ($users as $user){
                if(isset($_GET['Employees'])){
                    if($_GET['Employees']== $user->id){
                        echo $user->name;
                    }
                }
            }
            ?>

        </h1>
    </div>
    <div class="echarts-inner"  style="margin-bottom:26px;">
        <div class="echarts-list" id="total"></div>
    </div>
    <div class="echarts-inner"  style="margin-bottom:26px;">
        <div class="echarts-list" id="overdue"></div>
    </div>
    <div class="echarts-inner" style="padding-top:26px;">
        <div class="echarts-list" id="totalBar"></div>
        <div class="echarts-list" id="advanceBar"></div>
    </div>
</div>

<script>
    window.onload = function(){
        //gets the current Monday date and converts into a readable format
        $('Employees').submit();
        currentMonday = getMonday(new Date());
        changeDates(currentMonday);
        doSomething();
        //appends the modals to the taskcards
        let tasks = document.getElementsByClassName("task-card");
        let modals = document.getElementsByClassName("modals");
        for(let i = 0; i <tasks.length; i++){
            for (let j = 0; j <modals.length; j++){
                if (tasks[i].id === modals[j].id){
                    tasks[i].append(modals[j]);
                    modals[j].style.display = "block";
                }
            }
        }
        changeDates(currentMonday);
    }
</script>

