<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.10.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use App\Model\Entity\Task;
$tasksTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Tasks');
use App\Model\Table\SubtasksTable;
echo $this->Html->css(['tasks' , 'home', 'modal', 'buttons', 'bootstrap', 'kanban', 'custom']);
echo $this->Html->script(['jquery-1.4.1','bootstrap.min']);
$this->assign('title', 'Dashboard');

$checkConnection = function (string $name) {
    $error = null;
    $connected = false;
    try {
        $connection = ConnectionManager::get($name);
        $connected = $connection->connect();
    } catch (Exception $connectionError) {
        $error = $connectionError->getMessage();
        if (method_exists($connectionError, 'getAttributes')) {
            $attributes = $connectionError->getAttributes();
            if (isset($attributes['message'])) {
                $error .= '<br />' . $attributes['message'];
            }
        }
    }

    return compact('connected', 'error');
};

if (!Configure::read('debug')) :
    throw new NotFoundException(
        'Please replace templates/Pages/home.php with your own version or re-enable debug mode.'
    );
endif;

$cakeDescription = 'CakePHP: the rapid development PHP framework';


$this->loadHelper('Authentication.Identity');

if ($this->Identity->isLoggedIn()) {
    $currentUserName = $this->Identity->get('name');
    $currentUserId = $this->Identity->get('id');
}

$subTasksCount = 0;
$completeCount = 0;
if (!empty($task->subtasks)) {
    $subTasksCount = count($task->subtasks);
    foreach ($task->subtasks as $v) {
        if ($v->is_complete) {
            $completeCount++;
        }
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= $this->Html->meta('icon') ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>

</head>

<script>
    var currentMonday = new Date();
    var tasksTotal = 0;
    window.onload = function() {
        //gets the current Monday date and converts into a readable format
        //  <!-- Outputs the Titles -->
        currentMonday = getMonday(new Date());
        changeDates(currentMonday);
    }

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
        return result.getMonth() + 1 + '/' + result.getDate().toString() + '/' + result.getFullYear().toString().slice(2)
    }


    function changeDates(currentMonday) {
        var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        document.getElementById('Month_Text').innerText = months[currentMonday.getMonth()] + " " + currentMonday.getFullYear().toString();

        var Monday = getDateString(currentMonday, 0);
        var Tuesday = getDateString(currentMonday, 1);
        var Wednesday = getDateString(currentMonday, 2);
        var Thursday = getDateString(currentMonday, 3);
        var Friday = getDateString(currentMonday, 4);


        document.getElementById('Monday').innerHTML = "Mon" + " " + addDays(currentMonday, 0);
        document.getElementById('Tuesday').innerHTML = "Tue" + " " + addDays(currentMonday, 1);
        document.getElementById('Wednesday').innerHTML = "Wed" + " " + addDays(currentMonday, 2);
        document.getElementById('Thursday').innerHTML = "Thu" + " " + addDays(currentMonday, 3);
        document.getElementById('Friday').innerHTML = "Fri" + " " + addDays(currentMonday, 4);

        <?php
        //$currentMonday =  $_POST['currentMonday'];
        // echo $currentMonday;
        $html = "";
        $query = TableRegistry::getTableLocator()->get('Tasks')->find();// get all data from TasksTable
        $query->contain(['Users']);
        $query->contain(['Status']);
        $query->contain(['Clients']);
        $query->contain(['Subtasks']);

        foreach ($query as $task) {
            //creates each task as a draggable item and sets some info up

//            $html .= '<li class="drag-item"><p><h1>'.
//                $task->title.'</h1></p><p class="due_time">'.
//                $task->due_date.'</p><p>'.
//                $task->description.'</p><p>'.
//                $task->user->name.'</p><p>'.
//                '</p><p class = "button"> '.

//                $this->Html->link(__('View'), ['controller' => 'tasks', 'action' => 'view', $task->id]).' </p ></li>';

            $subTasksCount = 0;
            $completeCount = 0;
            if (!empty($task->subtasks)) {
                $subTasksCount = count($task->subtasks);
                foreach ($task->subtasks as $v) {
                    if ($v->is_complete) {
                        $completeCount++;
                    }
                }
            }

            $clientName = 'No Client';
            if (!$task->client == null){
                $clientName = 'Client: '.$task->client->name;
            }
            $html .= '<li class="drag-item" id="'.$task->id.'">'.
                '<h1 title='.$task->title.'>'.$task->title.'</h1>'.
                '<p class="due_time" style="visibility: hidden; display: none">'.$task->due_date.'</p>'.
                '<p class="desc" title='.$task->description.'>'.$task->description.'</p>'.
                '<p class="person">'.$clientName.'</p>'.
                '<p class="employee_id" style="visibility: hidden; display: none">'.$task->user->id.'</p>'.
                '<p class="status">'.$task->status->name.'</p>'.
                '<p class="task_process">' . $completeCount . '/' . $subTasksCount . '</p>'
                //'<p>'.$this->element('viewTask',['taskID' => $task->id]).'</p >'.      //if u wanna to add inside the card just use like thie line !!!!!!!!!!!!!
                .$this->Form->postButton(__('Complete'), ['controller' => 'tasks', 'action' => 'completeTask', $task->id]).
                '</li>';
    } ?>

        var html = '<?php echo  $html?>'
        var currentUser = '<?php echo $currentUserName ?>'
        var currentUserId = '<?php echo $currentUserId ?>'
        tasksTotal = 0

        //Reset card
        $("#1").html('')
        $("#2").html('')
        $("#3").html('')
        $("#4").html('')
        $("#5").html('')
        var currentData = [
            {
                name: 'Completed',
                value: 0,
                itemStyle:{
                    color: 'green'
                }
            },
            {
                name: 'Not Completed',
                value: 0,
                itemStyle:{
                    color: '#b80c3c'
                }
            }
        ]
        // $navData = array(
        //     array('name'=>'Completed', 'value'=>0, 'itemStyle'=>array('color'=>'green')),
        //     array('name'=>'In Progress', 'value'=>0,'itemStyle'=>array('color'=>'#b80c3c')),
        //     array('name'=>'Over Due', 'value'=>0,'itemStyle'=>array('color'=>'#b8333c')),
        //     array('name'=>'Attention Needed', 'value'=>0,'itemStyle'=>array('color'=>'#23443c'))
        // );
        //if due time = monday ,then add data
        // $NotCompleted = 0;
        $Completed = 0;
        $NotCompleted = 0;
        // $InProgress = 0;
        // $AttentionNeeded = 0;
        // $OverDue = 0;
        $(html).each((index,element)=>{
            if ($(element).find('.employee_id').text() == currentUserId) {
                if ($(element).find('.status').text() != 'Completed') {
                    if ($(element).find('.due_time').text() == Monday) {
                        $("#1").append(element)
                        tasksTotal++
                        $NotCompleted += 1;
                        currentData.forEach(item => {
                            if (item.name == 'Not Completed') {
                                item.value = $NotCompleted
                            }
                        })
                    } else if ($(element).find('.due_time').text() == Tuesday) {
                        $("#2").append(element)
                        tasksTotal++
                        $NotCompleted += 1;
                        currentData.forEach(item => {
                            if (item.name == 'Not Completed') {
                                item.value = $NotCompleted
                            }
                        })
                    } else if ($(element).find('.due_time').text() == Wednesday) {
                        $("#3").append(element)
                        tasksTotal++
                        $NotCompleted += 1;
                        currentData.forEach(item => {
                            if (item.name == 'Not Completed') {
                                item.value = $NotCompleted
                            }
                        })
                    } else if ($(element).find('.due_time').text() == Thursday) {
                        $("#4").append(element)
                        tasksTotal++
                        $NotCompleted += 1;
                        currentData.forEach(item => {
                            if (item.name == 'Not Completed') {
                                item.value = $NotCompleted
                            }
                        })
                    } else if ($(element).find('.due_time').text() == Friday) {
                        $("#5").append(element)
                        tasksTotal++
                        $NotCompleted += 1;
                        currentData.forEach(item => {
                            if (item.name == 'Not Completed') {
                                item.value = $NotCompleted
                            }
                        })
                    }
                }else{
                    if ($(element).find('.due_time').text() == Monday) {

                        $Completed += 1;
                        currentData.forEach(item => {
                            if (item.name == 'Completed') {
                                item.value = $Completed
                            }
                        })
                    } else if ($(element).find('.due_time').text() == Tuesday) {
                        $Completed += 1;
                        currentData.forEach(item => {
                            if (item.name == 'Completed') {
                                item.value = $Completed
                            }
                        })
                    } else if ($(element).find('.due_time').text() == Wednesday) {
                        $Completed += 1;
                        currentData.forEach(item => {
                            if (item.name == 'Completed') {
                                item.value = $Completed
                            }
                        })
                    } else if ($(element).find('.due_time').text() == Thursday) {
                        $Completed += 1;
                        currentData.forEach(item => {
                            if (item.name == 'Completed') {
                                item.value = $Completed
                            }
                        })
                    } else if ($(element).find('.due_time').text() == Friday) {
                        $Completed += 1;
                        currentData.forEach(item => {
                            if (item.name == 'Completed') {
                                item.value = $Completed
                            }
                        })
                    }
                }
            }
        })
        console.log(currentData)
        $("#tasksTotal").text(tasksTotal)
        appendModals();
        //loop through every task and append

        currentData.forEach(item=>{
            if(item.value==0){
                item.itemStyle.color = '#666'
            }
        })
        var dom = document.getElementById("container");
        var myChart = echarts.init(dom);
        var app = {};
        var option;
        option = {
            tooltip: {
                trigger: 'item',
                position:['10%', '80%'],
            },
            legend: {
                top: '5%',
                left: 'center'
            },
            series: [
                {
                    name: 'tasks',
                    type: 'pie',
                    radius: ['0', '70%'],
                    avoidLabelOverlap: false,
                    itemStyle: {
                        borderRadius: 10,
                        borderColor: '#fff',
                        borderWidth: 2,
                        normal:{
                            label:{
                                position : 'inner',
                                formatter : function (params){
                                    console.log(params)
                                    if(params.percent){
                                        return (params.percent - 0) + '%';
                                    }else{
                                        return '';
                                    }
                                },
                                textStyle: {
                                    color: '#fff',
                                    fontSize:12
                                }
                            },
                            labelLine:{
                                show:true
                            }
                        }
                    },

                    label: {
                        show: false,
                        position: 'left'
                    },

                    labelLine: {
                        show: false
                    },
                    data: currentData

                }
            ]
        };
        if (option && typeof option === 'object') {
            myChart.setOption(option);
        }

    }
    function appendModals(){
        //appends the modals to the taskcards
        let tasks = document.getElementsByClassName("drag-item");
        let modals = document.getElementsByClassName("modals");
        for(let i = 0; i <tasks.length; i++){
            for (let j = 0; j <modals.length; j++){
                if (tasks[i].id === modals[j].id){
                    var clone = modals[j].cloneNode(true);
                    clone.setAttribute("class","clone");
                    clone.setAttribute("id","clone"+j);
                    var viewBtn = "";
                    var modalFade = "";
                    //set target of button and modal id. Cant be duplicate with original modal
                    for (var x = 0; x < clone.childNodes.length; x++) {
                        if (clone.childNodes[x].className == "viewBtn") {
                            clone.childNodes[x].setAttribute("data-target","#m"+tasks[i].id);//sets target of button
                        }
                        else if(clone.childNodes[x].className == "modal fade"){
                            clone.childNodes[x].setAttribute("id","m"+tasks[i].id);//sets modal id
                        }
                    }
                    clone.style.display = "flex";
                    clone.style.flexDirection = "column";
                    clone.style.align = "center";
                    clone.style.flexWrap = "wrap";
                    clone.style.alignContent = "flex-end";
                    tasks[i].appendChild(clone);
                }
            }
        }
    }

    function nextWeek(){
        currentMonday.setDate(currentMonday.getDate() - 7);
        changeDates(currentMonday);
    }
    function prevWeek(){
        currentMonday.setDate(currentMonday.getDate() + 7);
        changeDates(currentMonday);
    }
</script>
<?php
$query = TableRegistry::getTableLocator()->get('Users')->find();// get all data from UserTable
$query->contain(['Tasks']);
foreach ($query as $user):
    foreach ($user->tasks as $task):
        ?>
        <div class = "modals" id="<?=$task->id?>"style ="display:none"><?=$this->element('viewTask', ['taskID' => $task->id])?></div>
    <?php endforeach;
endforeach;?>

<style>
    .w3-light-grey, .w3-hover-light-grey:hover, .w3-light-gray, .w3-hover-light-gray:hover {
        color: #000!important;
        background-color: #f1f1f1!important;
    }
    .w3-opacity, .w3-hover-opacity:hover {
        opacity: 0.60;
        -webkit-backface-visibility: hidden;
    }
    .w3-container {
        padding: 0.01em 16px;
        width:1300px;
        z-index: 98;
    }
    .w3-padding-64 {
        padding-top: 64px!important;
        padding-bottom: 64px!important;
    }
    .w3-center {
        text-align: center!important;
    }
    .w3-xlarge {
        font-size: 24px!important;
    }
    .w3-container:after, .w3-container:before, .w3-panel:after, .w3-panel:before, .w3-row:after, .w3-row:before, .w3-row-padding:after, .w3-row-padding:before, .w3-cell-row:before, .w3-cell-row:after, .w3-topnav:after, .w3-topnav:before, .w3-clear:after, .w3-clear:before, .w3-btn-group:before, .w3-btn-group:after, .w3-btn-bar:before, .w3-btn-bar:after, .w3-bar:before, .w3-bar:after {
        content: "";
        display: table;
        clear: both;
    }
</style>
<style>

    .rvnm-navbar-box {
        position:fixed;
        left: 0px;
        top: 0;
        width: 220px;
        background: #4387f5;
        min-height: 50vh;
        overflow-y: hidden;
        z-index: 99;
        height: 100%;
    }
    .rvnm-navbar-box.dark-ruby {
        background:#FFFFFF;
    }
    .rvnm-navbar-box.dark-ruby li{
        border-bottom: 1px solid rgb(184 12 60 / 16%);
        text-align: left;
        list-style: none;
        height: 70px;
        line-height:80px;
        background: #FFFFFF;
        margin-bottom: 0rem;
        padding-left:20px;
    }


    .rvnm-navbar-box.dark-ruby li a{
        text-decoration: none;
        height: 100%;
        color: #353C48;
        font-size:16px;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        letter-spacing:0px;
        padding-left:8px;
        font-weight:bold;

    }
    .rvnm-navbar-box.dark-ruby li:hover a {
        background:#b80c3c;
        color:#fff;
    }
    .rvnm-navbar-box.dark-ruby li:hover{
        background:#b80c3c;
    }
    .logo{
        width:100%;
        height:80px!important;
        padding:10px;
        box-sizing: border-box;
        margin-bottom: 30px;
    }
    .logo img{
        width:100%;
        height:80px!important;
        display:block;
        cursor: pointer;
    }


    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        top: 2px;

        margin-left: auto;
        margin-right: 10px;
        width: 40px;
        height: 24px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(16px);
        -ms-transform: translateX(16px);
        transform: translateX(16px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 22px;
    }

    .slider.round:before {
        border-radius: 50%;
    }


</style>

<!--    <?//= $this->Html->link(__('New Task'), ['controller' => 'tasks', 'action' => 'add'], ['class' => 'button6']) ?>-->
<!--    <?//= $this->Html->link(__('View Users'), ['controller' => 'Users'], ['class' => 'button6']) ?>-->
<!--    <?//= $this->Html->link(__('Create new user'), ['controller' => 'Users', 'action' => 'add'], ['class' => 'button6']) ?>-->


    <div style="display: flex; flex-direction: row;padding-left:220px;">
        <h1 style="position: absolute;right: 92.8rem;text-decoration: underline;">My Tasks</h1>
        <button onclick = "nextWeek()" style="margin: auto" class="dashboard"> < </button>
        <h1 id="Month_Text"> August 2021 </h1>
        <button onclick = "prevWeek()" style="margin: auto" class="dashboard"> > </button>
    </div>


    <div class="drag-container">


        <ul class="drag-list">
            <!-- Monday -->
            <li class="drag-column drag-column-on-hold">
                <span class="drag-column-header">
                    <h2 id="Monday"></h2>
                    <!--<svg class="drag-header-more" data-target="options1" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/</svg>-->
                </span>

                <div class="drag-options" id="options1"></div>

                <ul class="drag-inner-list" id="1">

                </ul>
            </li>
            <li class="drag-column drag-column-in-progress">
                <span class="drag-column-header">
                    <h2 id = "Tuesday"></h2>
                    <!--<svg class="drag-header-more" data-target="options2" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/</svg>-->
                </span>
                <div class="drag-options" id="options2"></div>
                <ul class="drag-inner-list" id="2">

                </ul>
            </li>
            <li class="drag-column drag-column-needs-review">
                <span class="drag-column-header">
                    <h2 id = "Wednesday"></h2>
                    <!--<svg data-target="options3" class="drag-header-more" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/</svg>-->
                </span>
                <div class="drag-options" id="options3"></div>
                <ul class="drag-inner-list" id="3">
                </ul>
            </li>
            <li class="drag-column drag-column-approved">
                <span class="drag-column-header">
                    <h2 id = "Thursday"></h2>
                    <!--<svg data-target="options4" class="drag-header-more" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/</svg>-->
                </span>
                <div class="drag-options" id="options4"></div>
                <ul class="drag-inner-list" id="4">

                </ul>
            </li>
            <li class="drag-column drag-column-on-hold">
                <span class="drag-column-header">
                    <h2 id = "Friday"></h2>
                    <!--<svg data-target="options5" class="drag-header-more" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/</svg>-->
                </span>
                <div class="drag-options" id="options5"></div>
                <ul class="drag-inner-list" id="5">

                </ul>
            </li>

        </ul>
    </div>
    <?php
        $allTasks = TableRegistry::getTableLocator()->get('Tasks')->find()->where([]);
        $allTasks->contain(['Status']);
        $navData = array(
            array('name'=>'Completed', 'value'=>0, 'itemStyle'=>array('color'=>'green')),
            array('name'=>'Not Completed', 'value'=>0,'itemStyle'=>array('color'=>'#b80c3c'))
        );
        $NotCompleted = 0;
        $Completed = 0;
        foreach ($allTasks as $task){
            if($task->status_id == 2){
                $Completed += 1;
                $navData[0]['value'] = $Completed;
                $navData[0]['name'] = 'Completed';
            }else{
                $NotCompleted += 1;
                $navData[1]['value'] = $NotCompleted;
                $navData[1]['name'] = 'Not Completed';
            }

        };
    ?>
    <nav id="navbar1" class="rvnm-navbar-box dark-ruby" style="z-index:100">

        <a href="<?php echo $this->Url->build(['controller' => 'pages', 'action' => 'display'])?>">
            <img src="data:image/png;base64,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l3hmw5h9bubCsQ4CwQB0Wzy8yALSKei1eCLB9ofoHFsfs89zXDh2ngJSGfSXICBxn3thxkdAgu451PsISBEFer+GXIrfwr9wBHbATXCXMQHIR5qkQUQBSUu8E+tRCCxGYTM0w2Uen6KA5Ccg8QBpBwHpR5oCui7h9+J2zxdA3K/HYUDiu+5v0DisOP6DrnkOSPz0dpymQK8oou9JLhybNBDENFHqgORvjJn/44H123jsXZG/rStcONZzQOLXAc+VIiBxAWjXASmmFo8BKQ9n05QlCGeavQqBxTisVUASUUDSEv8O+1sRsyj9Cc/DUuMkPE01HLYpIDkMSPYB0gMpa6scLrLvbVw41usFkP21uF0O/z03wQcUPKP8Cq9T8cZSBKQHqf8rRfbt4Ce9iygg9WYwIB2jvptS/EY6PAck7rcCRkoQkLjv/kwGJPt6641Z7W5N8Fkb4Ahth+stnYA1KhQrooCkJdmYh21wP7RAmeX1r6bQ6hLvc4H2pcrD9lfQ9tNMnbwKmhL8geLjvTLl8alJ0H9d2LchQd8cfXahyL556lvt4d+1HJqhE3bBVmiDgqPtV9P+L0vQvz7N95r6Jvm+VUFTTOtK/PuvSXScS388+FXWJmhM8R27LuF3pBIKoVyCY1KAupSFqwuhqhR9K1J+fwox5RN+Rl3Yv7bI/bkm5fmtBTphC5/XshaQREREREREJAgUkERERERERBSQREREREREFJBEREREREQUkERERERERBSQREREREREFJBEREREREQUkERERERERP6vHA5IAAAAAID8f20D5QMnv+q84nHm7AAAAABJRU5ErkJggg==" alt="" class="logo">
        </a>
        <!-- base64 for logo.png -->
        <li>
        <i class="fa fa-tasks"></i>
            <?= $this->Html->link(__('New Task'), ['controller' => 'tasks', 'action' => 'add'], ['class' => 'text']) ?>
        </li>
        <li>
        <i class="fa fa-user-circle"></i>
            <?= $this->Html->link(__('View Employees'), ['controller' => 'Users'], ['class' => 'text']) ?>
        </li>
        <li>
            <i class="fa fa-bar-chart"></i>
            <?= $this->Html->link(__('KPI'), ['controller' => 'kpi'], ['class' => 'text']) ?>
        </li>
        <li>
        <i class="fa fa-users"></i>
            <?= $this->Html->link(__('View Clients'), ['controller' => 'Clients'], ['class' => 'text']) ?>
        </li>
        <li>
            <i class="fa fa-user-circle"></i>
            <?= $this->Html->link(__('My Account'), ['controller' => 'Users',  'action' => 'view',  $currentUserId], ['class' => 'text']) ?>
        </li>
        <li>
        <i class="fa fa-sign-out"></i>
            <?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout'], ['class' => 'text']) ?>
        </li>
        <li>
            <!-- <i class="fa fa-sign-out"></i> -->
            <div style="display:flex;align-items:center">
                <a class="text">View My Tasks</a>
                <label class="switch">
                    <input type="checkbox" id="toggle" onclick="toggleCheck()">
                    <span class="slider round"></span>
                </label>
            </div>
        </li>
        <div id="container" style="width:220px;height: 300px"></div>
    </nav>
    <footer class="w3-container w3-padding-64 w3-center w3-opacity w3-xlarge" style="margin-top:20px; background: #ffebeb; ">
        <b style="color:#000000"><i class="fa fa-table"></i> This Week Total: <span id="tasksTotal" style="color:#b80c3c;"> 0 </span> tasks</b>
    </footer>
</html>
<script type = "text/javascript" src = "js/jquery-1.4.1.js" ></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">

</script>
