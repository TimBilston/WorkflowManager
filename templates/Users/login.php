<!-- in /templates/Users/login.php -->
<?php

    $this->disableAutoLayout();

?>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'cake', 'custom']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">

<body>
<nav class="top-nav">
</nav>
<main class="main">
    <div class="container">
        <div class="users form">
            <?= $this->Flash->render() ?>
            <h3>Login</h3>
            <?= $this->Form->create() ?>
            <fieldset>
                <legend><?= __('Please enter your username and password') ?></legend>
                <?= $this->Form->control('email', ['required' => true]) ?>
                <?= $this->Form->control('password', ['required' => true]) ?>
            </fieldset>
            <?= $this->Form->submit(__('Login')); ?>
            <?= $this->Form->end() ?>

            <?= $this->Html->link("Add User", ['action' => 'add']) ?>
        </div>

    </div>
</main>
<footer>
</footer>
</body>
</html>
