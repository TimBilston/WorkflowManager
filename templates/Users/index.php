<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>
<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="webroot/css/home.css">
<link rel="stylesheet" href="webroot/css/tasks.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<div class="users index content">
    <?= $this->Html->link(__('New User'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <div style="display: flex; flex-direction: row">
        <button onclick = "nextWeek()" style="margin: auto" > < </button>
        <h1 id="Month_Text"> August 2021 </h1>
        <h1 id="fucker"></h1>
        <button onclick = "prevWeek()" style="margin: auto" > > </button>
    </div>

    <script src = "https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.1/moment.min.js"></script>
    <script src ="webroot/js/jquery-1.4.1.js"></script>


    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th id = "Mon"></th>
                    <th id = "Tue"></th>
                    <th id = "Wed"></th>
                    <th id = "Thu"></th>
                    <th id = "Fri"></th>
                </tr>
            </thead>
            <tbody>
            <script>
                function doSomething() {
                    //compare dates and turn display to visible if good
                    var elements = document.getElementsByClassName('task-card');
                    var names = document.getElementsByClassName('names');
                    var MD = getDateString(currentMonday, 0);
                    var TuD = getDateString(currentMonday, 1);
                    var WD = getDateString(currentMonday, 2);
                    var ThD = getDateString(currentMonday, 3);
                    var FD = getDateString(currentMonday, 4);
                    for(let j = 0; j < names.length; j++){
                        for (let i = 0; i < elements.length; i++) {
                            //for testing elements[i].innerHTML = "foo";
                            if(elements[i].childNodes[3].innerText == MD && elements[i].childNodes[5].innerText == names[j].id){
                                document.getElementById("M_TD " + names[j].id).append(elements[i]);
                                elements[i].style.display = "block";
                            }
                            else if(elements[i].childNodes[3].innerText == TuD  && elements[i].childNodes[5].innerText == names[j].id){
                                document.getElementById("Tu_TD " + names[j].id).append(elements[i]);
                                elements[i].style.display = "block";
                            }
                            else if(elements[i].childNodes[3].innerText == WD  && elements[i].childNodes[5].innerText == names[j].id){
                                document.getElementById("W_TD " + names[j].id).append(elements[i]);
                                elements[i].style.display = "block";
                            }
                            else if(elements[i].childNodes[3].innerText == ThD && elements[i].childNodes[5].innerText == names[j].id){
                                document.getElementById("Th_TD " + names[j].id).append(elements[i]);
                                elements[i].style.display = "block";
                            }
                            else if(elements[i].childNodes[3].innerText == FD  && elements[i].childNodes[5].innerText == names[j].id){
                                document.getElementById("F_TD " + names[j].id).append(elements[i]);
                                elements[i].style.display = "block";
                            }
                            else if (elements[i].childNodes[3].innerText != MD && elements[i].childNodes[3].innerText != TuD && elements[i].childNodes[3].innerText != WD && elements[i].childNodes[3].innerText != ThD && elements[i].childNodes[3].innerText != FD){
                                elements[i].style.display = "none"
                            }
                        }
                    }
                    }
            </script>

            <?php foreach ($users as $user): ?>
                <?php foreach ($user->tasks as $task) {
                    ?>
                    <?php
                    $output = "";
                    $output .= '<li class="task-card" style = "display : none" id =' . $task->id . '>
                                            <h4 style = "margin-bottom: 0rem">' . $task->title . '</h4>
                                            <p class="due_time">' . $task->due_date . '</p>
                                            <p class ="person">'.$user->id .'</p>
                                            <p class="desc" >' . substr($task->description,0,15) . '...</p>
                                            <p class = "test"> ' . $this->Html->link(__('View'), ['controller' => 'tasks', 'action' => 'view', $task->id]) . ' </p></li>';
                    echo $output;
                }?>
                <tr>
                    <td class = "names" id = <?=$user->id?>><?= $this->Html->link(__(h($user->name) . ' ' . $user->last_name[0]), ['action' => 'view', $user->id]) ?></td>
                    <td id = "M_TD <?=$user->id?>"></td>
                    <td id = "Tu_TD <?=$user->id?>"></td>
                    <td id = "W_TD <?=$user->id?>"></td>
                    <td id = "Th_TD <?=$user->id?>"></td>
                    <td id = "F_TD <?=$user->id?>"></td>

                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
<script>
    var Monday = "";
    var Tuesday = "";
    var Wednesday = "";
    var Thursday = "";
    var Friday = "";

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
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

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
    }
    function nextWeek(){
        currentMonday.setDate(currentMonday.getDate() - 7);
        changeDates(currentMonday);
    }
    function prevWeek(){
        currentMonday.setDate(currentMonday.getDate() + 7);
        changeDates(currentMonday);
    }
    function returnMonday(){
        changeDates(currentMonday);
        return currentMonday;
    }
</script>
