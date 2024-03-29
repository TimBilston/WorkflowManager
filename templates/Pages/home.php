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
use App\Model\Table\SubtasksTable;

echo $this->Html->css(['tasks', 'home', 'modal', 'buttons', 'bootstrap', 'kanban', 'custom']);
echo $this->Html->script(['jquery-1.4.1', 'bootstrap.min']);
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
    <script type="text/javascript" src="js/jquery-1.4.1.js"></script>
    <!--        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
</head>
<div style="display: flex; flex-direction: row;">
    <h1 style="left: 15rem;text-decoration: underline;">My Tasks</h1>
    <button onclick="nextWeek()" style="margin: auto" class="dashboard"> <</button>
    <h1 id="Month_Text"> August 2021 </h1>
    <button onclick="prevWeek()" style="margin: auto" class="dashboard"> ></button>
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
                    <h2 id="Tuesday"></h2>
                    <!--<svg class="drag-header-more" data-target="options2" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/</svg>-->
                </span>
            <div class="drag-options" id="options2"></div>
            <ul class="drag-inner-list" id="2">

            </ul>
        </li>
        <li class="drag-column drag-column-needs-review">
                <span class="drag-column-header">
                    <h2 id="Wednesday"></h2>
                    <!--<svg data-target="options3" class="drag-header-more" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/</svg>-->
                </span>
            <div class="drag-options" id="options3"></div>
            <ul class="drag-inner-list" id="3">
            </ul>
        </li>
        <li class="drag-column drag-column-approved">
                <span class="drag-column-header">
                    <h2 id="Thursday"></h2>
                    <!--<svg data-target="options4" class="drag-header-more" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/</svg>-->
                </span>
            <div class="drag-options" id="options4"></div>
            <ul class="drag-inner-list" id="4">

            </ul>
        </li>
        <li class="drag-column drag-column-on-hold">
                <span class="drag-column-header">
                    <h2 id="Friday"></h2>
                    <!--<svg data-target="options5" class="drag-header-more" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/</svg>-->
                </span>
            <div class="drag-options" id="options5"></div>
            <ul class="drag-inner-list" id="5">

            </ul>
        </li>

    </ul>
</div>
<!-- Footer showing total tasks in a week  -->
<footer class="w3-container w3-padding-64 w3-center w3-opacity w3-xlarge" style="margin-top:20px; background: #ffebeb; ">
    <b style="color:#000000"><i class="fa fa-table"></i> This Week Total: <span id="tasksTotal" style="color:#b80c3c;"> 0 </span>
        Tasks</b>
</footer>
</html>
<script>
    let container = `<div id="container" style="width:220px;height: 300px"></div>`
    $("#navbar").append(container)

    var currentMonday = new Date();
    var tasksTotal = 0;
    window.onload = function () {
        //gets the current Monday date and converts into a readable format
        //  <!-- Outputs the Titles -->
        // currentMonday = getMonday(new Date());
        // changeDates(currentMonday);
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

    var currentUserId = '<?php echo $currentUserId ?>'

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

        var html = '';
        $('.modals').remove();
        $('.clone').remove();
        $.ajax({
            url: '/tasks/getTask'
            , async: false
            , dataType: 'json'
            , data: {
                begin: Monday
                , end: Friday
                , user_id: currentUserId
            }
            , success: function (result) {
                console.log(result)
                if (result.code == 200) {
                    html = result.data.html;
                    $(document.body).append(result.data.modal);
                } else {
                    alert('please try later');
                }
            }
        });

        //var html = '<?php //echo $html?>//'
        var currentUser = '<?php echo $currentUserName ?>'

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
                itemStyle: {
                    color: 'green'
                }
            },
            {
                name: 'Not Completed',
                value: 0,
                itemStyle: {
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
        $(html).each((index, element) => {
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
                } else {
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

        currentData.forEach(item => {
            if (item.value == 0) {
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
                position: ['10%', '80%'],
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
                        normal: {
                            label: {
                                position: 'inner',
                                formatter: function (params) {
                                    console.log(params)
                                    if (params.percent) {
                                        return (params.percent - 0) + '%';
                                    } else {
                                        return '';
                                    }
                                },
                                textStyle: {
                                    color: '#fff',
                                    fontSize: 12
                                }
                            },
                            labelLine: {
                                show: true
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

    function appendModals() {
        //appends the modals to the taskcards
        let tasks = document.getElementsByClassName("drag-item");
        let modals = document.getElementsByClassName("modals");
        for (let i = 0; i < tasks.length; i++) {
            for (let j = 0; j < modals.length; j++) {
                if (tasks[i].id === modals[j].id) {
                    var clone = modals[j].cloneNode(true);
                    clone.setAttribute("class", "clone");
                    clone.setAttribute("id", "clone" + j);
                    var viewBtn = "";
                    var modalFade = "";
                    //set target of button and modal id. Cant be duplicate with original modal
                    for (var x = 0; x < clone.childNodes.length; x++) {
                        if (clone.childNodes[x].className == "viewBtn") {
                            clone.childNodes[x].setAttribute("data-target", "#m" + tasks[i].id);//sets target of button
                        } else if (clone.childNodes[x].className == "modal fade") {
                            clone.childNodes[x].setAttribute("id", "m" + tasks[i].id);//sets modal id
                        }
                    }
                    clone.style.display = "inline-block";
                    clone.style.flexDirection = "column";
                    clone.style.align = "center";
                    clone.style.flexWrap = "wrap";
                    clone.style.alignContent = "flex-end";
                    tasks[i].appendChild(clone);
                }
            }
        }
    }

    function nextWeek() {
        currentMonday.setDate(currentMonday.getDate() - 7);
        changeDates(currentMonday);
    }

    function prevWeek() {
        currentMonday.setDate(currentMonday.getDate() + 7);
        changeDates(currentMonday);
    }

    $(document).on('click', '.submit_complete', function () {
        console.log('aa')
        var form = $(this).parents('form');
        $(this).parents('.drag-item').css('background-color', '#a0da92');
        $(this).hide();

        $.ajax({
            url: form.attr('action')
            , type: 'POST'
            , data: form.serialize()
        });
    });

    // Complete function changes task's status to complete
    $(function () {
        currentMonday = getMonday(new Date());
        changeDates(currentMonday);
    });
</script>

<style>
    .w3-light-grey, .w3-hover-light-grey:hover, .w3-light-gray, .w3-hover-light-gray:hover {
        color: #000 !important;
        background-color: #f1f1f1 !important;
    }

    .w3-opacity, .w3-hover-opacity:hover {
        opacity: 0.60;
        -webkit-backface-visibility: hidden;
    }

    .w3-container {
        padding: 0.01em 16px;
        width: 1300px;
        z-index: 98;
    }

    .w3-padding-64 {
        padding-top: 64px !important;
        padding-bottom: 64px !important;
    }

    .w3-center {
        text-align: center !important;
    }

    .w3-xlarge {
        font-size: 24px !important;
    }

    .w3-container:after, .w3-container:before, .w3-panel:after, .w3-panel:before, .w3-row:after, .w3-row:before, .w3-row-padding:after, .w3-row-padding:before, .w3-cell-row:before, .w3-cell-row:after, .w3-topnav:after, .w3-topnav:before, .w3-clear:after, .w3-clear:before, .w3-btn-group:before, .w3-btn-group:after, .w3-btn-bar:before, .w3-btn-bar:after, .w3-bar:before, .w3-bar:after {
        content: "";
        display: table;
        clear: both;
    }

    .rvnm-navbar-box {
        position: fixed;
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
        background: #FFFFFF;
    }

    .rvnm-navbar-box.dark-ruby li {
        border-bottom: 1px solid rgb(184 12 60 / 16%);
        text-align: left;
        list-style: none;
        height: 70px;
        line-height: 80px;
        background: #FFFFFF;
        margin-bottom: 0rem;
        padding-left: 20px;
    }


    .rvnm-navbar-box.dark-ruby li a {
        text-decoration: none;
        height: 100%;
        color: #353C48;
        font-size: 16px;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        letter-spacing: 0px;
        padding-left: 8px;
        font-weight: bold;

    }

    .rvnm-navbar-box.dark-ruby li:hover a {
        background: #b80c3c;
        color: #fff;
    }

    .rvnm-navbar-box.dark-ruby li:hover {
        background: #b80c3c;
    }

    .logo {
        width: 100%;
        height: 80px !important;
        padding: 10px;
        box-sizing: border-box;
        margin-bottom: 30px;
    }

    .logo img {
        width: 100%;
        height: 80px !important;
        display: block;
        cursor: pointer;
    }

</style>


