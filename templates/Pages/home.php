<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
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
$this->disableAutoLayout();

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

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    var currentMonday = new Date();

    window.onload = function() {
        //gets the current Monday date and converts into a readable format
        <!-- Outputs the Titles -->
        currentMonday = getMonday(new Date());

        changeDates(currentMonday);

    }

    function getMonday(d) {
        d = new Date(d);
        var day = d.getDay(),
            diff = d.getDate() - day + (day == 0 ? -6 : 1); // adjust when day is sunday
        return new Date(d.setDate(diff));
    }

    function changeDates(currentMonday) {
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        var monthName = months[currentMonday.getMonth()];
        var Monday = (currentMonday.getMonth()+1)+'/'+ (currentMonday.getDate()).toString()+'/'+currentMonday.getFullYear().toString().slice(2) //get every day format
        var Tuesday = (currentMonday.getMonth()+1)+'/'+ (currentMonday.getDate() + 1).toString()+'/'+currentMonday.getFullYear().toString().slice(2)
        var Wednesday = (currentMonday.getMonth()+1)+'/'+ (currentMonday.getDate() + 2).toString()+'/'+currentMonday.getFullYear().toString().slice(2)
        var Thursday = (currentMonday.getMonth()+1)+'/'+ (currentMonday.getDate() + 3).toString()+'/'+currentMonday.getFullYear().toString().slice(2)
        var Friday = (currentMonday.getMonth()+1)+'/'+ (currentMonday.getDate() + 4).toString()+'/'+currentMonday.getFullYear().toString().slice(2)

        document.getElementById('Monday').innerHTML = "Mon" + " " + currentMonday.getDate().toString() + " " + monthName + " " + currentMonday.getFullYear().toString();

        document.getElementById('Tuesday').innerHTML = "Tue" + " " + (currentMonday.getDate() + 1).toString() + " " + monthName + " " + currentMonday.getFullYear().toString();

        document.getElementById('Wednesday').innerHTML = "Wed" + " " + (currentMonday.getDate() + 2).toString() + " " + monthName + " " + currentMonday.getFullYear().toString();

        document.getElementById('Thursday').innerHTML = "Thu" + " " + (currentMonday.getDate() + 3).toString() + " " + monthName + " " + currentMonday.getFullYear().toString();

        document.getElementById('Friday').innerHTML = "Fri" + " " + (currentMonday.getDate() + 4).toString() + " " + monthName + " " + currentMonday.getFullYear().toString();
        <?php
        //$currentMonday =  $_POST['currentMonday'];
        // echo $currentMonday;
        $html = "";
        $query = TableRegistry::getTableLocator()->get('Tasks')->find();// get all data from db
        foreach ($query as $task) {
            //creates each task as a draggable item and sets some info up
            $html .= '<li class="drag-item"><p><h1>'. $task->title.'</h1></p><p class="due_time">'.$task->due_date.'</p><p>'.$task->description.'</p><p class = "button"> '. $this->Html->link(__('View'), ['controller' => 'tasks', 'action' => 'view', $task->id]).' </p></li>';
        } ?>

        var html = '<?php echo  $html ?>'

        $("#1").html('')
        $("#2").html('')
        $("#3").html('')
        $("#4").html('')  //reset card
        $("#5").html('')
        $(html).each((index,element)=>{
            if($(element).find('.due_time').text() == Monday){             //if due time = monday ,then add data
                $("#1").append(element)
            }else if($(element).find('.due_time').text() == Tuesday){
                $("#2").append(element)
            }else if($(element).find('.due_time').text() == Wednesday){
                $("#3").append(element)
            }else if($(element).find('.due_time').text() == Thursday){
                $("#4").append(element)
            }else if($(element).find('.due_time').text() == Friday){
                $("#5").append(element)
            }
        })
    }

    function popup(taskId){

        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the button that opens the modal


        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on the button, open the modal

        modal.style.display = "block";


        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
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
<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<link rel="stylesheet" href="webroot/css/kanban.css">
<link rel="stylesheet" href="webroot/css/custom.css">

    <?= $this->Html->link(__('New Task'), ['controller' => 'tasks', 'action' => 'add'], ['class' => 'button6']) ?>
    <?= $this->Html->link(__('View Users'), ['controller' => 'Users'], ['class' => 'button6']) ?>

    <section class="section">
        <h1>Dashboard</h1>

         </section>

    <!-- The popup/Modal -->
    <div id="myModal" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Some text in the Modal..</p>
        </div>

    </div>
    <div class="drag-container">

        <h2><button type="button" onclick = "nextWeek()"> < </button>
            August 2021
            <button type="button" onclick = "prevWeek()"> > </button> </h2>



        <ul class="drag-list">
            <!-- Monday -->
            <li class="drag-column drag-column-on-hold">
                <span class="drag-column-header">
                    <h2 id="Monday"></h2>
                    <svg class="drag-header-more" data-target="options1" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/</svg>
                </span>

                <div class="drag-options" id="options1"></div>

                <ul class="drag-inner-list" id="1">

                </ul>
            </li>
            <li class="drag-column drag-column-in-progress">
                <span class="drag-column-header">
                    <h2 id = "Tuesday"></h2>
                    <svg class="drag-header-more" data-target="options2" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/</svg>
                </span>
                <div class="drag-options" id="options2"></div>
                <ul class="drag-inner-list" id="2">

                </ul>
            </li>
            <li class="drag-column drag-column-needs-review">
                <span class="drag-column-header">
                    <h2 id = "Wednesday"></h2>
                    <svg data-target="options3" class="drag-header-more" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/</svg>
                </span>
                <div class="drag-options" id="options3"></div>
                <ul class="drag-inner-list" id="3">
                </ul>
            </li>
            <li class="drag-column drag-column-approved">
                <span class="drag-column-header">
                    <h2 id = "Thursday"></h2>
                    <svg data-target="options4" class="drag-header-more" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/</svg>
                </span>
                <div class="drag-options" id="options4"></div>
                <ul class="drag-inner-list" id="4">

                </ul>
            </li>
            <li class="drag-column drag-column-on-hold">
                <span class="drag-column-header">
                    <h2 id = "Friday"></h2>
                    <svg data-target="options4" class="drag-header-more" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/</svg>
                </span>
                <div class="drag-options" id="options4"></div>
                <ul class="drag-inner-list" id="5">

                </ul>
            </li>

        </ul>
    </div>

</html>
<script type = "text/javascript" src = "js/jquery-1.4.1.js" ></script>
<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/45226/dragula.min.js" > </script>

<script type="text/javascript">
    //Kanban board script

    dragula([
        document.getElementById('1'),
        document.getElementById('2'),
        document.getElementById('3'),
        document.getElementById('4'),
        document.getElementById('5')
    ])

        .on('drag', function(el) {

            // add 'is-moving' class to element being dragged
            el.classList.add('is-moving');
        })
        .on('dragend', function(el) {

            // remove 'is-moving' class from element after dragging has stopped
            el.classList.remove('is-moving');

            // add the 'is-moved' class for 600ms then remove it
            window.setTimeout(function() {
                el.classList.add('is-moved');
                window.setTimeout(function() {
                    el.classList.remove('is-moved');
                }, 600);
            }, 100);
        });


    var createOptions = (function() {
        var dragOptions = document.querySelectorAll('.drag-options');

        // these strings are used for the checkbox labels
        var options = ['Research', 'Strategy', 'Inspiration', 'Execution'];

        // create the checkbox and labels here, just to keep the html clean. append the <label> to '.drag-options'
        function create() {
            for (var i = 0; i < dragOptions.length; i++) {

                options.forEach(function(item) {
                    var checkbox = document.createElement('input');
                    var label = document.createElement('label');
                    var span = document.createElement('span');
                    checkbox.setAttribute('type', 'checkbox');
                    span.innerHTML = item;
                    label.appendChild(span);
                    label.insertBefore(checkbox, label.firstChild);
                    label.classList.add('drag-options-label');
                    dragOptions[i].appendChild(label);
                });

            }
        }

        return {
            create: create
        }


    }());

    var showOptions = (function () {

        // the 3 dot icon
        var more = document.querySelectorAll('.drag-header-more');

        function show() {
            // show 'drag-options' div when the more icon is clicked
            var target = this.getAttribute('data-target');
            var options = document.getElementById(target);
            options.classList.toggle('active');
        }


        function init() {
            for (i = 0; i < more.length; i++) {
                more[i].addEventListener('click', show, false);
            }
        }

        return {
            init: init
        }
    }());

    createOptions.create();
    showOptions.init();
</script>
