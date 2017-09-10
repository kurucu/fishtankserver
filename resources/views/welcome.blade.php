<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">

    <title>Fishtank</title>
</head>
<body>

    <div class="container">

        <div class="row mt-3">
            <div class="col">
                <div class="card">
                    <div class="card-body bg-success text-white">
                        <h1>Day (Auto)</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <button type="button" class="btn btn-outline-success btn-lg btn-block" id="dayButton">Day</button>
            </div>
            <div class="col">
                <button type="button" class="btn btn-outline-info btn-lg btn-block active" id="autoButton">Auto</button>
            </div>
            <div class="col">
                <button type="button" class="btn btn-outline-dark btn-lg btn-block" id="nightButton">Night</button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <button type="button" class="btn btn-secondary btn-lg btn-block" id="offButton">Off</button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        This is some info.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

    <script>

    $(document).ready({

        $("#dayButton").click({
            $.getJSON( "/state/day", function( data ) {
                var feedback_state = data['requested_state'];
                alert(feedback_state);
            });
        });

        $("#autoButton").click({
            $.getJSON( "/state/auto", function( data ) {
                var feedback_state = data['requested_state'];
                alert(feedback_state);
            });
        });

        $("#nightButton").click({
            $.getJSON( "/state/night", function( data ) {
                var feedback_state = data['requested_state'];
                alert(feedback_state);
            });
        });

        $("#offButton").click({
            $.getJSON( "/state/off", function( data ) {
                var feedback_state = data['requested_state'];
                alert(feedback_state);
            });
        });
    });

    </script>
</body>
</html>
