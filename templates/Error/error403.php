<?php

$this->disableAutoLayout();

?>
<html>
<a href="" class="fa fa-arrow-left"></a>
<div class="error container">
    <div>
        <h1 style="display: inline-block;">403</h1>

        <img src="<?php echo $this->Url->image('lock.png')?>" style="width: 150px">

    </div>

    <p>You do not have permission to visit this page. Please go back</p>
    <button class="button" style="vertical-align:middle" onclick="history.go(-1)"><span>Go back</span></button>
</div>
</html>

<style>

    body,
    html {
        padding: 0;
        margin: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        background-color: #810529;;
        font-family: 'Montserrat', sans-serif;
        color: #fff
    }

    html {
        background: url('https://static.pexels.com/photos/818/sea-sunny-beach-holiday.jpg');
        background-size: cover;
        background-position: bottom
    }

    .error {
        text-align: center;
        padding: 16px;
        position: relative;
        top: 50%;
        transform: translateY(-50%);
        -webkit-transform: translateY(-50%)
    }

    h1 {
        margin: -10px 0 -30px;
        font-size: calc(17vw + 40px);
        opacity: .8;
        letter-spacing: -17px;
    }

    p {
        opacity: .8;
        font-size: 20px;
        margin: 8px 0 38px 0;
        font-weight: bold
    }

    input,
    button,
    input:focus,
    button:focus {
        border: 0;
        outline: 0!important;
    }

    input {
        width: 300px;
        padding: 14px;
        max-width: calc(100% - 80px);
        border-radius: 6px 0 0 6px;
        font-weight: 400;
        font-family: 'Montserrat', sans-serif;
    }

    button {
        width: 40px;
        padding: 14.5px 16px 14.5px 12.5px;
        vertical-align: top;
        border-radius: 0 6px 6px 0;
        color: grey;
        background: silver;
        cursor: pointer;
        transition: all 0.4s
    }

    button:hover {
        color: white;
        background: lightcoral;
    }

    .fa-arrow-left {
        position: fixed;
        top: 30px;
        left: 30px;
        font-size: 2em;
        color:white;
        text-decoration:none
    }

    .button {
        display: inline-block;
        border-radius: 4px;
        background-color: honeydew;
        opacity: 80%;
        border: none;
        color: #000000;
        text-align: center;
        font-size: 20px;
        padding: 20px;
        width: 150px;
        transition: all 0.5s;
        cursor: pointer;
        margin: 5px;
    }

    .button span {
        cursor: pointer;
        display: inline-block;
        position: relative;
        transition: 0.5s;
    }

    .button span:after {
        content: '\00ab';
        position: absolute;
        opacity: 0;
        top: 0;
        left: -20px;
        transition: 0.5s;
    }

    .button:hover span {
        padding-left: 25px;
    }

    .button:hover span:after {
        opacity: 1;
        left: 0;
    }
</style>
