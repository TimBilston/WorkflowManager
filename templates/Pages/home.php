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
<!--    <script type = "text/javascript" src = "js/jquery-1.4.1.js" ></script>-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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



            if ($task->status->name == 'Completed') {
                $bgColor = '#a0da92';
            } else {
                $bgColor = '';
            }

            $html .= '<li class="drag-item" id="'.$task->id.'" style="background-color: ' . $bgColor . '">'.
                '<h1 title='.$task->title.'>'.$task->title.'</h1>'.
                '<p class="due_time" style="visibility: hidden; display: none">'.$task->due_date.'</p>'.
                '<p class="desc" title='.$task->description.'>'.$task->description.'</p>'.
                '<p class="person">'.$clientName.'</p>'.
                '<p class="employee_id" style="visibility: hidden; display: none">'.$task->user->id.'</p>'.
                '<p class="status">'.$task->status->name.'</p>'.
                '<p class="task_process" style="visibility: hidden; display: none">' . $completeCount . '/' . $subTasksCount . '</p>';

            if ($task->status->name != 'Completed') {
                $html .= $this->Form->postButton(__('Complete'), ['controller' => 'tasks', 'action' => 'completeTask', $task->id], ['class' => 'submit_complete', 'type' => 'button']);
            }

            //'<p>'.$this->element('viewTask',['taskID' => $task->id]).'</p >'.      //if u wanna to add inside the card just use like thie line !!!!!!!!!!!!!
            $html .= '</li>';
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
                }
                else{
                    if ($(element).find('.due_time').text() == Monday) {
                        $("#1").append(element);
                        $Completed += 1;
                        currentData.forEach(item => {
                            if (item.name == 'Completed') {
                                item.value = $Completed
                            }
                        })
                    } else if ($(element).find('.due_time').text() == Tuesday) {
                        $("#2").append(element);
                        $Completed += 1;
                        currentData.forEach(item => {
                            if (item.name == 'Completed') {
                                item.value = $Completed
                            }
                        })
                    } else if ($(element).find('.due_time').text() == Wednesday) {
                        $("#3").append(element);
                        $Completed += 1;
                        currentData.forEach(item => {
                            if (item.name == 'Completed') {
                                item.value = $Completed
                            }
                        })
                    } else if ($(element).find('.due_time').text() == Thursday) {
                        $("#4").append(element);
                        $Completed += 1;
                        currentData.forEach(item => {
                            if (item.name == 'Completed') {
                                item.value = $Completed
                            }
                        })
                    } else if ($(element).find('.due_time').text() == Friday) {
                        $("#5").append(element);
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


    <div style="display: flex; flex-direction: row;">
        <h1 style="left: 15rem;text-decoration: underline;">My Tasks</h1>
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

<script>
    $(function() {
        $(document).on('click', '.submit_complete', function () {
            var form = $(this).parents('form');
            $(this).parents('.drag-item').css('background-color', '#a0da92');
            $(this).hide();

            $.ajax({
                url: form.attr('action')
                , type: 'POST'
                , data: form.serialize()
            });
        });
    });
</script>

    <footer class="w3-container w3-padding-64 w3-center w3-opacity w3-xlarge" style="margin-top:20px; background: #ffebeb; ">
        <b style="color:#000000"><i class="fa fa-table"></i> This Week Total: <span id="tasksTotal" style="color:#b80c3c;"> 0 </span> Tasks</b>
    </footer>

</html>

<script type="text/javascript">

$(function(){
    let container = `<div id="container" style="width:220px;height: 300px"></div>`
    $("#navbar").append(container)
})

</script>
