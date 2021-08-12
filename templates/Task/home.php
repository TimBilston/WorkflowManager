<?php
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

$this->disableAutoLayout();

if (!Configure::read('debug')) :
    throw new NotFoundException(
        'Please replace templates/Pages/home.php with your own version or re-enable debug mode.'
    );
endif;

$cakeDescription = 'CakePHP: the rapid development PHP framework';
?>
<!DOCTYPE html>
<html>

<title>Natures Bonding</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="webroot/css/style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="webroot/css/home.css">
<link rel = "stylesheet" href = "https://www.w3schools.com/lib/w3.css">
<?= $this->Html->css(['normalize.min', 'milligram.min', 'cake']) ?>
<body>
    <div class="drag-container">
        <ul class="drag-list">
            <li class="drag-column drag-column-on-hold">
                <span class="drag-column-header">
                    <h2>Monday</h2>
                </span>

                <div class="drag-options" id="options1"></div>

                <ul class="drag-inner-list" id="1">
                    <?php foreach ($task as $tsk): ?>
                        <li class="drag-item">
                            <div class="id">testId: <?php echo $tsk->id ?></div>
                            <div class="title">title: <?php echo $tsk->title ?></div>
                            <div class="title">description: <?php echo $tsk->description ?></div>
                            <div class="title">start date: <?php echo $tsk->start_date ?></div>
                            <div class="title">due date: <?php echo $tsk->due_date ?></div>
                            <!-- copy this code -->
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <!---only for monday-->
            <li class="drag-column drag-column-in-progress">
                <span class="drag-column-header">
                    <h2>tuesday</h2>
                </span>
                <div class="drag-options" id="options2"></div>
                <ul class="drag-inner-list" id="2">
                    <li class="drag-item"></li>
                    <li class="drag-item"></li>
                    <li class="drag-item"></li>
                </ul>
            </li>
            <li class="drag-column drag-column-needs-review">
                <span class="drag-column-header">
                    <h2>wednesay</h2>

                </span>
                <div class="drag-options" id="options3"></div>
                <ul class="drag-inner-list" id="3">
                    <?php foreach ($task as $tsk): ?>
                        <li class="drag-item">
                            <div class="id">testId: <?php echo $tsk->id ?></div>
                            <div class="title">title: <?php echo $tsk->title ?></div>
                            <div class="title">description: <?php echo $tsk->description ?></div>
                            <div class="title">start date: <?php echo $tsk->start_date ?></div>
                            <div class="title">due date: <?php echo $tsk->due_date ?></div>
                            <!-- copy this code -->
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <li class="drag-column drag-column-approved">
                <span class="drag-column-header">
                    <h2>thursday</h2>
                </span>
                <div class="drag-options" id="options4"></div>
                <ul class="drag-inner-list" id="4">
                    <li class="drag-item"></li>
                    <li class="drag-item"></li>
                </ul>
            </li>
            <li class="drag-column drag-column-finish">
                <span class="drag-column-header">
                    <h2>friday</h2>
                </span>
                <div class="drag-options" id="options5"></div>
                <ul class="drag-inner-list" id="5">
                    <li class="drag-item"></li>
                    <li class="drag-item"></li>
                </ul>
            </li>
        </ul>
    </div>

</div>
<script src="js/jquery-1.4.1.js"></script>
<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/45226/dragula.min.js"></script>
<script>

    dragula([
        document.getElementById("1"),
        document.getElementById("2"),
        document.getElementById("3"),
        document.getElementById("4"),
        document.getElementById("5")
    ])
	.on("drag", function (el) {
		// add 'is-moving' class to element being dragged
		el.classList.add("is-moving");
	})
	.on("dragend", function (el) {
		// remove 'is-moving' class from element after dragging has stopped
		el.classList.remove("is-moving");

		// add the 'is-moved' class for 600ms then remove it
		window.setTimeout(function () {
			el.classList.add("is-moved");
			window.setTimeout(function () {
				el.classList.remove("is-moved");
			}, 600);
		}, 100);
	});

    var createOptions = (function () {
        var dragOptions = document.querySelectorAll(".drag-options");

        // these strings are used for the checkbox labels
        var options = ["Research", "Strategy", "Inspiration", "Execution"];

        // create the checkbox and labels here, just to keep the html clean. append the <label> to '.drag-options'
        function create() {
            for (var i = 0; i < dragOptions.length; i++) {
                options.forEach(function (item) {
                    var checkbox = document.createElement("input");
                    var label = document.createElement("label");
                    var span = document.createElement("span");
                    checkbox.setAttribute("type", "checkbox");
                    span.innerHTML = item;
                    label.appendChild(span);
                    label.insertBefore(checkbox, label.firstChild);
                    label.classList.add("drag-options-label");
                    dragOptions[i].appendChild(label);
                });
            }
        }

        return {
            create: create
        };
    })();

    var showOptions = (function () {
        // the 3 dot icon
        var more = document.querySelectorAll(".drag-header-more");

        function show() {
            // show 'drag-options' div when the more icon is clicked
            var target = this.getAttribute("data-target");
            var options = document.getElementById(target);
            options.classList.toggle("active");
        }

        function init() {
            for (i = 0; i < more.length; i++) {
                more[i].addEventListener("click", show, false);
            }
        }

        return {
            init: init
        };
    })();

    createOptions.create();
    showOptions.init();


</script>
</body>
</html>
