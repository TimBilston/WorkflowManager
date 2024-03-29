<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
use Cake\Routing\Router;
$tasksTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Tasks');
echo $this->Html->css(['userIndex']);
echo $this->Html->script(['jquery-1.4.1','bootstrap.min']);
//set date range for +/- 3months current date to avoid lag. Tasks are formatted mm/dd/yy
$d = date("m/d/y");
$dPlus = strtotime("+4 months"); //(php is smart can interpret "4 months"
$dMinus = strtotime("-4 months"); //(php is smart can interpret "4 months"
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
<style>
    .names a {
        color: #0071BC;
        text-decoration: underline;
        font-weight: bold;
    }
</style>


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
    $Overdue = 0;
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
                $Overdue += 1;
                array_push($allData, array('name'=>'Overdue', 'value'=>1, 'dueDate'=> $task->due_date));
                }
                $allTotal += 1;
                array_push($allData, array('name'=>'In Progress', 'value'=>1, 'dueDate'=> $task->due_date));
            }
        ?>
<?php endif; endforeach; ?>

<?php
    $BarAllData = Array(); // get all task ratio
    $BarInProgress = 0;
    $BarCompleted = 0;
    $BarOverdue = 0;
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
                        $BarOverdue += 1;
                        array_push($BarAllData, array('name'=>'Overdue', 'value'=>1, 'dueDate'=> $task->due_date, 'itemStyle' => Array('color'=>'#ff7070')));
                    }else if($task->status_id==2){
                        $BarCompleted += 1;
                        array_push($BarAllData, array('name'=>'Completed', 'value'=>1, 'dueDate'=> $task->due_date, 'itemStyle' => Array('color'=>'#5470c6')));
                    }else if($task->status_id==4){
                        $BarAttentionNeeded += 1;
                        array_push($BarAllData, array('name'=>'AttentionNeeded', 'value'=>1, 'dueDate'=> $task->due_date, 'itemStyle' => Array('color'=>'#91cc75')));
                    }else if($task->status_id==1){
                        $BarInProgress += 1;
                        array_push($BarAllData, array('name'=>'In Progress', 'value'=>1, 'dueDate'=> $task->due_date, 'itemStyle' => Array('color'=>'#fac858')));
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
                            $Overdue += 1;
                            array_push($advanceData, array('name'=>'Later Completion', 'value'=>1, 'dueDate'=> $task->due_date, 'itemStyle' => Array('color'=>'#ff7070')));
                        }else if($task->status_id==2){
                            if(strtotime($task->complete_date) < strtotime($task->due_date)){
                                $advance_e += 1;
                                array_push($advanceData, array('name'=>'Early Completion', 'value'=>1, 'dueDate'=> $task->due_date, 'itemStyle' => Array('color'=>'#91cc75')));
                            }else if(strtotime($task->complete_date) > strtotime($task->due_date)){
                                $overdue_e += 1;
                                array_push($advanceData, array('name'=>'Later Completion', 'value'=>1, 'dueDate'=> $task->due_date, 'itemStyle' => Array('color'=>'#ff7070')));
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
<div class="users index content" onload="document.Employees.submit()" style="width: 70vw">

    <div id = "headerMonths">
        <button id = 'BtnNxt' onclick = "nextWeek()" style="margin: auto" class="employee_view"> < </button>
        <h1 id="Month_Text"> August 2021 </h1>
        <!--<h1 id="misc"></h1> -->
        <button id = 'BtnLst' onclick = "prevWeek()" style="margin: auto" class="employee_view"> > </button>
    </div>

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

            if (document.getElementById('kpitoggle').getAttribute('value')=='true') {
                var navData = <?php echo json_encode($navData) ?>; //change php env to js env
                var allData = <?php echo json_encode($allData) ?>; //get data
                var currentYear = new Date(currentMonday).getFullYear();
                var currentMonth = new Date(currentMonday).getMonth() + 1;
                var BarAllData = <?php echo json_encode($BarAllData) ?>; //get data
                var advanceData = <?php echo json_encode($advanceData) ?>; //get data
                navData = navData.filter(item => {

                    if (item.dueDate.split("-")) {
                        if (item.dueDate.split("-")[0] == currentYear && item.dueDate.split("-")[1] == currentMonth) {
                            return true
                        }
                    }
                })
                allData = allData.filter(item => {
                    if (item.dueDate.split("-")) {
                        if (item.dueDate.split("-")[0] == currentYear && item.dueDate.split("-")[1] == currentMonth) {
                            return true
                        }
                    }
                })
                BarAllData = BarAllData.filter(item => {
                    if (item.dueDate.split("-")) {
                        if (item.dueDate.split("-")[0] == currentYear && item.dueDate.split("-")[1] == currentMonth) {
                            return true
                        }
                    }
                })
                advanceData = advanceData.filter(item => {
                    if (item.dueDate.split("-")) {
                        if (item.dueDate.split("-")[0] == currentYear && item.dueDate.split("-")[1] == currentMonth) {
                            return true
                        }
                    }
                })

                SetNewformatAllData(allData)
                SetNewformatData(navData)
                SetFormatBarData(BarAllData)
                SetAdvanceBarData(advanceData)
            }
        }
        function nextWeek(){
            if ($("#BtnNxt").prop("disabled") === false){
                ajaxDatesChange(-7);
            }
        }
        function prevWeek(){
            if ($("#BtnLst").prop("disabled")=== false){
                ajaxDatesChange(+7);
            }
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
                    text: 'Total Task Completion (Daily)',
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
                if(item.name == "Overdue"){
                    item['Overdue'] = item.value
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
                        if(cache.find(c => c.dueDate === t.dueDate).name == 'Overdue'){
                            cache.find(c => c.dueDate === t.dueDate)['Total']  = t.Total
                        }else{
                            cache.find(c => c.dueDate === t.dueDate)['Overdue']  = t.Overdue
                        }
                    }else{
                        cache.push(t);
                    }
                }
                return cache;
            }
            var overdue = document.getElementById("overdue");
            var overdueMyChart = echarts.init(overdue);
            var overdueOption;
            overdueOption = {
                title: {
                    text: 'Overdue Tasks (Daily)',
                    y: 'top',
                    x:'left'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['In Progress', 'Overdue']
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
                        name: 'Overdue',
                        type: 'bar',
                        data: NewformatAllData.map(i=>i.Overdue || 0),
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
            if (overdueOption && typeof overdueOption === 'object') {
                overdueMyChart.setOption(overdueOption);
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
                    text: 'Task Completion Ratio',
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
                    text: 'Task Delivery Ratio',
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
        <table id="employeeTable">
            <thead><!--Form for selecting drop down user, sets user id in URL -->
            <div>
                <form id="Employeesform">
                    <label for="Employees">Select an Employee:</label>
                    <div id="selectDiv"> <!--groups so they can be on same line-->
                        <div id="selectLeft">
                            <select name="Employees" id="Employees">
                                <option selected value ="blank" ></option>
                                <?php foreach ($users as $user):?>
                                <option value ="<?=$user->id?>"><?=$user->name?></option>
                                <?php endforeach ?>
                            </select>
                            <input type="submit" value="Submit">
                        </div>
                        <div id="newEmp"><?= $this->Html->link(__('New Employee'), ['action' => 'add'], ['class' => 'button new_user']) ?></div>
                    </div>
                </form>
            </div>
                <tr id = "headerDays">
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
                if((isset($_GET['Employees']) && ($_GET['Employees']==$user->id || $_GET['Employees']=="blank")) || isset($_GET['Employees'])==false):?>

                    <tr>
                        <td class = "names"  id = <?=$user->id?>><?= $this->Html->link(__(h(ucfirst($user->name)) . ' ' . $user->last_name[0]), ['action' => 'view', $user->id]) ?></td>
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
if(isset($_GET['Employees'])) {
    if ($_GET['Employees'] == $currentUserId || $currentUserAccess <= 3) {
        echo '<p id = "kpitoggle" style = "display:none" value="true"></p>';
    } else {
        echo '<p id = "kpitoggle" style = "display:none" value="false"></p>';
    }
} ?>
<div class="echarts-box" id = "echarts">
    <div>
        <h1>
            <?php foreach ($users as $user){
                if(isset($_GET['Employees'])) {
                    if ($_GET['Employees'] == $currentUserId || $currentUserAccess <= 3) {
                        if ($_GET['Employees'] == $user->id) {
                            echo $user->name;
                        }
                    }
                }
            }
            ?>

        </h1>
    </div>
    <div class="echarts-inner" style="padding-top:26px;">
        <div class="echarts-list" id="totalBar"></div>
        <div class="echarts-list" id="advanceBar"></div>
        <div class="echarts-inner" style="padding-top:26px;">
            <div class="echarts-list" id="totalBar"></div>
            <div class="echarts-list" id="advanceBar"></div>
        </div>
        <!-- <div class="echarts-inner"  style="margin-bottom:26px;">
            <div class="echarts-list" id="total"></div>
        </div>
        <div class="echarts-inner"  style="margin-bottom:26px;">
            <div class="echarts-list" id="overdue"></div>
        </div> -->
    </div>
    <div class="echarts-inner"  style="margin-bottom:26px;">
        <div class="echarts-list" id="total"></div>
    </div>
    <div class="echarts-inner"  style="margin-bottom:26px;">
        <div class="echarts-list" id="overdue"></div>
    </div>

</div>

<script>
    window.onload = function(){
        //gets the current Monday date and converts into a readable format
        refresh();
        currentMonday = getMonday(new Date());
        checkKPIs();
        changeDates(currentMonday);
        ajaxDatesChange(+0);
    }
    function checkKPIs(){
        let value = document.getElementById('kpitoggle').getAttribute('value');
        if(value == 'true'){
            document.getElementById('echarts').style.display = "block";
        }
        else{
            document.getElementById('echarts').style.display = "none";
        }
    }
    function refresh(){ //auto submits the selector. Bugs if left un-submitted
        const queryString = window.location.search;
        //ajaxDatesChange(+0);
        const urlParams = new URLSearchParams(queryString);
        if (urlParams.has('Employees')){

        }
        else{
            document.getElementById("Employeesform").submit();
        }
    }
</script>

<div id="hiddenTasks"style="display: block"></div>


<script>
    function ajaxDatesChange(day) {//gets the new tasks for the new week
        loading();
        var s3 = performance.now();
        console.log('getting tasks from ', currentMonday.getDate());
        $("li").remove(".task-card"); //delete all current task cards
        currentMonday.setDate(currentMonday.getDate() + day);//changes week based on what was passed in
        let datetoParse = currentMonday.toDateString();
        $.get("users/changeDates/"+datetoParse, function (data, status) {//ajax sends to ChangeDates function, passes in currentMonday
            var e3 = performance.now();
            console.log(`Getting Tasks took ${e3 - s3} milliseconds`)
            $("#hiddenTasks").append(data);
            changeDates(currentMonday);
            appendTasks()//append Modals to tasks
            finishLoading();
        });
    }
    function loading(){
        $("#BtnNxt").prop("disabled", true);
        $("#BtnLst").prop("disabled", true);
        $('#employeeTable').addClass('loading');
    }
    function finishLoading(){
        $("#BtnNxt").prop("disabled", false);
        $("#BtnLst").prop("disabled", false);
        $('#employeeTable').removeClass('loading');
    }
    function appendTasks(){
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
        for(let j = 0; j < names.length; j++){
            for (let i = 0; i < elements.length; i++) {
          //      elements.sort(function(a, b){return a.id - b.id}); //need to sort every iteration by id otherwise order gets messed up every .append()
                //for testing elements[i].innerHTML = "foo";

                if(elements[i].childNodes[5].innerText === names[j].id){//Tasks.name === name on table
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
                    }
                }
            }
        }
    }
    function appendModals(){
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
    }
</script>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-id="@fat">Open modal for @fat</button>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="exampleModalLabel"></h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <aside class="column">
                        <div class="side-nav">
                            <h4 class="heading"><?= __('Actions') ?></h4>
                            <?= $this->Html->link(__('Edit Task'), ['controller' => 'Tasks', 'action' => 'edit', $task->id], ['class' => 'side-nav-item']) ?>
                            <!--<button class = "linkButton" onclick = "editForm()">Edit Task</button>-->
                            <?= $this->Form->postLink(__('Delete Task'), ['controller' => 'Tasks', 'action' => 'delete', $task->id], ['confirm' => __('Are you sure you want to delete # {0}?', $task->id), 'class' => 'side-nav-item']) ?>
                        </div>
                    </aside>
                    <div class="column-responsive column-80">
                        <div class="tasks view content"><!-- Echo view content for tasks-->
                            <div class = "task-modal-content">
                                <?= $this->Form->end() ?>
                                <?= $this->Form->create($task, ['url' => ['controller' => 'Tasks','action' => 'edit', $task->id]]); ?>
                                <fieldset>
                                    <div  class="desc"><h4>Description</h4></div>
                                    <p id = "Mod-desc" class = "displayform"></p>
                                    <div class="row" style="padding: 20px">

                                        <div class = "date">
                                            <div class="due_time"><h4>Due Date</h4></div>
                                            <p id="Mod-due_date" class = "displayform"></p>
                                        </div>
                                    </div>
                                    <div class ="row" style="margin-inline: auto;">
                                        <div class =""><h4>Recurrence</h4></div>
                                        <div class =""><h4>No of Recurrence</h4></div>
                                    </div>
                                    <div class="row" style="display: flex">
                                        <p id="Mod-recurrence_type" class = "displayform" style="padding-left: 56px;"></p>
                                        <p id="Mod-no_of_recurrence" class = "displayform" style="padding-left: 95px;"></p>
                                    </div>
                                    <div class ="employee"><h4>Assigned Employee</h4></div>
                                    <p id="Mod-employee_name" class = "displayform"></p>

                                    <div class="person"><h4>Client</h4></div>
                                    <p id="Mod-client_name" class = "displayform"></p>

                                    <div class="status"><h4>Status</h4></div>
                                    <p id="Mod-status_title" class = "displayform"></p>

                                </fieldset>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#exampleModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var taskID = button.data('id'); // Extract info from data-* attributes
        $.get("users/getModal/"+taskID, function (data, status) {//ajax sends to getModal function, passes in TaskID
            //return data from ajax call here
            let taskDetails = JSON.parse(data);
            console.log(taskDetails);
            var modal = $('#exampleModal')
            buildModal(modal,taskDetails);
        });
    })
    function buildModal(modal,task){
        //builds modal based on what is returned in ajax get
        modal.find('.modal-title').text(task.title)
        modal.find('.modal-body #Mod-desc').text(task.description);
        modal.find('.modal-body #Mod-due_date').text(task.due_date);
        modal.find('.modal-body #Mod-recurrence_type').text(task.recurrence.recurrence);
        modal.find('.modal-body #Mod-no_of_recurrence').text(task.recurrence.no_of_recurrence);
        modal.find('.modal-body #Mod-employee_name').text(task.user.name);
        modal.find('.modal-body #Mod-client_name').text(task.client.name);
        modal.find('.modal-body #Mod-status_title').text(task.status.name);
    }
</script>
