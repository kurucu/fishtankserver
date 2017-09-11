<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">

    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <meta name="apple-mobile-web-app-title" content="Fishtank">
    <meta name="apple-mobile-web-app-capable" content="yes">


    <title>Fishtank</title>
</head>
<body>

    <div class="container">

        <div class="row mt-3">
            <div class="col">
                <div class="card">
                    <div class="card-body bg-danger text-white" id="stateDivision">
                        <h1 id="stateDivisionText">Loading...</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <button type="button" class="btn btn-outline-success btn-lg btn-block" id="dayButton">Day</button>
            </div>
            <div class="col">
                <button type="button" class="btn btn-outline-info btn-lg btn-block" id="autoButton">Auto</button>
            </div>
            <div class="col">
                <button type="button" class="btn btn-outline-dark btn-lg btn-block" id="nightButton">Night</button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <button type="button" class="btn btn-outline-secondary btn-lg btn-block" id="offButton">Off</button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <p>
                            It can take up to 10 seconds for the tank to notice your command.
                        </p>
                        <p>
                            Current Date/Time: <span id="currentTime"></span>
                        </p>
                        <p>
                            Sunrise Time: <span id="sunriseTime"></span><br />
                            Sunset Time: <span id="sunsetTime"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script   src="https://code.jquery.com/jquery-3.2.1.min.js"   integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="   crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

    <script>

    function setState(state)
    {
        var url = "/state/" + state;

        $.getJSON( url, function( data ) {
            var feedback_state = data['requested_state'];
            var setting = data['data']['setting'];
            var actual = data['data']['actual'];
            var data = data['data'];
            updateApp(feedback_state, actual, setting, data)
        });
    }

    function getState()
    {
        var url = "/get_state";

        $.getJSON( url, function( data ) {
            var feedback_state = data['requested_state'];
            var setting = data['data']['setting'];
            var actual = data['data']['actual'];
            var data = data['data'];
            updateApp(feedback_state, actual, setting, data)
        });
    }

    function updateApp(state, actual, setting, data)
    {
        switch(state)
        {
            case('day'):
                $("#dayButton").addClass('active');
                $("#autoButton").removeClass('active');
                $("#nightButton").removeClass('active');
                $("#offButton").removeClass('active');
                break;
            case('night'):
                $("#dayButton").removeClass('active');
                $("#autoButton").removeClass('active');
                $("#nightButton").addClass('active');
                $("#offButton").removeClass('active');
                break;
            case('off'):
                $("#dayButton").removeClass('active');
                $("#autoButton").removeClass('active');
                $("#nightButton").removeClass('active');
                $("#offButton").addClass('active');
                break;
            default:
                $("#dayButton").removeClass('active');
                $("#autoButton").addClass('active');
                $("#nightButton").removeClass('active');
                $("#offButton").removeClass('active');
        }

        switch(actual)
        {
            case('day'):
                $('#stateDivision').addClass('bg-success');
                $('#stateDivision').removeClass('bg-dark');
                $('#stateDivision').removeClass('bg-secondary');
                $('#stateDivision').removeClass('bg-danger');
                $('#stateDivisionText').text("Day (" + setting + ")");
                break;
            case('night'):
                $('#stateDivision').removeClass('bg-success');
                $('#stateDivision').addClass('bg-dark');
                $('#stateDivision').removeClass('bg-secondary');
                $('#stateDivision').removeClass('bg-danger');
                $('#stateDivisionText').text("Night (" + setting + ")");
                break;
            case('off'):
                $('#stateDivision').removeClass('bg-success');
                $('#stateDivision').removeClass('bg-dark');
                $('#stateDivision').addClass('bg-secondary');
                $('#stateDivision').removeClass('bg-danger');
                $('#stateDivisionText').text("Off (" + setting + ")");
                break;
            default:
                $('#stateDivision').removeClass('bg-success');
                $('#stateDivision').removeClass('bg-dark');
                $('#stateDivision').removeClass('bg-secondary');
                $('#stateDivision').addClass('bg-danger');
                $('#stateDivisionText').text("Unknown (" + setting + ")");
        }

        $('#currentTime').text(data['time']);
        $('#sunriseTime').text(data['sunrise']);
        $('#sunsetTime').text(data['sunset']);
    }

    $(document).ready(function() {

        $("#dayButton").click(function(){
            setState("day");
        });

        $("#autoButton").click(function(){
            setState("auto");
        });

        $("#nightButton").click(function(){
            setState("night");
        });

        $("#offButton").click(function(){
            setState("off");
        });

        getState();
    });

    </script>
</body>
</html>
